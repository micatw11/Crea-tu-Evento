<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Mensaje extends Model
{
   	protected $table = 'mensajes';
    
    protected $fillable = [ 
                            'from_user_id', 
                            'to_user_id',
                            'ancestro_id',
                            'mensaje',
                            'estado',
                            'lectura'
    					  ];

    public function formUser(){
    	return $this->hasMany('App\User', 'from_user_id');
    }

    public function toUser(){
    	return $this->hasOne('App\User', 'to_user_id');
    }

    public function ancestros(){
    	return $this->hasMany('App\User', 'ancestro_id');
    }
}
