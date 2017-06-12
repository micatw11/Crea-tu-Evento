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
use App\Usuario;
use App\User;


class UsuarioController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = User::with('usuario');

        if($request->filter){
            $like = '%'.$request->filter.'%';
            $usuario = Usuario::where('nombre', 'like', $like)
                            ->orWhere('apellido', 'like', $like)->get()->pluck('user_id');
                
            $query=$query->where('email', 'like', $like);

            if (!$usuario->isEmpty())
                $query=$query->orWhere('id',$usuario);
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
        $usuario = Usuario::where('user_id', $id)->firstOrFail();
        $usuario->update($request->all());
        if($usuario->save()){
            return response()->json(['data' =>  'OK'], 200);
        } else {
            return response()-json(['error' => 'Internal Server Error'], 500 );
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
        $user = User::where('id', $id)->firstOrFail();

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
        $user = User::where('id', $id)->firstOrFail();

        if($user->bloqueo()){
            return response()->json(['data' =>  'OK'], 200);
        } else {
            return response()->json(['error' =>  'Internal Server Error'], 500);
        }

    }

    public function updateAvatar(Request $request, $id){

        $usuario = Usuario::where('user_id', $id)->firstOrFail();

        //Se guarda el avatar en el almacenamiento 
        $filename = $this->saveAvatar($request);

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

        $this->validator($request);

        $user = User::where('id', $id)->firstOrFail();

        $validCredentials = Hash::check($request->oldPassword, $user->getAuthPassword());
        
        if($validCredentials){
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

        $user = User::where('id', $id)->firstOrFail();

        $user->roles_id = $request->roles_id;

        if($user->save()){
            return response()->json(['data' =>  'OK' ], 200);
        } else {
            return response()->json(['error' => 'Internal Server Error'], 500);
        }
    }


    protected function deleteAvatar(Usuario $usuario){
        $currentAvatar = $usuario->avatar;

        if($currentAvatar) {
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
}
