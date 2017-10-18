<?php

namespace App\Http\Controllers;

use App\Http\Services\DomicilioService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use App\Publicacion;
use App\Domicilio;
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

    protected function validateReserva(Request $request)
    {
        return $this->validate($request, 
            [
                'horario_id' => 'required',
                'fecha' => 'required',
                'precio_total' => 'required|regex:/^\d*(\.\d{1,2})?$/',
                'articulos.*.articulo_id' => 'required',
                'articulos.*.cantidad' => 'required|min:1',
                'rubros' => 'required',
                'estado' => 'required|in:presupuesto,reservado,confirmado,cancelado'
            ]);
    }

    protected function createReserva(Request $request, $publicacion, $user, $domicilio_id, $precio_total)
    {
        return Reserva::create([
                'publicacion_id' => $publicacion->id,
                'user_id' => $user->id,
                'fecha' => $request->fecha,
                'hora_inicio' => '00:00:00',
                'hora_finalizacion' => '00:00:00',
                'precio_total' => $precio_total,
                'domicilio_id' => $domicilio_id,
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

        $this->validateReserva($request);

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

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $reserva = Reserva::where('id', $id)->firstOrFail();
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
        //
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
