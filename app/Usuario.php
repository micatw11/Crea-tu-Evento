<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Usuario extends Model
{
    
    protected $table = 'usuarios';

    protected $fillable = [ 'avatar', 
    						'nombre', 
    						'apellido', 
    						//'fecha_nac',
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

    
}
