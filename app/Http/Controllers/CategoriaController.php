<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use App\Categoria;
use App\Log;

class CategoriaController extends Controller
{
    
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return Retorna una lista del recurso Categoria
     */
    public function index(Request $request)
    {
        $query = Categoria::orderBy('nombre', 'asc');

        //filtro
        if ($request->filter) {
            $like = '%'.$request->filter.'%';
            $query = $query->where('categorias.nombre','like',$like);
        }

        $categorias = $query->paginate(10);

        return response()->json($categorias, 200);

    }

    /**
     * @param  \Illuminate\Http\Request  $request
     * Crear un recurso Categoria
     *
     * @return \App\Categoria
     */
    protected function create(Request $request)
    {
        return Categoria::create([
                    'nombre'=> $request->nombre,
                    'tipo_proveedor' => $request->tipo_proveedor
            ]);
    }

    /**
     * Store a newly created resource Categoria in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //validacion de datos
        $this->validatorCategoria($request);
        //se crea el recurso categoria
        $categoria = $this->create($request);
        
        if ($categoria){
            return response(null, Response::HTTP_OK);
        } else {
            return response(null, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @param $request
     * valida los datos para un recurso Categoria
     */
    protected function validatorCategoria(Request $request)
    {
      return $this->validate($request, 
        [
            'nombre'=>'required|min:4|max:55',
            'tipo_proveedor'=>'required'
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
          $categoria= Categoria::where('id', $id)->with('subcategorias')->firstOrFail();

        if ($categoria) {
            return response()->json(['data' => $categoria], 200);
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
        $this->validatorCategoria($request);
        //$table_name= "rubro";
        //$accion = "update";
        $categoria= Categoria::where('id', $id)->firstOrFail();
        //Log::logs($id, $table_name, $accion , $rubro, 'Ha actualizado informacion personal');
        $categoria->update($request->all());
        if($categoria->save()){
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

    public function allCategoria(Request $request)
    {
        $categorias = Categoria::orderBy('nombre', 'asc')->get();

        return response()->json($categorias, 200);
    }
}
