<?php

namespace App\Http\Controllers;

use App\Http\Services\DomicilioService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use App\Publicacion;
use App\Domicilio;
use App\Mensaje;
use App\Reserva;

class ReservaController extends Controller
{

    /**
     * @var DomicilioService
     */
    private $domicilioService;

    /**
     * ReservaController constructor.
     */
    public function __construct( DomicilioService $domicilioService)
    {
        $this->domicilioService = $domicilioService;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    public function reservasUser(Request $request, $user_id)
    {
        $reservas = Reserva::where('user_id', $user_id)->where('estado', 'confirmado')
            ->with('publicacion.proveedor','rubros','articulos')->get();

        return response()->json($reservas, Response::HTTP_OK);
    }

    protected function validateReserva(Request $request, $is_presupuesto)
    {
        if($is_presupuesto){
            return $this->validate($request, 
                [
                    'horario_id' => 'required',
                    'fecha' => 'required',
                    'articulos.*.articulo_id' => 'required',
                    'rubros' => 'required',
                    'estado' => 'required|in:presupuesto,reservado,confirmado,cancelado'
                ]);
        } else{
            return $this->validate($request, 
                [
                    'horario_id' => 'required',
                    'fecha' => 'required',
                    'articulos.*.articulo_id' => 'required',
                    'articulos.*.cantidad' => 'required|min:1',
                    'rubros' => 'required',
                    'precio_total' => 'required|regex:/^\d*(\.\d{2})?$/',
                    'estado' => 'required|in:presupuesto,reservado,confirmado,cancelado'
                ]);
        }
    }

    protected function createReserva(Request $request, $publicacion, $user, $domicilio_id, $precio_total)
    {
        return Reserva::create([
                'publicacion_id' => $publicacion->id,
                'user_id' => $user->id,
                'fecha' => $request->fecha,
                'hora_inicio' => '00:00:00',
                'hora_finalizacion' => '00:00:00',
                'domicilio_id' => $domicilio_id,
                'precio_total' => $precio_total,
                'estado' => $request->estado
            ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $publicacion_id)
    {
        $requiredDomicilio = false;
        $precio_total = 0;

        $publicacion = Publicacion::where('id', $publicacion_id)
                ->with('prestacion.rubros', 'prestacion.domicilio', 'articulos')->firstOrFail();

        $user = Auth::user();

        $this->validateReserva($request, false);

        foreach ($publicacion->prestacion->rubros as $rubro) { 
            if($rubro->salon){
                $requiredDomicilio = false;
                break;
            }
        }

        foreach($request->articulos as $articuloSelect){
                
            foreach ($publicacion->articulos as $articulo) {
                if($articuloSelect['articulo_id'] == $articulo->id){
                    $precio_total = $precio_total + ($articulo->precio * $articuloSelect['cantidad']);
                }
            }
        }

        // precio mas precio horario

        if( $requiredDomicilio ){
            $this->domicilioService->validateDomicilio($request);
            $domicilio = $this->domicilioService->createDomicilio($request, 'Social');
        } else {
            $domicilio = Domicilio::where('id', $publicacion->prestacion->domicilio_id )->firstOrFail();
        }

        $reserva = $this->createReserva($request, $publicacion, $user, $domicilio->id, $precio_total);
        $reserva->articulos()->attach($request->articulos);
        $reserva->rubros()->attach($request->rubros);

        if( $reserva->save() )
        {
            return response(null, Response::HTTP_OK);
        }
        else
        {
            return response(null, Response::HTTP_INTERNAL_SERVER_ERROR);
        } 
    }

    public function storePresupuesto(Request $request, $publicacion_id)
    {
        $requiredDomicilio = false;
        $domicilio = null;

        $publicacion = Publicacion::where('id', $publicacion_id)
                ->with('prestacion.rubros', 'prestacion.domicilio', 'articulos', 'proveedor')->firstOrFail();

        $user = Auth::user();

        $this->validateReserva($request, true);

        foreach ($publicacion->prestacion->rubros as $rubro) { 
            if($rubro->salon){
                $requiredDomicilio = false;
                break;
            }
        }

        // precio mas precio horario

        if( $requiredDomicilio ){
            $this->domicilioService->validateDomicilio($request);
            $domicilio = $this->domicilioService->createDomicilio($request, 'Social');
        } else {
            $domicilio = Domicilio::where('id', $publicacion->prestacion->domicilio_id )->firstOrFail();
        }

        $reserva = $this->createReserva($request, $publicacion, $user, $domicilio->id, 0.00);
        $reserva->articulos()->attach($request->articulos);
        $reserva->rubros()->attach($request->rubros);
        $mensaje = Mensaje::create([ 'from_user_id' => Auth::id(), 'to_user_id' => $publicacion->proveedor->user_id, 
                    'ancestro_id' => null, 'mensaje' => $request->comentario, 'reserva_id' => $reserva->id]);
        if( $reserva->save() )
        {
            return response(null, Response::HTTP_OK);
        }
        else
        {
            return response(null, Response::HTTP_INTERNAL_SERVER_ERROR);
        } 
    }

    public function cambiarEstado(Request $request, $id)
    {
        $this->validate($request, [
                'estado' => 'required|in:presupuesto,reservado,confirmado,cancelado'
            ]);
        $reserva = Reserva::where('id', $id)->firstOrFail();
        if(Auth::id() == $reserva->user_id)
        {
            $reserva->update(['estado' => $request->estado]);

            if( $reserva->save() )
            {
                return response(null, Response::HTTP_OK);
            }
            else 
            {
                return response(null, Response::HTTP_INTERNAL_SERVER_ERROR);
            }
        }
        else
        {
            return response(null, Response::HTTP_UNAUTHORIZED);
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
        $reserva = Reserva::where('id', $id)->with('publicacion','user','rubros','articulos')->firstOrFail();
        return response()->json($reserva, Response::HTTP_OK);
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

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updatePresupuesto(Request $request, $id)
    {
        $reserva = Reserva::where('id', $id)->where('estado', 'presupuesto')->firstOrFail();
        $reserva->articulos()->detach();
        $reserva->articulos()->attach($request->articulos);
        $reserva->precio_total = $reserva->precio_total + $request->precio_total;
        
        if( $reserva->save() )
        {
            return response(null, Response::HTTP_OK);
        }
        else 
        {
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
