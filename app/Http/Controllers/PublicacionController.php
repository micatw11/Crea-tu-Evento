<?php

namespace App\Http\Controllers;

use App\Http\Services\PrestacionService;
use App\Http\Services\DomicilioService;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use App\Publicacion;
use App\Reserva;
use App\Prestacion;
use App\Domicilio;
use App\Proveedor;
use App\Favorito;
use App\Foto;
use App\Rol;

class PublicacionController extends Controller
{
    /**
     * @var PrestacionService
     * @var DomicilioService
     */
    private $prestacionService;
    private $domicilioService;

    /**
     * PublicacionController constructor.
     */
    public function __construct(PrestacionService $prestacionService, DomicilioService $domicilioService)
    {
        $this->prestacionService = $prestacionService;
        $this->domicilioService = $domicilioService;
    }

    public function index(Request $request){
        $ids = [];
        if($request->has('favorite') && $request->favorite && Auth::user())
        {
            $user_id = $request->favorite;
            $ids = Favorito::where('user_id', Auth::id())->orderBy('created_at', 'ASC')->get()->pluck('publicacion_id');
            
        } else {
            $query = Publicacion::where('publicaciones.estado', 1 )
                ->join('proveedores', 'publicaciones.proveedor_id', '=', 'proveedores.id')
                ->join('prestaciones', 'prestaciones.proveedor_id', '=', 'proveedores.id')
                ->join('domicilios', 'prestaciones.domicilio_id', '=', 'domicilios.id')
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
            ->with('calificaciones', 'prestacion.proveedor.domicilio.localidad.provincia', 'prestacion.domicilio.localidad.provincia',
             'prestacion.rubros', 'subcategoria.categoria', 'fotos', 'caracteristicas', 'favoritos', 'horarios')
            ->groupBy('publicaciones.id')


        ->select(
            'publicaciones.*',
                DB::raw('(CASE WHEN publicaciones.oferta IS NULL THEN FALSE ELSE TRUE END) as tiene_oferta'),

                DB::raw('(SELECT CASE WHEN COUNT(caracteristica_publicacion.id) = 0 THEN FALSE ELSE TRUE END FROM caracteristica_publicacion WHERE caracteristica_publicacion.publicacion_id = publicaciones.id ) as tiene_caracteristicas') );

            $publicaciones = $query->orderBy('tiene_oferta', 'DESC')
                ->orderBy('tiene_caracteristicas', 'DESC')
                    ->paginate(10);
            $this->setPromedio($publicaciones);

        return response()->json(['publicaciones' => $publicaciones, 'idses' => $ids], 200);
    }

    private function setPromedio($publicaciones){
        foreach ($publicaciones as $publicacion) {
            if(count($publicacion->calificaciones) > 0)
            {
                $count = 0;
                foreach ($publicacion->calificaciones as $calificacion) {
                    $count = $count + $calificacion->puntuacion_total;
                }
                $publicacion->calificacion = round($count / count($publicacion->calificaciones), 2);
            } else {
                $publicacion->calificacion = 0;
            }

        }
    }

    public function show(Request $request, $id){

        $publicacion = Publicacion::join('calificaciones', 'calificaciones.publicacion_id', '=', 'publicaciones.id')
            ->with('prestacion.rubros', 'prestacion.domicilio.localidad.provincia', 'proveedor.user.usuario','subcategoria.categoria','fotos', 'caracteristicas', 'favoritos', 'articulos','horarios', 'calificaciones.reserva.user.usuario')
            ->select('publicaciones.*',
                DB::raw('TRUNCATE(AVG(calificaciones.puntuacion_total), 1) as calificacion'))
                        ->where('publicaciones.id', $id)->firstOrFail();

        return response()->json(['publicacion' => $publicacion], 200);
    }

    protected function createPublicacion($request, $proveedor, $prestacion){
        return Publicacion::create([
                'titulo' => $request->titulo,
                'oferta' => $request->oferta,
                'descripcion' => $request->descripcion,
                'fecha_finalizacion' => $request->fecha_finalizacion,
                'subcategoria_id' => $request->subcategoria_id,
                'precio' => $request->precio,
                'prestacion_id' => $prestacion->id,
                'proveedor_id' => $proveedor->id
            ]);
    }

    public function store(Request $request){
    	$this->validatorPublicacion($request);

        $user = Auth::user();
        $proveedor = Proveedor::where('user_id', $user->id)->firstOrFail();

        $this->prestacionService->validatePrestacion($request);
        if($request->has('comercio') && $request->comercio){
            $this->domicilioService->validateDomicilio($request);
            $domicilio = $this->domicilioService->createDomicilio($request, 'Social');
        } else {
            $domicilio = Domicilio::where('id', $proveedor->domicilio_id)->first();
        }

        $prestacion = $this->prestacionService->createPrestacion($request, $proveedor, $domicilio);

        $prestacion->rubros()->attach($request->rubros_id); 
        

    	$publicacion = $this->createPublicacion($request, $proveedor, $prestacion);

        if($request->has('articulos') && sizeof($request->articulos) > 0){
            $publicacion->articulos()->attach($request->articulos);
        }

    	if($publicacion->save()){
            for ($i=0; $i < sizeof($request->fotos); $i++) { 
                $filename = $this->createFoto($request->fotos[$i]);
                $foto = new Foto;
                $foto->nombre = $filename;
                $foto->publicacion_id = $publicacion->id;
                $foto->save();
            }
            if ($request->has('horariosId')){
                $horarios = Horario::where('id', $request->horariosId)->get();
                foreach ($horarios as $key) {
                    $key->update([
                    'publicacion_id' => $publicacion->id]);
                    $key->save();
                }
            }
            if($request->has('caracteristicas')&&$publicacion)
            {
                $publicacion->caracteristicas()->attach($request->caracteristicas);
            }
 
			return response(['id' => $publicacion->id], Response::HTTP_OK);
    	} else {
			return response(null, Response::HTTP_INTERNAL_SERVER_ERROR);
    	}

    }

    public function update(Request $request, $id){
        $this->validatorPublicacion($request);
        if($request->has('comercio')){
            $this->domicilioService->validateDomicilio($request);
        }
        $ids = [];
        $idses = [];
        $photo_delete = null;

    	$publicacion = Publicacion::where('id', $id)->firstOrFail();

        if($publicacion->proveedor_id == Auth::user()->proveedor->id)
        {

            $prestacion = Prestacion::where('id', $publicacion->prestacion_id)->firstOrFail();

            $domicilio = Domicilio::where('id', $prestacion->domicilio_id)->first();
            if($domicilio && $request->has('comercio')){
                $this->domicilioService->updateDomicilio($request, $domicilio);
            }

            $prestacion->rubros()->detach(); 

            $this->prestacionService->updatePrestacion($request, $prestacion);

            $prestacion->rubros()->attach($request->rubros_id);

            $publicacion->update($request->except(['fotos']));

            $publicacion->articulos()->detach();
            if( $request->has('articulos') && sizeof($request->articulos) > 0){
                $publicacion->articulos()->attach($request->articulos);
            }
            
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

        $query = Publicacion::with('proveedor', 'prestacion.rubros', 'subcategoria.categoria', 'fotos', 'prestacion.domicilio.localidad.provincia', 'caracteristicas', 'calificaciones')
            ->where('proveedor_id', $idProveedor);

        if($request->has('with_estado') && ($request->with_estado == 0 || $request->with_estado == 1))
        {
            $query->where('estado', $request->with_estado);
        }

        $publicaciones = $query->orderBy('estado', 'desc')->orderBy('titulo', 'asc')->paginate(10);

        $this->setPromedio($publicaciones);
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

    public function reservas(Request $request, $id){
        $reservas = Reserva::where('publicacion_id', $id)->where('estado', 'confirmado')->with('publicacion.proveedor','user.usuario','rubros','articulos')->get();

        return response()->json($reservas, Response::HTTP_OK);
    }

}