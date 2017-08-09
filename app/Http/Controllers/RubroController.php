<?php

namespace App\Http\Controllers;

use App\Http\Services\CheckCategoriesService;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use App\Subcategoria;
use App\Categoria;
use App\Rubro;

class RubroController extends Controller
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
        $query = Rubro::with('subcategoria.categoria')->orderBy('nombre', 'asc');

        if ($request->filter) {
            $like = '%'.$request->filter.'%';
            $query = $query->where('nombre','like',$like);
        }

        $rubros = $query->paginate(10);

        return response()->json($rubros);
    }

    public function store(Request $request){

        //$this->validatorRubro($request);

        $categoria = null;
        $subcategoria = null;
        $newCategoria = false;
        $rubro = null;

        if($request->has('subcategoria_nombre'))
        {
            if($request->has('categoria_nombre'))
            {
                $newCategoria = true;
            }
            
            if($newCategoria)
            {
                $this->validate($request, [
                        'subcategoria_nombre' => 'required|unique:subcategorias,nombre|min:4|max:55',
                        'categoria_nombre' => 'required|unique:categorias,nombre|min:4|max:55',
                        'tipo_proveedor' => 'required',
                        'nombre' => 'required|unique:rubros,nombre|min:4|max:55'
                    ]);
                $categoria = $this->createCategoria($request);
                $subcategoria = $this->createSubcategoria($request, $categoria->id);
            }
            else
            {
                $this->validate($request, [
                        'subcategoria_nombre' => 'required|unique:subcategorias,nombre|min:4|max:55',
                        'categoria_id' => 'required|exists:categorias,id',
                        'nombre' => 'required|unique:rubros,nombre|min:4|max:55'
                    ]);
                $subcategoria = $this->createSubcategoria($request, $request->categoria_id);
            }

            $rubro = $this->createRubro($request, $subcategoria->id);
        }
        else 
        {
            $this->validate($request, [
                    'subcategoria_id'=> 'required|exists:subcategorias,id',
                    'nombre'=>'required|unique:rubros,nombre|min:4|max:55'
                ]);
            $rubro = $this->createRubro($request, $request->subcategoria_id);
        }
        
        if ($rubro)
        {
            return response(null, Response::HTTP_OK);
        } 
        else 
        {
            return response(null, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @param $request
     */
    protected function validatorRubro(Request $request)
    {
        return $this->validate($request, ['nombre'=>'required|unique:rubros,nombre|min:4|max:55']);
    }

    /**
    * @param  \Illuminate\Http\Request  $request
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    protected function createRubro(Request $request, $subcategoria_id)
    {
        return Rubro::create([
                    'nombre'=> $request->nombre,
                    'subcategoria_id' => $subcategoria_id
                ]);
    }

    /**
    * @param  \Illuminate\Http\Request  $request
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    protected function createSubcategoria(Request $request, $categoria_id)
    {
        return Subcategoria::create([
                    'nombre'=> $request->subcategoria_nombre,
                    'categoria_id' => $categoria_id
                ]);
    }

    /**
    * @param  \Illuminate\Http\Request  $request
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    protected function createCategoria(Request $request)
    {
        return Categoria::create([
                    'nombre'=> $request->categoria_nombre,
                    'tipo_proveedor' => $request->tipo_proveedor
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
        $rubros= Rubro::where('id', $id)->with('subcategoria.categoria')->firstOrFail();

        if ($rubros) {
            return response()->json(['data' => $rubros], 200);
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
        $this->validate($request, [
                'nombre'=>'required|min:4|max:55',
                'subcategoria_id'=>'required|exists:subcategorias,id'
                ]);

        $rubro = Rubro::where('id', $id)->firstOrFail();
        $subcategoria_id = null;

        if($request->subcategoria_id != $rubro->subcategoria_id)
        {
            $subcategoria_id = $rubro->subcategoria_id;
        }

        $rubro->update($request->all());

        if($subcategoria_id != null)
        {
            $this->categoriesService->checkSubcategories($subcategoria_id);
        }

        if($rubro->save())
        {
            return response(null, Response::HTTP_OK);
        } else {
            return response(null, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

      public function searchRubros(Request $request, $subcategoria)
    {
        $rubros = Rubro::where('subcategoria_id',$subcategoria)
                ->orderBy('nombre', 'asc')->get();


        return response()->json($rubros);
    }
}
