<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $this->validateLogin($request);

        if (Auth::attempt(['email' => $request->email, 'password' => $request->password], $request->remember)) {
            // Authentication passed...
            $user = Auth::user();
            $user->usuario->localidad->provincia;
            return response()->json(['data' =>  $user, 'csrfToken', csrf_token()]);
        } else {
            return response()->json([
                'error' => 'Unauthorized',
            ], 401);
        }
    }

    public function logout(Request $request)
    {
      if(!Auth::guest()){
            Auth::logout();
            return response()->json(['data' => 'OK'], 200);
        } else {
            return response()->json([
                'error' => 'Unauthorized',
            ], 401);
        }
        
    }


    protected function validateLogin(Request $request)
    {
        return $this->validate($request, 
            [  'email' => 'required',
                'password' => 'required'
            ]);
    }

    public function getAuth(Request $request){
        if(!Auth::guest()){
            $user = Auth::user();
            $user->usuario->localidad->provincia;
            return response()->json(['data' =>  $user, 'csrfToken', csrf_token()]);
        } else {
            return response()->json([
                'error' => 'Unauthorized',
            ], 401);
        }
    }

}