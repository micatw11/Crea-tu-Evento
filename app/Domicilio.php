<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Domicilio extends Model
{
    protected $table = 'domicilios';

    protected $fillable = [ 'tipo_domicilio', 
    						'calle', 
    						'numero',
    						'piso',
    						'localidad_id'
    					  ];

    public function proveedor()
    {
        return $this->hasOne('App\Proveedor');
    }

      public function rubrosDetalle()
    {
        return $this->hasOne('App\RubrosDetalle');
    }

	public function localidad()
    {
        return $this->belongsTo('App\Localidad');
    }
}
