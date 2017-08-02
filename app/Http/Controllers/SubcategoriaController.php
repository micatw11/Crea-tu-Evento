<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Subcategoria;


class SubcategoriaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = Subcategoria::orderBy('nombre', 'asc');

        if ($request->filter) {
            $like = '%'.$request->filter.'%';
            $query = $query->where('nombre','like',$like);
        }

        $subcategorias = $query->paginate(10);

        return response()->json($subcategorias);
    }

    public function store(Request $request){
        $this->validatorSubcategoria($request);
        $categoria= $this->create($request);
        
        if ($categoria){
            return response()->json(['data' => 'OK'], 200);
        } else {
            return response()->json(['error' =>  'Internal Server Error'], 500);
        }
    }

    /**
     * @param $request
     */
    protected function validatorSubcategoria(Request $request)
    {
      return $this->validate($request, 
        [
            'nombre'=>'required|min:4|max:55'
        ]);
    }

    /**
    * @param  \Illuminate\Http\Request  $request
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    protected function create(Request $request)
    {
        return Subcategoria::create([
                    'nombre'=> $request->nombre
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
          $subcategoria= Subcategoria::where('id', $id)->firstOrFail();

        if ($subcategoria) {
            return response()->json(['data' => $subcategoria], 200);
        } else {
            return response()->json(['error' =>  'Internal Server Error'], 500);
        }
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
        $this->validatorSubcategoria($request);
        $subcategoria= Subcategoria::where('id', $id)->firstOrFail();

        $subcategoria->update($request->all());

        if($subcategoria->save()){
            return response()->json(['data' =>  'OK'], 200);
        } else {
            return response()->json(['error' => 'Internal Server Error'], 500 );
        }
    }

}
