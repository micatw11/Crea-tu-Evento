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
        $query = Publicacion::with('rubros_detalle.proveedor', 'rubros_detalle.rubro.subcategoria.categoria');

        if($request->filter){
            $like = '%'.$request->filter.'%';
            $query->where('titulo','like', $like )
                    ->orWhere('descripcion', 'like', $like);
        }

        $publicaciones = $query->paginate(10);
        return response()->json(['publicaciones' => $publicaciones], 200);
    }

    public function show(Request $request, $id){
        $publicacion = Publicacion::with('rubros_detalle.proveedor.user.usuario','rubros_detalle.rubro.subcategoria.categoria','fotos')
                        ->where('id', $id)
                        ->where('estado', 1)->firstOrFail();

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
 
			return response(['id' => $publicacion->id], Response::HTTP_OK);
    	} else {
			return response(null, Response::HTTP_INTERNAL_SERVER_ERROR);
    	}

    }

    public function update(Request $request, $id){

        $this->validatorPublicacion($request);
        $ids = [];
    	$publicacion = Publicacion::where('id', $id)->firstOrFail();

    	$publicacion->update($request->except(['fotos']));

        if($request->has('fotos')){
            for ($i=0; $i < sizeof($publicacion->fotos); $i++) { 
                array_push($ids, $publicacion->fotos[$i]->id);
            }

            $photo_delete=Foto::whereNotIn('id', $ids)->where('publicacion_id', $publicacion->id)->get();

            foreach ($photo_delete as $fotos) {
                $file = "public/avatars/{$fotos->nombre}";
                if(Storage::exists($file)) {
                    Storage::delete($file);
                }
            }
            Foto::whereNotIn('id', $ids)->where('publicacion_id', $publicacion->id)->delete();
        }
        if($request->has('fotosUpdate')){
            for ($i=0; $i < sizeof($request->fotosUpdate); $i++) { 
                $filename = $this->createFoto($request->fotosUpdate[$i]);
                $foto = new Foto;
                $foto->nombre = $filename;
                $foto->publicacion_id = $publicacion->id;
                $foto->save();
            }
        }

    	if($publicacion->save()){
			return response(['id' => $publicacion->id] , Response::HTTP_OK);
    	} else {
			return response(null, Response::HTTP_INTERNAL_SERVER_ERROR);
    	}

    }

    protected function validatorPublicacion(Request $request)
    {
	    return $this->validate($request, 
	        [
	        	'titulo' => 'required|min:5', 
	        	'descripcion' => 'required|min:15',
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
                ->where('publicaciones.estado', 1)
                ->groupby('publicaciones.id')->distinct()->get()->pluck('id');
        $publicaciones = Publicacion::with('rubros_detalle.rubro.subcategoria.categoria', 'fotos')->whereIn('id', $publicacionesId)->get();
        return response()->json(['publicaciones' => $publicaciones], 200);
    }

    public function destroy($id){
        if(Auth::user()->roles_id == Rol::roleId('Proveedor'))
        {
            $publicacion = Publicacion::with('rubros_detalle')->where('id', $id)->where('estado', 1)->firstOrFail();
            if($publicacion->rubros_detalle->proveedor_id == Auth::user()->proveedor->id)
            {
                $publicacion->estado = 0;
                if($publicacion->save())
                    return response(null, Response::HTTP_OK);
                else
                    return response(null, Response::HTTP_INTERNAL_SERVER_ERROR);
            }
        }
        return response(null, Response::HTTP_UNAUTHORIZED);;
    }
    
}