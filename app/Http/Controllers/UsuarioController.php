<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\UsuarioRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use Illuminate\Routing\Router;
use App\Usuario;
use App\User;
use App\Rol;
use App\Log;
use App\Proveedor;
use App\RubrosDetalle;
use App\Rubro;




class UsuarioController extends Controller
{  


    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {


        $queryUser = User::where(function($query){
                        $query->where('id', '!=', Auth::id())
                            ->where('roles_id', '!=', Rol::roleId('Proveedor'))
                            ->where('roles_id', '!=', Rol::roleId('Administrador'));
                        });

        if($request->filter){
            $like = '%'.$request->filter.'%';
            
            $usuario = Usuario::join('users', 'users.id', '=', 'usuarios.user_id')

                    ->where(function($query) use ($like){
                        $query->where('usuarios.nombre', 'like', $like)
                            ->orWhere('usuarios.apellido', 'like', $like)
                            ->orwhere('users.email', 'like', $like);
                        })
                    ->get()->pluck('user_id');

            $queryUser = $queryUser->whereIn('id', $usuario);
        }

        $users = $queryUser->with('usuario', 'rol')
                        ->paginate(10);
        return response()->json($users);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UsuarioRequest $request)
    {

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
       
        $usuario = Usuario::where('user_id', $id)
            ->with('localidad.provincia', 'user.rol', 'user.proveedor.rubrosDetalles.domicilio', 'user.proveedor.domicilio','user.proveedor.rubrosDetalles.publicaciones', 'user.proveedor.rubrosDetalles')
                ->firstOrFail();

        if (Gate::allows('show-profile', $usuario)) {
            return response()->json(['data' =>  $usuario], 200);
        } else {
            return response(null, Response::HTTP_FORBIDDEN);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UsuarioRequest $request, $id)
    {
        $table_name= "usuarios";
        $accion = "update";
        $usuario = Usuario::where('user_id', $id)->firstOrFail();
        Log::logs($id, $table_name, $accion , $usuario, 'Ha actualizado informacion personal');
        $usuario->update($request->all());
        if($usuario->save()){
            return response(null, Response::HTTP_OK);
        } else {
            return response(null, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $table_name= "users";
        $accion = "destroy";
        $user = User::where('id', $id)->firstOrFail();
        Log::logs($id, $table_name, $accion , $user, 'Ha desactivado la cuenta');
        if($user->baja()){
            if($request->logout){
                 Auth::logout();
            }
            return response(null, Response::HTTP_OK);
        } else {
            return response(null, Response::HTTP_INTERNAL_SERVER_ERROR);
        }

    }

    public function bloquear(Request $request, $id)
    {
        $user = User::where('id', $id)->firstOrFail();
        $table_name= "users";
        
        $action = $request->action;
        if( $action === 2){
            $accion= "lock";
            $descripcion = 'Ha bloqueado un usuario';
            $user->estado = 2;
        } else {
            $accion= "unlock";
            $descripcion = 'Ha desbloqueado un usuario';
            $user->estado = 1;
        }

        if($user->save()){
            Log::logs($id, $table_name, $accion , $user, $descripcion);
            return response(null, Response::HTTP_OK);
        } else {
            return response()->json(['request' => $request ], 500);
        }

    }

    public function updateAvatar(Request $request, $id){
        $table_name= "usuarios";
        $accion= "updateAvatar";
        $usuario = Usuario::where('user_id', $id)->firstOrFail();

        //Se guarda el avatar en el almacenamiento 
        $filename = $this->saveAvatar($request);
        Log::logs($id, $table_name, $accion, $usuario, 'Ha actualizado su foto de perfil');
        //Se elimina el anterior avatar del almacenamiento 
        $this->deleteAvatar($usuario);

        $usuario->avatar = $filename;

        if($usuario->save()){
            return response()->json(['data' => $filename ], 200);
        } else {
            return response(null, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function changePassword(Request $request, $id){
        $table_name= "users";
        $accion=  "updatePassword";
        $this->validator($request);

        $user = User::where('id', $id)->firstOrFail();

        $validCredentials = Hash::check($request->oldPassword, $user->getAuthPassword());
        
        if($validCredentials){
            Log::logs($id, $table_name, $accion, 'Ha cambiado su contraseña');
            $user->password =  Hash::make($request->password);
            $user->save();
            return response(null, Response::HTTP_OK);
        } else {
            $error = array('oldPassword' => 'La contraseña ingresada no coincide con nuestros registros.');
            return response()->json(['oldPassword' => $error], 403);
        }
    }

    public function cambiarRol(Request $request, $id){
        $table_name= "users";
        $accion= "updateRole";
        $user = User::where('id', $id)->firstOrFail();

        Log::logs($id, $table_name,  $accion , $user, 'ha cambiado el rol del usuario');

        $user->roles_id = $request->roles_id;

        if($user->save()){
            return response(null, Response::HTTP_OK);
        } else {
            return response(null, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }


    protected function deleteAvatar(Usuario $usuario){
        $currentAvatar = $usuario->avatar;

        if($currentAvatar && $currentAvatar !== 'default.png' && $currentAvatar !== 'default1.png' ) {
            $file = "public/avatars/{$currentAvatar}";

            if(Storage::exists($file)) {
                Storage::delete($file);
            }
        }
    }

    protected function saveAvatar($request){

        $img = $request->avatar;
        $img = str_replace('data:image/png;base64,', '', $img);
        $file = base64_decode($img);
        $filename  = str_random(30) . '.'.'jpg';
        Storage::put('public/avatars/'.$filename, $file);

        return $filename;
    }

    protected function validator(Request $request)
    {
      return $this->validate($request, 
        [
            'oldPassword' => 'required|min:6',
            'password' => 'required|min:6|confirmed'
        ]);
    }


    public function activity(Request $request, $id){

        if (Gate::allows('show-activity', $id)) {
            $query = DB::table('logs')
                                ->select('*', DB::raw('DATE_FORMAT(created_at, "%Y-%m-%d") as fecha'))
                                    ->where('user_id', $id);

            $actividades = $query->orderBy('created_at', 'desc')->get();

            return response()->json($actividades);

        } else {
            return response(null, Response::HTTP_FORBIDDEN);
        }
    }

    public function buscarUsuarios(Request $request)
    {
        $like = '%'.$request->q.'%';
        $proveedores = Proveedor::all()->pluck('user_id');
        $usuarios = DB::table('users')
            ->join('usuarios', 'usuarios.user_id', '=', 'users.id')
            ->select('users.id as value', DB::raw('CONCAT(usuarios.apellido, ", ",usuarios.nombre, " - ", users.email) as label'))
                //->whereNotIn('users.id', $proveedores)
                ->where([
                    ['users.roles_id', Rol::roleId('Usuario')],
                    ['users.id','!=', Auth::id()]
                ])
                ->where(function($query) use ($like){
                    $query->where('usuarios.nombre','like' ,$like)
                        ->orWhere('usuarios.apellido', 'like', $like)
                        ->orWhere('users.email', 'like', $like);
                })->orderBy('usuarios.nombre', 'asc')->get();


        return response()->json($usuarios);
    }

}
