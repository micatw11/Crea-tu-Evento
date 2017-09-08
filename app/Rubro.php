<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Rubro extends Model
{
    protected $table = 'rubros';

    protected $fillable = [ 
                            'nombre',
    						'subcategoria_id',
    					  ];
    
    ///RELACIONES//
    public function subcategoria()
    {
        return $this->belongsTo('App\Subcategoria');
    }

    public function rubros_detalles()
    {
        return $this->hasMany('App\RubrosDetalle');
    }

    public function caracteristicas()
    {
        return $this->belongsToMany('App\Caracteristica');
    }
   
}