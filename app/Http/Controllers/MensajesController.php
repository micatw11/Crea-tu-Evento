<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use App\Reserva;
use App\Mensaje;

class MensajesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $mensajesId = DB::table('mensajes')
                ->join('users', 'mensajes.from_user_id', '=', 'users.id')
                ->join('usuarios', 'usuarios.user_id', '=', 'users.id')
                ->where('mensajes.estado', 1)
                ->where('from_user_id', Auth::id())
                ->orWhere('to_user_id', Auth::id())
                ->groupBy('mensajes.reserva_id')
                ->select(DB::raw('MAX(mensajes.ancestro_id) as ancestro_id'),DB::raw('MAX(mensajes.id) as id'))->pluck('id');
                
        $query = Mensaje::select('mensajes.*' )
                ->whereIn('mensajes.id', $mensajesId)
                ->with('fromUser.usuario', 'toUser.usuario', 'reserva.publicacion.proveedor')->orderBy('created_at', 'desc');

        if( $request->has('page') || $request->has('per_page') ) 
            $mensajes = $query->paginate(10);
        else
            $mensajes = $query->get();

        $mensajes = $this->setCountLectura($mensajes);              

        return response()->json($mensajes, Response::HTTP_OK);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $form_user_id = Auth::id();
        $to_user_id = null;

        $mensajeAnterior = Mensaje::where('id', $request->prev_message_id)->firstOrFail();

        if($mensajeAnterior->to_user_id == $form_user_id){
            $to_user_id = $mensajeAnterior->from_user_id;
        }
        else
        {
            $to_user_id = $mensajeAnterior->to_user_id;
        }

        $newMensaje = Mensaje::create([ 'from_user_id' => Auth::id(), 'to_user_id' => $to_user_id, 
                        'ancestro_id' => $mensajeAnterior->id, 'mensaje' => $request->mensaje, 
                        'reserva_id' => $mensajeAnterior->reserva_id]);
        if( $newMensaje->save() ){
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
        $mensaje = Mensaje::where('id', $id)->firstOrFail();
        $reserva = Reserva::where('id', $mensaje->reserva_id)
                        ->with('publicacion.proveedor.user.usuario','articulos', 'rubros', 'domicilio.localidad.provincia', 'user.usuario', 'horario')->first();

        Mensaje::where('reserva_id', $mensaje->reserva_id)->where('to_user_id', Auth::id())->update(['lectura' => true]);


        $mensajes = Mensaje::where('reserva_id', $mensaje->reserva_id)
                        ->where('estado', 1)
                        ->with('fromUser.usuario', 'toUser.usuario')->get();

        return response()->json(['mensajes' => $mensajes, 'presupuesto' => $reserva], Response::HTTP_OK);
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
        //
    }

    protected function setCountLectura($mensajes){
        $nuevosMensajes = DB::table('mensajes')
            ->join('users', 'mensajes.from_user_id', '=', 'users.id')
            ->join('usuarios', 'usuarios.user_id', '=', 'users.id')
            ->where('mensajes.estado', 1)
            ->where('to_user_id', Auth::id())
            ->groupBy('mensajes.reserva_id')
            ->select(DB::raw('MAX(mensajes.ancestro_id) as ancestro_id'),
                DB::raw('MAX(mensajes.id) as id'), 
                DB::raw('COUNT(case mensajes.lectura WHEN FALSE THEN 1 ELSE NULL END) as nuevos'))->get();
        foreach ($mensajes as $mensaje) {
           foreach ($nuevosMensajes as $nuevoMensaje) {
                if($nuevoMensaje->id == $mensaje->id)
                {
                    $mensaje->nuevos = $nuevoMensaje->nuevos;
                }
           }
        }
        return $mensajes;
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
