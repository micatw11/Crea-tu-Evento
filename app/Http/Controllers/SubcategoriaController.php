<?php

namespace App\Http\Controllers;

use App\Http\Services\CheckCategoriesService;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use App\Subcategoria;
use App\Categoria;

class SubcategoriaController extends Controller
{
  

    /**
     * @var CheckCategoriesService
     */
    private $categoriesService;

    /**
     *  constructor.
     * @param CheckCategoriesService $categoriesService
     */
    public function __construct(CheckCategoriesService $categoriesService)
    {
        $this->categoriesService = $categoriesService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = Subcategoria::with('categoria')->orderBy('nombre', 'asc');

        if ($request->filter) {
            $like = '%'.$request->filter.'%';
            $query = $query->where('nombre','like',$like);
        }

        $subcategorias = $query->paginate(10);

        return response()->json($subcategorias);
    }

    public function store(Request $request){

        $this->validatorSubcategoria($request);
        $subcategoria = $this->create($request);

        return response(null, Response::HTTP_OK);
    }

    /**
     * @param $request
     */
    protected function validatorSubcategoria(Request $request)
    {
      return $this->validate($request, 
        [
            'nombre'=>'required|min:4|max:55',
            'categoria_id'=>'required|exists:categorias,id'
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
                    'nombre'=> $request->nombre,
                    'categoria_id' => $request->categoria_id
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
          $subcategoria= Subcategoria::where('id', $id)->with('categoria')->firstOrFail();

        if ($subcategoria) {
            return response()->json(['data' => $subcategoria], 200);
        } else {
            return response(null, Response::HTTP_INTERNAL_SERVER_ERROR);
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

        $subcategoria = Subcategoria::where('id', $id)->firstOrFail();
        $categoria_id = null;

        if($request->categoria_id != $subcategoria->categoria_id)
        {
            $categoria_id = $subcategoria->categoria_id;
        }

        $subcategoria->update($request->all());
        $table_name= "subcategoria";
        $accion = "update";
        Log::logs($id, $table_name, $accion , $subcategoria, 'Ha actualizado informacion de Categoria');
        if($categoria_id != null)
        {
            $this->categoriesService->checkCategories($categoria_id);
        }
        
        if($subcategoria->save()){
            return response(null, Response::HTTP_OK);
        } else {
            return response(null, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function searchSubcategoria(Request $request, $categoria)
    {
        $subcategorias = Subcategoria::where('categoria_id',$categoria)
                ->orderBy('nombre', 'asc')->get();


        return response()->json($subcategorias);
    }

}
