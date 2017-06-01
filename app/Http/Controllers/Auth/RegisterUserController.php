<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Usuario;
use App\Rol;
use App\Http\Requests\UsuarioRequest;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\Events\Registered;
use App\Http\Controllers\UsuarioController;

class RegisterUserController extends Controller
{
    protected function validator(Request $request)
    {
      return $this->validate($request, 
        [
            'name' => 'required|min:4|max:55',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|min:6|confirmed'
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    protected function create(Request $request)
    {
        return User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'roles_id' => Rol::usuario()]);
    }

    /**
     * Handle a registration request for the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        $this->validator($request);

        event(new Registered($user = $this->create($request)));

        $this->guard()->login($user);

        $this->createUsuario($request);
        $user->usuario->localidad->provincia;
        $user->rol;
        return response()->json(['data' =>  $user, 'csrfToken', csrf_token()]);
    }


    /**
     * Get the guard to be used during registration.
     *
     * @return \Illuminate\Contracts\Auth\StatefulGuard
     */
    protected function guard()
    {
        return Auth::guard();
    }

    public function createUsuario(Request $request)
    {
        return Usuario::create([
            'nombre' => $request->nombre,
            'apellido' => $request->apellido,
            'sexo' => $request->sexo,
            'localidad_id' => $request->localidad_id,
            'fecha_nac' => $request->fecha_nac,
            'user_id' => Auth::user()->id,
            'avatar' => 'storage/avatars/default.png']);        
    }
}
