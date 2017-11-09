<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Horario;
use App\Publicacion;
use App\Proveedor;
use App\Rol;

class HorarioController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        $horarios= Horario::where('id', $id)->get();

        if ($horarios) {
            return response()->json( $horarios[0], 200);
        } else {
            return response(null, Response::HTTP_INTERNAL_SERVER_ERROR);
        }


    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function indexPublicacion($publicacion_id)
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
        $j = 0;
        $horarioNo[$j]= null;
        $idHorarios [0] = null;
        $save=false;
        $user = Auth::user();
        $horarioRepetido= null;
        $proveedor= Proveedor::where('user_id', $user->id)->firstOrFail();
        if ($request->horariosId != []){
                Horario::where('publicacion_id', $request->publicacion_id)
                            ->where('proveedor_id', $proveedor->id)
                            ->whereNotIn('id', $request->horariosId)->delete();
        }else{
                Horario::where('publicacion_id', $request->publicacion_id)
                            ->where('proveedor_id', $proveedor->id)->delete();
        } 
         
        for ($i=0; $i < sizeof($dias); $i++) {
            $diaUnico = $dias[$i];
            $horarioRepetido = Horario::where('publicacion_id', $request->publicacion_id)
                            ->where('proveedor_id', $proveedor->id)
                            ->where('dia', $diaUnico)
                            ->where(function ($q) use ($request){
                                     $q->whereBetween('hora_inicio', [$request->hora_inicio, $request->hora_fin])
                                    ->orWhereBetween('hora_fin', [$request->hora_inicio, $request->hora_fin])->get();
                            })->first();
        
                   if ($horarioRepetido) {
                         $horarioNo[$j]= $horarioRepetido;
                         $j = $j+1;
                   }
                   else{
                        $horario = Horario::create([
                            'dia' => $diaUnico,
                            'hora_inicio' => $request->hora_inicio,
                            'hora_fin' => $request->hora_fin,
                            'publicacion_id' => $request->publicacion_id,
                            'proveedor_id' => $proveedor->id,
                            'precio' => $request->precio
                        ]);
                        if($horario->save()){
                            $horarios[$i]=$horario;
                            $idHorarios [$i] = $horario->id;
                            $save=true;
                        }else{
                            $save=false;
                            $horarioNo [$j]= $horario;
                            $j = $j+1;
                        }
                    }
        }
        if($save){
                return response(['horarioNo' => $horarioNo, 'idHorario' => $idHorarios], Response::HTTP_OK);
            }
            else {
                return response(['horarioNo' => $horarioNo], Response::HTTP_INTERNAL_SERVER_ERROR);
            }
    }

    protected function validateHorario(Request $request)
    {
        return $this->validate($request, 
            [
                'dia'=>'required',  
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
         $horario= Horario::where('id', $id)->first();

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
        
        $this->validateHorario($request);
        $user = Auth::user();
        if($user->roles_id == Rol::roleId('proveedor')){
            $proveedor = Proveedor::where('user_id', $user->id)->firstOrFail();
            $publicacion = null;
            $horario = Horario::where('id', $id)->firstOrFail();
            if ($request->publicacion_id != null){
                $publicacion= Publicacion::where('id', $horario->publicacion_id)->where('proveedor_id', $proveedor->id)->firstOrFail();
            }else{
                $request->publicacion_id = null;
            }
            if ($publicacion||$proveedor){
            $horario->update([
                'dia' => $request->dia,
                'hora_inicio' => $request->hora_inicio,
                'hora_fin' => $request->hora_fin,
                'proveedor_id' => $proveedor->id,
                'publicacion_id' => $request->publicacion_id,
                'precio' => $request->precio
            ]);
            
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
        return response(null, Response::HTTP_UNAUTHORIZED);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
       $horario= Horario::where('id', $id)->firstOrFail();

        if ($horario->delete()) {
            return response(null, Response::HTTP_OK);
        }else {
            return response(null, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
