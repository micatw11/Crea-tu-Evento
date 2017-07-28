<?php

namespace App\Policies;

use App\User;
use App\Usuario;
use App\Rol;
use Illuminate\Auth\Access\HandlesAuthorization;

class UsuarioPolicy
{
    use HandlesAuthorization;

    public function show(User $user, Usuario $usuario)
    {
        return $user->id === $usuario->user_id 
            || $usuario->user->roles_id === Rol::roleId('Proveedor') 
            || $user->roles_id === Rol::roleId('Administrador')
            || $user->roles_id === Rol::roleId('Supervisor');
    }
}
