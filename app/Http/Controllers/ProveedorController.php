<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Http\Services\DomicilioService;
use Illuminate\Support\Facades\Mail;
use App\Mail\NewProveedorToOperador;
use App\Mail\NewProveedorToSupervisor;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use App\Notificacion;
use App\Telefono;
use App\Proveedor;
use App\Publicacion;
use App\Rol;
use App\Domicilio;
use App\Prestacion;
use App\Rubro;
use App\Log;
use App\User;

class ProveedorController extends Controller
{

    /**
     * @var DomicilioService
     */
    private $domicilioService;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(DomicilioService $domicilioService)
    {
        $this->domicilioService = $domicilioService;
        $this->middleware('auth');
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = Proveedor::with('user.usuario', 'domicilio.localidad.provincia', 'telefono',
                        'register_by_user.usuario', 'accepted_by_user.usuario', 'rejected_by_user.usuario');

        if($request->filter){
            $like = '%'.$request->filter.'%';
             $query = $query->where('nombre','like', $like )
                    ->orWhere('email', 'like', $like);
        }

        if( $request->has('page') || $request->has('per_page') ) 
            $proveedores = $query->orderBy('created_at', 'DESC')->paginate(10);
        else
            $proveedores = $query->orderBy('created_at', 'DESC')->get();
        return response()->json($proveedores, 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {   
        $this->domicilioService->validateDomicilio($request);
        $this->validatorProveedor($request);

        $domicilio = $this->domicilioService->createDomicilio($request, 'Real');

        $pos = strpos($request->telefono, '-');
        $longitud = (strlen($request->telefono))-1;
        $cod_area = substr($request->telefono, 0,$pos);
        $numero = substr($request->telefono, $pos+1,$longitud);

        $telefono = Telefono::create(['cod_area' => $cod_area,'numero' => $numero]);
        $proveedor = $this->createProveedor($request,$domicilio, $telefono);

        
        if ($proveedor && $domicilio ){
            $proveedor = Proveedor::where('id', $proveedor->id)->with('user.usuario')->first();
            $operador = User::where('id', Auth::id())->with('usuario')->first();

            $log = Log::logs($proveedor->id, 'proveedores', 'create', null, 'Ha registrado un nuevo proveedor.');
            $for_role = Rol::roleId('Supervisor');
            Notificacion::create(
                [
                    'for_role_id' => $for_role, 
                    'log_id' => $log->id,
                    'by_user_id' => Auth::id(),
                    'descripcion' => "Se ha registrado un nuevo proveedor."
                ]);
            $supervisores = User::where('roles_id', Rol::roleId('Supervisor'))->activo()->get();
            foreach ($supervisores as $supervisor) {
                Mail::to($supervisor->email)->queue(new NewProveedorToOperador($operador, $proveedor));
            }
            return response(null, Response::HTTP_OK);
        
        } else {
            return response(null, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    protected function validatorProveedor(Request $request)
    {
        return $this->validate($request, 
            [
                'user_id' => 'required|exists:users,id',
                'nombre' => 'required|min:4|max:55',
                'cuit' => 'required|min:9|max:12',
                'ingresos_brutos' => 'required|min:5|max:10',
                'email' => 'required|email',
                'telefono' => 'required|regex:/[0-9]{3,4}-[0-9]{6,8}/i'
            ]);
    }

    public function createProveedor(Request $request, $domicilio, $telefono)
    {
        
        $filename=null;
        if($request->has('adjunto') && $request->adjunto != null)
            $filename= $this->storeImage($request);

        return Proveedor::create([
                'user_id' => $request->user_id,
                'nombre' => $request->nombre,
                'cuit' => $request->cuit,
                'ingresos_brutos' => $request->ingresos_brutos,
                'email' => $request->email,
                'estado' => "Tramite",
                'domicilio_id' => $domicilio->id,
                'adjunto' => $filename,
                'register_by_user_id' => Auth::id(),
                'telefono_id' => $telefono->id
            ]);
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
        
        $this->domicilioService->validateDomicilio($request);
        $this->validatorProveedor($request);
        $filename = null;
        if($request->has('adjunto') && $request->adjunto != null)
            $filename= $this->storeImage($request);
        $proveedor = Proveedor::where('id', $id)->firstOrFail();
        $domicilio= Domicilio::where('id', $proveedor->domicilio_id)->firstOrFail();
        $telefono = Telefono::where('id', $proveedor->telefono->id)->firstOrFail();
        //Log::logs($id, $table_name, $accion , $rubro, 'Ha actualizado informacion personal');
        $this->domicilioService->updateDomicilio($request, $domicilio);
        if(Auth::id() == $proveedor->user_id){
            $proveedor->update([
                'nombre' => $request->nombre,
                'cuit' => $request->cuit,
                'ingresos_brutos' => $request->ingresos_brutos,
                'email' => $request->email,
                'domicilio_id' => $domicilio->id
            ]);
        } else {
            $proveedor->update([
                    'user_id' => $request->user_id,
                    'nombre' => $request->nombre,
                    'cuit' => $request->cuit,
                    'ingresos_brutos' => $request->ingresos_brutos,
                    'email' => $request->email,
                    'domicilio_id' => $domicilio->id,
                    'adjunto' => $filename
                ]);
        }
        $pos = strpos($request->telefono, '-');
        $longitud = (strlen($request->telefono))-1;
        $cod_area = substr($request->telefono, 0,$pos);
        $numero = substr($request->telefono, $pos+1,$longitud);
        $telefono->update(['cod_area' => $cod_area,'numero' => $numero]);

        if($proveedor->save() && $domicilio->save() &&  $telefono->save()){
            return response(null, Response::HTTP_OK);
        } else {
            return response(null, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Update status.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function cambiarEstado(Request $request, $id){

        $proveedor = Proveedor::where('user_id', $id)->with('user.usuario')->firstOrFail();

        if($request->action == 'Baja' ){
            $proveedor->user->roles_id = Rol::roleId('Usuario');
            $publicaciones = Publicacion::where('proveedor_id', $proveedor->id)
                ->update(['estado'=> 0]);
            $proveedor->rejected_by_user_id = Auth::id();
            $proveedor->accepted_by_user_id = null;
            $log = Log::logs($proveedor->id, 'proveedores', 'baja', null, 'Ha dado de baja un proveedor.'); 
            $for_role = Rol::roleId('Administrador');
            Notificacion::create(
                [
                    'for_role_id' => $for_role, 
                    'log_id' => $log->id,
                    'by_user_id' => Auth::id(),
                    'descripcion' => "Se ha dado de baja un proveedor."
                ]);
        } 
        else if ( $request->action == 'Rechazado') {
            $proveedor->rejected_by_user_id = Auth::id();
            $proveedor->accepted_by_user_id = null;
            $log = Log::logs($proveedor->id, 'proveedores', 'rechazado', null, 'Ha rechazado un proveedor.'); 
            $for_role = Rol::roleId('Administrador');
            Notificacion::create(
                [
                    'for_role_id' => $for_role, 
                    'log_id' => $log->id,
                    'by_user_id' => Auth::id(),
                    'descripcion' => "Se ha rechazado un proveedor."
                ]);
        }
        else if ( $request->action == 'Aprobado' && $proveedor->user->roles_id == Rol::roleId('Usuario') ) 
        {
            $supervisor = User::where('id', Auth::id())->with('usuario')->first();
            $administradores = User::where('roles_id', Rol::roleId('Administrador'))->activo()->get();
            foreach ($administradores as $administrador) {
                Mail::to($administrador->email)->queue(new NewProveedorToSupervisor($supervisor, $proveedor));
            }
            $proveedor->user->roles_id = Rol::roleId('Proveedor');
            $proveedor->accepted_by_user_id = Auth::id();
            $proveedor->rejected_by_user_id = null;
            $log = Log::logs($proveedor->id, 'proveedores', 'aprobado', null, 'Ha acepatado un nuevo proveedor.');
            $for_role = Rol::roleId('Administrador');
            Notificacion::create(
                [
                    'for_role_id' => $for_role, 
                    'log_id' => $log->id,
                    'by_user_id' => Auth::id(),
                    'descripcion' => "Se ha acepatado un nuevo proveedor."
                ]);
        }
        else
        {
            return response()->json(['error' =>  'Bad Request'], 400);
        }

        $proveedor->estado = $request->input('action');
        $proveedor->observaciones = $request->input('observaciones');

        if($proveedor->user->save() && $proveedor->save()){
            return response(null, Response::HTTP_OK);
        } else {
            return response(null, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Show the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id){
        $proveedor = Proveedor::where('id', $id)
            ->with('domicilio.localidad.provincia','user.usuario', 'telefono')->firstOrFail();

        return response()->json(['data' => $proveedor], 200);
    }


    /**
     * @param $request
     */

    protected function storeImage(Request $request)
    {
        $img = $request->adjunto;
        $extension = null;

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
        else if(strstr($img, 'data:application/pdf;base64,'))
        {
            $img = str_replace('data:application/pdf;base64,', '', $img);
            $extension = 'pdf';
        }
        else 
        {
            $img = str_replace('data:image/png;base64,', '', $img);
            $extension = 'png';
        }

        $file = base64_decode($img);
        $filename  = str_random(30) . '.'.$extension;
        Storage::put('public/proveedores/'.$filename, $file);
        return $filename;
    }

    public function buscarRubro(Request $request, $proveedorId){
        $query = DB::table('prestaciones')
            ->select('id as value', 'denominacion as label')
            ->where('proveedor_id', $proveedorId);
        if($request->q){
            $like = '%'.$request->q.'%';
            $query->where('denominacion','like', $like )
                    ->orWhere('descripcion', 'like', $like);
        }
        $rubros = $query->get();
        return response()->json(['data' => $rubros], 200);
    }

}
