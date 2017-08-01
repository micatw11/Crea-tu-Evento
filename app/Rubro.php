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
    public function rubrosDetalles(){
        return $this->hasMany('App\RubrosDetalle');
    }

}