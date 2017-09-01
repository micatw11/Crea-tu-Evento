<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Caracteristica extends Model
{
       protected $table = 'caracteristicas';

    protected $fillable = [ 
                            'nombre',
    						'adicional'
    					  ];

    
    public function rubros()
    {
        return $this->belongsToMany('App\Rubro');
    }

    public function publicacion_caracteristicas()
    {
        return $this->belongsToMany('App\PublicacionCaracteristicas');
    }
}
