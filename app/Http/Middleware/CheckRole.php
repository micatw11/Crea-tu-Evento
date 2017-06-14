<?php

namespace App\Http\Middleware;
use Illuminate\Http\Response;
use App\Rol;

use Closure;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $roles = array_slice(func_get_args(), 2);
        $idRole = null;
        foreach ($roles as $role) {

            $idRole = Rol::roleId($role);
            $bool =  ($request->user()->roles_id == $idRole) ? true : false;
            if($bool) return $next($request);
        }

        return response()->json(['error' => 'Forbidden'], 403);

    }
}
