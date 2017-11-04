<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Horario;
use App\Reserva;
use App\Publicacion;
use Carbon\Carbon;

class HorarioController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($publicacion_id)
    {
        $horarios= Publicacion::where('id', $publicacion_id)->with('horarios')->get();

        if ($horarios) {
            return response()->json( $horarios[0], 200);
        } else {
            return response(null, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validateHorario($request);
        $dias = $request->dia;
        for ($i=0; $i < sizeof($dias); $i++) {
            $diaunico =  $dias[$i];
            $horario = Horario::create([
                'dia' => $diaunico,
                'hora_inicio' => $request->hora_inicio,
                'hora_fin' => $request->hora_fin,
                'publicacion_id' => $request->publicacion_id,
                'precio' => $request->precio
            ]);
            if($horario->save()){
                $horarios[$i]=$horario;
               $save=true;
            }else{
                $save=false;
            }
        }
        if($save){
                return response($horarios, Response::HTTP_OK);
            }
            else {
                return response(null, Response::HTTP_INTERNAL_SERVER_ERROR);
            }
    }

    protected function validateHorario(Request $request)
    {
        return $this->validate($request, 
            [
                'dia'=>'required', 
                'publicacion_id' => 'required', 
                'hora_inicio' => 'required', 
                'hora_fin' => 'nullable',
                'precio' => 'nullable'
            ]);
    
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
         $horario= Horario::where('id', $id)->firstOrFail();

        if ($horario) {
            return response()->json( $horario, 200);
        } else {
            return response(null, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        
       /* $this->validateHorario($request);
        $user = Auth::user();
        if($user->roles_id == Rol::roleId('proveedor')){
            $proveedor = Proveedor::where('user_id', $user->id)->firstOrFail();

            $horario = Horario::where('id', $id)->firstOrFail();
            $publicacion= Publicaion::where('id', $horario->publicacion_id)->where('proveedor_id', $proveedor->id)->firstOrFail())
            if ($publicacion){
            $horario->update($request);
            
                if($horario->save())
                {
                    return response()->json($horario, Response::HTTP_OK);
                }
                else
                {
                    return response(null, Response::HTTP_INTERNAL_SERVER_ERROR);
                }
            }
        }
        return response(null, Response::HTTP_UNAUTHORIZED);*/
    }

    public function horariosPorFecha(Request $request, $publicacion_id, $fecha){
        $dia = $this->toDayOfWeek($fecha);
        $horarios = Horario::where('publicacion_id', $publicacion_id)->where('dia', $dia)->get();
        $reserva_id = null;
        $reservas = Reserva::where('publicacion_id', $publicacion_id)
            ->where('estado', 'reservado')->orWhere('estado', 'confirmado')
            ->whereDate('fecha', '=', Carbon::createFromFormat('Y-m-d', $fecha)->toDateString())->get();
        
        if($request->has('except_this_reserva'))
        {
            $reserva_id = $request->except_this_reserva;
        }
        $auxHorarios = array();
        $auxHorario = null;
        foreach ($horarios as $horario) {
            $esta = false;
            foreach ($reservas as $reserva) {
                if($reserva->horario_id == $horario->id)
                {
                    $auxHorario = $horario;
                    if($reserva_id != null && $reserva_id == $reserva->id){
                        $auxHorario->estado = 'disponible';
                    }
                    else
                    {
                        $auxHorario->estado = 'reservado';
                    }
                    $auxHorarios[] = $auxHorario;
                    $auxHorario = null;
                    $esta = true;
                    break;
                }
            }
            if(!$esta){
                $auxHorario = $horario;
                $auxHorario->estado = 'disponible';
                $auxHorarios[] = $auxHorario;
                $auxHorario = null;
            }
        }
        return response()->json($auxHorarios, 200);
    }

    protected function toDayOfWeek($date){
        $dt = Carbon::createFromFormat('Y-m-d', $date);
        $dayOfWeek = $dt->format('l'); 
        $dia = '';
        switch ($dayOfWeek) {
            case 'Monday':
                $dia = 'lunes';
                break;
            case 'Tuesday':
                $dia = 'martes';
                break;
            case 'Wednesday':
                $dia = 'miercoles';
                break;
            case 'Thursday':
                $dia = 'jueves';
                break;
            case 'Friday':
                $dia = 'viernes';
                break;
            case 'Saturday':
                $dia = 'sabado';
                break;
            case 'Sunday':
                $dia = 'domingo';
                break;
            default:
                break;
        }
        return $dia;
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
