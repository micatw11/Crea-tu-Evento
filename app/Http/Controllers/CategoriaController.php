<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Categoria;
use App\Log;

class CategoriaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = Categoria::orderBy('nombre', 'asc');

        if ($request->filter) {
            $like = '%'.$request->filter.'%';
            $query = $query->where('nombre','like',$like)->orWhere('descripcion','like',$like);
        }

        $categorias = $query->paginate(10);

        if ($categorias) {
            return response()->json($categorias);
        } else {
            return response()->json(['error' =>  'Internal Server Error'], 500);
        }
    }

    /**
    * @param  \Illuminate\Http\Request  $request
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        return Categoria::create([
                    'nombre'=> $request->nombre,
                    'descripcion'=> $request->descripcion,
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
        $this->validatorCategoria($request);
        $categoria= $this->create($request);
        
        if ($categoria){
            return response()->json(['data' => 'OK'], 200);
        
        } else {
            return response()->json([
                'error' => 'Unauthorized'], 401);
        }
    }

        /**
     * @param $request
     */
    protected function validatorCategoria(Request $request)
    {
      return $this->validate($request, 
        [
            'nombre'=>'required|min:4|max:55',
            'descripcion'=> 'max:200'
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
          $categoria= Categoria::where('id', $id)->firstOrFail();

        if ($categoria) {
            return response()->json(['data' => $categoria], 200);
        } else {
            return response()->json(['error' =>  'Internal Server Error'], 500);
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
        $this->validatorCategoria($request);
        //$table_name= "rubro";
        //$accion = "update";
        $categoria= Categoria::where('id', $id)->firstOrFail();
        //Log::logs($id, $table_name, $accion , $rubro, 'Ha actualizado informacion personal');
        $categoria->update($request->all());
        if($categoria->save()){
            return response()->json(['data' =>  'OK'], 200);
        } else {
            return response()->json(['error' => 'Internal Server Error'], 500 );
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
