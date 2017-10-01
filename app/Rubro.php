<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Rubro extends Model
{
    protected $table = 'rubros';

    protected $fillable = [ 
                            'nombre',
                            'salon',
                            'servicio',
                            'producto'
    					  ];
    
    ///RELACIONES//

    public function rubros_detalles()
    {
        return $this->hasMany('App\RubrosDetalle');
    }

    public function caracteristicas()
    {
        return $this->belongsToMany('App\Caracteristica');
    }

    public function articulos(){
        return $this->hasMany('App\Articulo');
    }
   
}