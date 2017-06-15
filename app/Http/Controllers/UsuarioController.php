<?php

namespace App\Http\Controllers;

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
use App\Log;



class UsuarioController extends Controller
{  
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = User::where('id', '!=', Auth::user()->id)->with('usuario');

        if($request->filter){
            $like = '%'.$request->filter.'%';
            $usuario = Usuario::where('nombre', 'like', $like)
                            ->orWhere('apellido', 'like', $like)->get()->pluck('user_id');
                
            $query=$query->where('email', 'like', $like);

            if (!$usuario->isEmpty())
                $query=$query->orWhereIn('id',$usuario);
        }

        $users = $query->paginate(10);

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
        $usuario = Usuario::where('user_id', $id)->with('localidad.provincia')->firstOrFail();
        return response()->json(['data' =>  $usuario]);
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
        $table_name= "usuario";
        $accion = "update";
        $usuario = Usuario::where('user_id', $id)->firstOrFail();
        Log::logs($id, $table_name, $accion , $usuario);
        $usuario->update($request->all());
        if($usuario->save()){
            return response()->json(['data' =>  'OK'], 200);
        } else {
            return response()->json(['error' => 'Internal Server Error'], 500 );
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
        Log::logs($id, $table_name, $accion , $user);
        if($user->baja()){
            if($request->logout){
                 Auth::logout();
            }
            return response()->json(['data' =>  'OK'], 200);
        } else {
            return response()->json(['error' =>  'Internal Server Error'], 500);
        }

    }

    public function bloquear(Request $request, $id)
    {
        $table_name= "users";
        $accion= "bloquear";
        $user = User::where('id', $id)->firstOrFail();

        if($user->bloqueo()){
            Log::logs($id, $table_name, $accion , $user);
            return response()->json(['data' =>  'OK'], 200);
        } else {
            return response()->json(['error' =>  'Internal Server Error' , 'request' => $request ], 500);
        }

    }

    public function updateAvatar(Request $request, $id){
        $table_name= "usuarios";
        $accion= "updateAvatar";
        $usuario = Usuario::where('user_id', $id)->firstOrFail();

        //Se guarda el avatar en el almacenamiento 
        $filename = $this->saveAvatar($request);
        Log::logs($id, $table_name, $accion, $usuario);
        //Se elimina el anterior avatar del almacenamiento 
        $this->deleteAvatar($usuario);

        $usuario->avatar = $filename;

        if($usuario->save()){
            return response()->json(['data' => $filename ], 200);
        } else {
            return response()->json(['error' => 'Internal Server Error'], 500);
        }
    }

    public function changePassword(Request $request, $id){
        $table_name= "users";
        $accion=  "changePassword";
        $this->validator($request);

        $user = User::where('id', $id)->firstOrFail();

        $validCredentials = Hash::check($request->oldPassword, $user->getAuthPassword());
        
        if($validCredentials){
            Log::logs($id, $table_name, $accion );
            $user->password =  Hash::make($request->password);
            $user->save();
            return response()->json(['data' =>  'OK'], 200);
        } else {
            $error = array(
                'oldPassword' => 'La contraseña ingresada no coincide con nuestros registros.');
            return response()->json(['error' =>  'Unauthorized', 'oldPassword'=> $error], 401);
        }
    }

    public function cambiarRol(Request $request, $id){
        $table_name= "users";
        $accion= "cambiarRol";
        $user = User::where('id', $id)->firstOrFail();
        Log::logs($id, $table_name,  $accion , $user);
        $user->roles_id = $request->roles_id;

        if($user->save()){
            return response()->json(['data' =>  'OK' ], 200);
        } else {
            return response()->json(['error' => 'Internal Server Error'], 500);
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

        $actividades = Log::where('user_id', $id)
                            ->where('tabla', 'usuario')->get();
        if ($actividades){
            return response()->json($actividades);
        
        }else{
            return response()->json(['error' => 'No hay datos']);
        }
    }

}
