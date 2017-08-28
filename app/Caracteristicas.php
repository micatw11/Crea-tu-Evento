<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Caracteristicas extends Model
{
       protected $table = 'caracteristicas';

    protected $fillable = [ 
                            'nombre',
    						'adicional'
    					  ];

    
    public function rubros_caracteristicas()
    {
        return $this->hasMany('App\RubrosCaracteristicas');
    }

    public function publicacion_caracteristicas()
    {
        return $this->hasMany('App\PublicacionCaracteristicas');
    }
}
