<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use App\User;
use Socialite;
use App\SocialAccountService;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $this->validateLogin($request);

        if (Auth::attempt(['email' => $request->email, 'password' => $request->password], $request->remember)) {
            // Authentication passed...
            $user = Auth::user();
            if ($user->estado!=2){
                if(!$user->estado) $user->alta();
                if ($user->usuario->localidad_id!= null){ 
                    $user->usuario->localidad->provincia;
                }else{
                    $user->usuario;
                }
                return response()->json(['data' =>  $user, 'csrfToken' => csrf_token()], 200);
            } else {
                    Auth::logout();
                    return response()->json([
                        'message' => 'La cuenta se ecuentra temporalmente bloqueada.',
                    ], 403);
            }
        } else {
            return response(null, Response::HTTP_UNAUTHORIZED);
        }
    }

    public function logout(Request $request)
    {
      if(!Auth::guest()){
            Auth::logout();
            return response()->json(['data' => 'OK'], 200);
        } else {
            return response(null, Response::HTTP_UNAUTHORIZED);
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
            if ($user->usuario->localidad_id!= null){ 
                    $user->usuario->localidad->provincia;
                }else{
                    $user->usuario;
                }
            return response()->json(['data' =>  $user, 'csrfToken' => csrf_token()]);
        } else {
            return response(null, Response::HTTP_UNAUTHORIZED);
        }
    }
    /**
     * Redirect the user to the google authentication page.
     *
     * @return Response
     */
    public function redirect()
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Obtain the user information from Google.
     *
     * @return Response
     */

    public function callback(SocialAccountService $service){

      $user=$service->createOrGetUser(Socialite::driver('google')->user());
      //return responsejson()
      if ($user){
        auth()->login($user);
    }
      return redirect()->to('/');
    }

}