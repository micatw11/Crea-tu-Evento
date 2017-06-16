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
        return $this->hasOne('App\Usuario');
    }

    public function rol()
    {     
        return $this->belongsTo('App\Rol', 'roles_id', 'id');    
    }

    public function scopePassword($query, $password){
        return $query->where('password', $password);
    }

    public function scopeActivo($query){
        return $query->where('estado', 1);
    }

    public function scopeBaja(){
        $this->estado = 0;
        return $this->save();
    }

     public function scopeBloqueo($action){
        if($action === 2){
          $this->estado = 2;
          return $this->save();
        }else
          return false;

    }

      public function scopeAlta(){
          $this->estado = 1;
          return $this->save();
      }

      public function __toString(){
        return $this->estado.', '.$this->roles_id;
    }
}
