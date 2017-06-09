<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'roles_id', 'estado'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token'
    ];

    public function usuario()
    {
        return $this->HasOne('App\Usuario');
    }

    public function rol()
    {     
        return $this->belongsTo('App\Rol', 'roles_id', 'id');    
    }

    public function scopePassword($query, $password){
        return $query->where('password', $password);
    }

    public function scopeActivo($query){
        return $query->where('estado', true);
    }

    public function scopeBaja(){
        $this->estado = false;
        return $this->save();
    }

    public function scopeAlta(){
        $this->estado = true;
        return $this->save();
    }
}
