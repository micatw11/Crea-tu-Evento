<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use App\Publicacion;
use App\Proveedor;
use App\Favorito;
use App\Foto;
use App\Rol;

class PublicacionController extends Controller
{
    
    public function index(Request $request){
        $ids = [];
        if($request->has('favorite') && $request->favorite && Auth::user())
        {
            $user_id = $request->favorite;
            $ids = Favorito::where('user_id', Auth::id())->orderBy('created_at', 'ASC')->get()->pluck('publicacion_id');
            
        } else {
            $query = Publicacion::where('publicaciones.estado', 1 )

                ->join('proveedores', 'publicaciones.proveedor_id', '=', 'proveedores.id')

                ->join('rubros_detalle', 'rubros_detalle.proveedor_id', '=', 'proveedores.id')
                ->join('rubros', 'rubros_detalle.rubro_id', '=', 'rubros.id')

                ->join('domicilios', 'rubros_detalle.domicilio_id', '=', 'domicilios.id')
                ->join('localidades', 'domicilios.localidad_id', '=', 'localidades.id')

                ->join('subcategorias', 'publicaciones.subcategoria_id', '=', 'subcategorias.id')
                ->join('categorias', 'subcategorias.categoria_id', '=', 'categorias.id')

                ->select('publicaciones.*')
                ->where('publicaciones.estado', 1);
        


            if($request->has('filter') && $request->filter != ''){
                $like = '%'.$request->filter.'%';
                $query->where(function($query) use ($like){
                    $query->where('publicaciones.titulo','like', $like )
                        ->orWhere('publicaciones.descripcion', 'like', $like);
                    });
            }
            if($request->has('with_subcategory') && $request->with_subcategory != '')
            {
                $id = $request->with_subcategory;
                $query->where(function($query) use ($id){
                    $query->where('subcategorias.id', $id );
                });
            } 
            else 
            {
                if($request->has('with_category') && $request->with_category != '')
                {
                    $id = $request->with_category;
                    $query->where(function($query) use ($id){
                        $query->where('categorias.id', $id );
                    });
                }
            }
            if($request->has('with_localidad') && $request->with_localidad != ''){
                $id = $request->with_localidad;
                $query->where(function($query) use ($id){
                    $query->where('localidades.id', $id );
                });
            }
            $ids = $query->distinct('publicaciones.id')->get()->pluck('id');
            
        }

        $query = Publicacion::whereIn('publicaciones.id', $ids)
            ->with('proveedor.rubros_detalles.domicilio.localidad.provincia',
             'proveedor.rubros_detalles.rubro', 'subcategoria.categoria', 'fotos', 'caracteristicas', 'favoritos')


        ->select(
            '*', 
                DB::raw('(CASE WHEN publicaciones.oferta IS NULL THEN FALSE ELSE TRUE END) as tiene_oferta'),

                DB::raw('(SELECT CASE WHEN COUNT(caracteristica_publicacion.id) = 0 THEN FALSE ELSE TRUE END FROM caracteristica_publicacion WHERE caracteristica_publicacion.publicacion_id = publicaciones.id ) as tiene_caracteristicas')

                );
            $publicaciones = $query->orderBy('tiene_oferta', 'DESC')
                ->orderBy('tiene_caracteristicas', 'DESC')
                    ->paginate(10);

        return response()->json(['publicaciones' => $publicaciones, 'idses' => $ids], 200);

    }

    public function show(Request $request, $id){

        $publicacion = Publicacion::with('proveedor.rubros_detalles.rubro', 'proveedor.user.usuario','subcategoria.categoria','fotos', 'caracteristicas', 'favoritos')

                        ->where('id', $id)->firstOrFail();

        return response()->json(['publicacion' => $publicacion], 200);
    }

    public function store(Request $request){
    	$this->validatorPublicacion($request);

        $user = Auth::user();
        $proveedor = Proveedor::where('user_id', $user->id)->firstOrFail();

    	$publicacion = Publicacion::create([
            'titulo' => $request->titulo,
            'oferta' => $request->oferta,
            'descripcion' => $request->descripcion,
            'fecha_finalizacion' => $request->fecha_finalizacion,
            'subcategoria_id' => $request->subcategoria_id,
            'precio' => $request->precio,
            'proveedor_id' => $proveedor->id
        ]);

    	if($publicacion->save()){
            for ($i=0; $i < sizeof($request->fotos); $i++) { 
                $filename = $this->createFoto($request->fotos[$i]);
                $foto = new Foto;
                $foto->nombre = $filename;
                $foto->publicacion_id = $publicacion->id;
                $foto->save();
            }
            if($request->has('caracteristicas')&&$publicacion)
            {
                $publicacion->caracteristicas()->attach($request->caracteristicas);
            }
 
			return response(['id' => $publicacion->id, 'fecha' => $request->fecha_finalizacion], Response::HTTP_OK);
    	} else {
			return response(null, Response::HTTP_INTERNAL_SERVER_ERROR);
    	}

    }

    public function update(Request $request, $id){
        $this->validatorPublicacion($request);
        $ids = [];
        $idses = [];
        $photo_delete = null;

    	$publicacion = Publicacion::where('id', $id)->firstOrFail();

        if($publicacion->proveedor_id == Auth::user()->proveedor->id)

        {    	   
            $publicacion->update($request->except(['fotos']));
            if($publicacion->save())
            { 
                $ids = $request->fotos;

                $photo_delete=Foto::whereNotIn('id', $ids)->where('publicacion_id', $publicacion->id)->get();

                    
                foreach ($photo_delete as $foto) {
                    $file = "public/proveedores/publicaciones/{$foto->nombre}";
                    if(Storage::exists($file)) {
                        Storage::delete($file);
                    }
                }
                Foto::whereNotIn('id', $ids)->where('publicacion_id', $publicacion->id)->delete();

                for ($i=0; $i < sizeof($request->fotosUpdate); $i++) { 
                    $filename = $this->createFoto($request->fotosUpdate[$i]);
                    $foto = new Foto;
                    $foto->nombre = $filename;
                    $foto->publicacion_id = $publicacion->id;
                    $foto->save();
                }
                if($request->has('caracteristicas')&&$publicacion)
                {   
                    foreach ($request->caracteristicas as $key) {
                        $idses[] = $key['caracteristica_id'];
                        $publicacion->caracteristicas()->detach($key);
                    }
                    DB::table('caracteristica_publicacion')
                                    ->where('publicacion_id', $id)
                                   ->whereNotIn('caracteristica_id', $idses)->delete();
                    
                    $publicacion->caracteristicas()->attach($request->caracteristicas);

                }
    			return response(['id' => $publicacion->id] , Response::HTTP_OK);
        	}
        }

		return response(null, Response::HTTP_INTERNAL_SERVER_ERROR);
    }

    protected function validatorPublicacion(Request $request)
    {
	    return $this->validate($request, 
	        [
	        	'titulo' => 'required|min:5', 
	        	'descripcion' => 'required|min:20|max:30000',
                'subcategoria_id' => 'required|exists:subcategorias,id',
                'precio' => 'required'
	        ]);
    }

    protected function createFoto($img)
    {

        $extension = '';

        if(strstr($img, 'data:image/jpeg;base64,'))
        {
            $img = str_replace('data:image/jpeg;base64,', '', $img);
            $extension = 'jpeg';
        }
        else if(strstr($img, 'data:image/jpg;base64,'))
        {
            $img = str_replace('data:image/jpg;base64,', '', $img);
            $extension = 'jpg';
        }
        else 
        {
            $img = str_replace('data:image/png;base64,', '', $img);
            $extension = 'png';
        }

        $file = base64_decode($img);
        $filename  = str_random(30) . '.'.$extension;
        Storage::put('public/proveedores/publicaciones/'.$filename, $file);

        return $filename;
        
    }

  
    public function publicacionesProveedor(Request $request, $idProveedor){
        /*$publicacionesId = DB::table('publicaciones')
            ->join('rubros_detalle', 'rubros_detalle.id', '=', 'publicaciones.rubros_detalle_id')
            ->join('rubros', 'rubros.id', '=', 'rubros_detalle.rubro_id')
                ->select('publicaciones.id')
                ->where('rubros_detalle.proveedor_id', $idProveedor)
                ->groupby('publicaciones.id')->distinct()->get()->pluck('id');*/

        $query = Publicacion::with('proveedor.rubros_detalles.rubro', 'subcategoria.categoria', 'fotos', 'proveedor.rubros_detalles.domicilio.localidad.provincia', 'caracteristicas')
            ->where('proveedor_id', $idProveedor);

        if($request->has('with_estado') && ($request->with_estado == 0 || $request->with_estado == 1))
        {
            $query->where('estado', $request->with_estado);
        }

        $publicaciones = $query->orderBy('estado', 'desc')->orderBy('titulo', 'asc')->paginate(10);

        return response()->json(['publicaciones' => $publicaciones], 200);
    }

    public function destroy($id){
        if(Auth::user()->roles_id == Rol::roleId('Proveedor'))
        {
            $publicacion = Publicacion::where('id', $id)->firstOrFail();
            if($publicacion->proveedor_id == Auth::user()->proveedor->id)
            {
                if ($publicacion->estado == 1)
                    $publicacion->estado = 0;
                else
                    $publicacion->estado = 1;
                if($publicacion->save())
                    return response(null, Response::HTTP_OK);
                else
                    return response(null, Response::HTTP_INTERNAL_SERVER_ERROR);
            }
        }
        return response(null, Response::HTTP_UNAUTHORIZED);
    }

}