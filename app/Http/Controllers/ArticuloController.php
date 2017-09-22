<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use App\Articulo;
use App\Proveedor;
use App\Rol;

class ArticuloController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        if($user->roles_id == Rol::roleId('proveedor')){
            $proveedor = Proveedor::where('user_id', $user->id)->firstOrFail();
            $articulos = Articulo::where('proveedor_id', $proveedor->id)->paginate(10);

            return response()->json($articulos, 200);

        }        
        return response(null, Response::HTTP_UNAUTHORIZED);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validateArticulo($request);

        $user = Auth::user();
        if($user->roles_id == Rol::roleId('proveedor')){
            $proveedor = Proveedor::where('user_id', $user->id)->firstOrFail();

            $articulo = Articulo::create([
                'proveedor_id' => $proveedor->id,
                'nombre' => $request->nombre,
                'stock' => $request->cantidad,
                'tipo' => $request->tipo,
                'precio' => $request->precio,
                'estado' => 1
            ]);

            if($article->save())
            {
                return response(['id' => $article->id], Response::HTTP_OK);
            }
            else
            {
                return response(null, Response::HTTP_INTERNAL_SERVER_ERROR);
            } 
        }

        return response(null, Response::HTTP_UNAUTHORIZED);
    }

    protected function validateArticulo(Request $request)
    {
        return $this->validate($request, 
            [
                'tipo' => 'required|in:producto,servicio,salon', 
                'stock' => 'numeric|nullable', 
                'nombre' => 'required|min:3|max:25',
                'precio' => 'nullable'
            ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        $articulo = Articulo::with('publicaciones')->where('id', $id)->where('estado', 1)->firstOrFail();

        return response()->json($articulo, Response::HTTP_OK);
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
        $this->validateArticulo($request);
        $user = Auth::user();
        if($user->roles_id == Rol::roleId('proveedor')){
            $proveedor = Proveedor::where('user_id', $user->id)->firstOrFail();

            $articulo = Article::where('id', $id)->where('proveedor_id', $proveedor->id)->firstOrFail();

            $articulo->update($request);
            
            if($articulo->save())
            {
                return response()->json($articulo, Response::HTTP_OK);
            }
            else
            {
                return response(null, Response::HTTP_INTERNAL_SERVER_ERROR);
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
    public function destroy(Request $request, $id)
    {
        //
    }
}
