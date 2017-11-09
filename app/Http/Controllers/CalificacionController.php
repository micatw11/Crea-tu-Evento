<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use App\Calificacion;
use App\Publicacion;
use Carbon\Carbon;
use App\Reserva;

class CalificacionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $user_id)
    {
        if ($user_id == 'me') {
            $user_id = Auth::id();
        }
        $calificaciones = Calificacion::join('reservas', 'reservas.id', '=', 'calificaciones.reserva_id')
                    ->where('reservas.user_id', $user_id)->orderBy('reservas.fecha', 'ASC')
                    ->select('calificaciones.*')->with('publicacion.proveedor', 'reserva')->paginate(10);

        return response()->json($calificaciones, Response::HTTP_OK);
    }

    public function indexPendientes(Request $request, $user_id){
        if ($user_id == 'me') {
            $user_id = Auth::id();
        }
        $now = Carbon::now();
        $reservasId = Reserva::where('reservas.estado', 'confirmado')
            ->where('fecha', '<',$now->toDateString())
            ->where('reservas.user_id', $user_id)
            ->get()->pluck('id');

        $calificaciones = Calificacion::whereIn('reserva_id', $reservasId)->get()->pluck('reserva_id');

        $reservasNoCalificadas = Reserva::whereNotIn('id', $calificaciones)
            ->where('estado', 'confirmado')
            ->where('fecha', '<',$now->toDateString())
            ->orderBy('reservas.fecha', 'ASC')
            ->with('publicacion.proveedor','rubros','articulos')->get();

        return response()->json($reservasNoCalificadas,  Response::HTTP_OK);
    }

    protected function validateCalificacion(Request $request)
    {
        return $this->validate($request, 
            [
                'publicacion_id' => 'required|exists,publicaciones,id', 
                'reserva_id' => 'required|exists,reservas,id', 
                'calidad' => 'required|min:1|max:5',
                'calidad_precio' => 'required|min:1|max:5',
                'profesionalidad' => 'required|min:1|max:5',
                'respuesta' => 'required|min:1|max:5',
                'recomendar' => 'required|boolean',
                'comentario' => 'required|min:5|max:300'
            ]);
    }

    protected function createCalificacion(Request $request, $publicacion, $reserva)
    {
        return Calificacion::create([
                'publicacion_id' => $publicacion->id,
                'reserva_id' => $reserva->id,
                'calidad' => $request->calidad,
                'calidad_precio' => $request->calidad_precio,
                'profesionalidad' => $request->profesionalidad,
                'respuesta' => $request->respuesta,
                'recomendar' => $request->recomendar,
                'comentario' => $request->comentario,
                'puntuacion_total' => $request->puntuacion_total

            ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validateCalificacion($request);

        $reserva = Reserva::where('id', $request->reserva_id)->firstOrFail();
        $publicacion = Publicacion::where('id', $request->publicacion_id)->firstOrFail();

        $calificacion = $this->createCalificacion($request, publicacion, reserva);

        if($calificacion->save())
        {
            return response(null, Response::HTTP_OK);
        }
        else
        {
            return response(null, Response::HTTP_INTERNAL_SERVER_ERROR);
        } 
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $calificacion = Calificacion::where('id', $id)->firstOrFail();

        return response()->json($calificacion, Response::HTTP_OK);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validateCalificacion($request);
        $calificacion = Calificacion::where('id', $id)->firstOrFail();
        $calificacion->update($request);

        if( $calificacion->save() ){
            return response(null, Response::HTTP_OK);
        } else {
            return response(null, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
