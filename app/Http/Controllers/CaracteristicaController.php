<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Response;
use App\Caracteristicas;

class CaracteristicaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = Caracteristicas::orderBy('nombre', 'asc');

        //filtro
        if ($request->filter) {
            $like = '%'.$request->filter.'%';
            $query = $query->where('caracteristicas.nombre','like',$like);
        }

        $caracteristica = $query->paginate(10);

        return response()->json($caracteristica, 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
         return Caracteristicas::create([
                    'nombre'=> $request->nombre,
                    'adicional' => $request->adicional
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
        //validacion de datos
        $this->validatorCaracteristica($request);
        //se crea el recurso Caracteristicas
        $caracteristica = $this->create($request);
        
        if ($caracteristica){
            return response(null, Response::HTTP_OK);
        } else {
            return response(null, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @param $request
     * valida los datos para un recurso Caracteristicas de rubro
     */
    protected function validatorCaracteristica(Request $request)
    {
      return $this->validate($request, 
        [
            'nombre'=>'required|min:4|max:55'
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
        $caracteristica = Caracteristicas::where('id', $id)->firstOrFail();

        return response()->json(['caracteristica' => $caracteristica], 200);
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
        
        $this->validatorCaracteristica($request);
        //$table_name= "caracteristica";
        //$accion = "update";
        $caracteristica= Caracteristicas::where('id', $id)->firstOrFail();
        //Log::logs($id, $table_name, $accion , $caracteristica, 'Ha actualizado informacion personal');
        $caracteristica->update($request->all());
        if($caracteristica->save()){
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
