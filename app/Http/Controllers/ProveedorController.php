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
        $query = Proveedor::with('user.usuario', 'domicilio');

        if($request->filter){
            $like = '%'.$request->filter.'%';
             $query = $query->where('nombre','like', $like )
                    ->orWhere('email', 'like', $like);
        }

        $proveedores = $query->paginate(10);
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
        $proveedor = $this->createProveedor($request,$domicilio);
        
        if ($proveedor && $domicilio ){
            $proveedor = Proveedor::where('id', $proveedor->id)->with('user.usuario')->first();
            $operador = User::where('id', Auth::id())->with('usuario')->first();
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
                'dni' => 'required'
            ]);
    }

    public function createProveedor(Request $request, $domicilio)
    {
        
        $filename= $this->storeImage($request);

        return Proveedor::create([
                'user_id' => $request->user_id,
                'nombre' => $request->nombre,
                'cuit' => $request->cuit,
                'ingresos_brutos' => $request->ingresos_brutos,
                'email' => $request->email,
                'estado' => "Tramite",
                'domicilio_id' => $domicilio->id,
                'dni' => $filename
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
        $filename= $this->storeImage($request);
        $proveedor = Proveedor::where('id', $id)->firstOrFail();
        $domicilio= Domicilio::where('id', $proveedor->domicilio_id)->firstOrFail();
        //Log::logs($id, $table_name, $accion , $rubro, 'Ha actualizado informacion personal');
        $this->domicilioService->updateDomicilio($request, $domicilio);
        $proveedor->update([
                'user_id' => $request->user_id,
                'nombre' => $request->nombre,
                'cuit' => $request->cuit,
                'ingresos_brutos' => $request->ingresos_brutos,
                'email' => $request->email,
                'domicilio_id' => $domicilio->id,
                'dni' => $filename
            ]);

        if($proveedor->save() && $domicilio->save()){
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

        if($request->action == 'Baja'){
            $proveedor->user->roles_id = Rol::roleId('Usuario');
            $publicaciones = Publicacion::where('proveedor_id', $proveedor->id)->update(['estado'=> 0]);

            
        } 
        else if ( $request->action == 'Aprobado' && $proveedor->user->roles_id == Rol::roleId('Usuario') ) 
        {
            $supervisor = User::where('id', Auth::id())->with('usuario')->first();
            $administradores = User::where('roles_id', Rol::roleId('Administrador'))->activo()->get();
            foreach ($administradores as $administrador) {
                Mail::to($administrador->email)->queue(new NewProveedorToSupervisor($supervisor, $proveedor));
            }
            $proveedor->user->roles_id = Rol::roleId('Proveedor');
        }
        else {
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
    public function proveedor($id){
        $proveedor = Proveedor::where('id', $id)
            ->with('domicilio.localidad.provincia','user.usuario')->firstOrFail();

        return response()->json(['data' => $proveedor], 200);
    }


    /**
     * @param $request
     */

    protected function storeImage(Request $request)
    {
        $img = $request->dni;
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
