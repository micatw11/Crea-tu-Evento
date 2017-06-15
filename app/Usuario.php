<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Usuario extends Model
{
    
    protected $table = 'usuarios';

    protected $fillable = [ 'avatar', 
    						'nombre', 
    						'apellido', 
    						'fecha_nac',
                            'sexo',
    						'user_id',
    						'localidad_id'
    					  ];


    ///RELACIONES//
	public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function localidad()
    {
        return $this->belongsTo('App\Localidad');
    }

    public function telefono()
    {
        return $this->hasMany('App\Telefono');
    }

          public function __toString(){
        return $this->avatar.', '.$this->nombre.', '.$this->apellido.', '.$this->fecha_nac.', '.$this->sexo.', '.$this->user_id.', '.$this->localidad_id;
    }
}
