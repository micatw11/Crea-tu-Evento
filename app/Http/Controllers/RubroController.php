<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Rubro;

class RubroController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = Rubro::with('subcategoria')->orderBy('nombre', 'asc');

        if ($request->filter) {
            $like = '%'.$request->filter.'%';
            $query = $query->where('nombre','like',$like);
        }

        $rubros = $query->paginate(10);

        return response()->json($rubros);
    }

    public function store(Request $request){
        $this->validatorRubro($request);
        $rubros= $this->create($request);
        
        if ($rubros){
            return response()->json(['data' => 'OK'], 200);
        } else {
            return response()->json(['error' =>  'Internal Server Error'], 500);
        }
    }

    /**
     * @param $request
     */
    protected function validatorRubro(Request $request)
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
        return Rubro::create([
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
          $rubros= Rubro::where('id', $id)->firstOrFail();

        if ($rubros) {
            return response()->json(['data' => $rubros], 200);
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
        $this->validatorRubro($request);
        $rubros= Rubro::where('id', $id)->firstOrFail();

        $rubros->update($request->all());

        if($rubros->save()){
            return response()->json(['data' =>  'OK'], 200);
        } else {
            return response()->json(['error' => 'Internal Server Error'], 500 );
        }
    }
}
