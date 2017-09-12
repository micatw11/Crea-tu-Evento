<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use App\Publicacion;
use App\Foto;
use App\Rol;

class PublicacionController extends Controller
{
    
    public function index(Request $request){
        $query = Publicacion::where('publicaciones.estado', 1 )
                            ->join('rubros_detalle', 'rubros_detalle.id', '=', 'publicaciones.rubros_detalle_id')
                            ->join('proveedores', 'rubros_detalle.proveedor_id', '=', 'proveedores.id')
                            ->join('rubros', 'rubros_detalle.rubro_id', '=', 'rubros.id')
                            ->join('subcategorias', 'rubros.subcategoria_id', '=', 'subcategorias.id')
                            ->join('categorias', 'subcategorias.categoria_id', '=', 'categorias.id')
                            ->join('domicilios', 'rubros_detalle.domicilio_id', '=', 'domicilios.id')
                            ->join('localidades', 'domicilios.localidad_id', '=', 'localidades.id')
                            ->join('fotos', 'publicaciones.id', '=', 'fotos.publicacion_id')
                            ->select('publicaciones.*')
                            ->where('publicaciones.estado', 1);
                           
        if($request->has('filter') && $request->filter != ''){
            $like = '%'.$request->filter.'%';

            $query->where(function($query) use ($like){
                $query->where('publicaciones.titulo','like', $like )
                    ->orWhere('publicaciones.descripcion', 'like', $like);
                });
        }


        if($request->has('with_denomination') && $request->with_denomination != '')
        {
            $id = $request->with_denomination;
            $query->where(function($query) use ($id){
                $query->where('rubros.id', $id );
            });
        } 
        else 
        {
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
        }

        if($request->has('with_localidad') && $request->with_localidad != ''){
            $id = $request->with_localidad;
            $query->where(function($query) use ($id){
                $query->where('localidades.id', $id );
            });
        }
        $ids = $query->distinct('publicaciones.id')->get()->pluck('id');

        $query = 
            Publicacion::whereIn('publicaciones.id', $ids)
        
        ->with('rubros_detalle.proveedor', 'rubros_detalle.rubro.subcategoria.categoria', 'rubros_detalle.domicilio.localidad.provincia', 'fotos', 'caracteristicas')

        ->select(
            '*', 
                DB::raw('(CASE WHEN publicaciones.oferta IS NULL OR length(oferta) = 0 THEN FALSE ELSE TRUE END) as tiene_oferta'),

                DB::raw('(SELECT CASE WHEN COUNT(caracteristica_publicacion.id) = 0 THEN FALSE ELSE TRUE END FROM caracteristica_publicacion WHERE caracteristica_publicacion.publicacion_id = publicaciones.id ) as tiene_caracteristicas')

                );
            $publicaciones = $query->orderBy('tiene_oferta', 'DESC')
                ->orderBy('tiene_caracteristicas', 'DESC')
                    ->paginate(10);

        return response()->json(['publicaciones' => $publicaciones], 200);

    }

    public function show(Request $request, $id){

        $publicacion = Publicacion::with('rubros_detalle.proveedor.user.usuario','rubros_detalle.rubro.subcategoria.categoria','fotos', 'caracteristicas')
                        ->where('id', $id)->firstOrFail();

        return response()->json(['publicacion' => $publicacion], 200);
    }

    public function store(Request $request){
    	$this->validatorPublicacion($request);
    	$publicacion = Publicacion::create([
            'titulo' => $request->titulo,
            'oferta' => $request->oferta,
            'descripcion' => $request->descripcion,
            'fecha_finalizacion' => $request->fecha_finalizacion,
            'rubros_detalle_id' => $request->rubros_detalle_id
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
    	$publicacion = Publicacion::with('rubros_detalle')->where('id', $id)->firstOrFail();
        if($publicacion->rubros_detalle->proveedor_id == Auth::user()->proveedor->id)
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
	        	'rubros_detalle_id' => 'required|exists:rubros_detalle,id'
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
        $publicacionesId = DB::table('publicaciones')
            ->join('rubros_detalle', 'rubros_detalle.id', '=', 'publicaciones.rubros_detalle_id')
            ->join('rubros', 'rubros.id', '=', 'rubros_detalle.rubro_id')
                ->select('publicaciones.id')
                ->where('rubros_detalle.proveedor_id', $idProveedor)
                ->groupby('publicaciones.id')->distinct()->get()->pluck('id');

        $query = Publicacion::with('rubros_detalle.rubro.subcategoria.categoria', 'fotos', 'rubros_detalle.proveedor', 'rubros_detalle.domicilio.localidad.provincia', 'caracteristicas')->whereIn('id', $publicacionesId);

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
            $publicacion = Publicacion::with('rubros_detalle')->where('id', $id)->firstOrFail();
            if($publicacion->rubros_detalle->proveedor_id == Auth::user()->proveedor->id)
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
        return response(null, Response::HTTP_UNAUTHORIZED);;
    }

}