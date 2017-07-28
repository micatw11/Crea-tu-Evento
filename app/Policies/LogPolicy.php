<?php

namespace App\Policies;

use App\User;
use App\Log;
use App\Rol;
use Illuminate\Auth\Access\HandlesAuthorization;

class LogPolicy
{
    use HandlesAuthorization;

    public function show(User $user, Log $log){
        return $user->id === $log->user_id
        || $user->roles_id === Rol::roleId('Administrador')
        || $user->roles_id === Rol::roleId('Supervisor');
    }

    public function activity(User $user, $id){
        return $user->id === $id
        || $user->roles_id === Rol::roleId('Administrador')
        || $user->roles_id === Rol::roleId('Supervisor');
    }

}
