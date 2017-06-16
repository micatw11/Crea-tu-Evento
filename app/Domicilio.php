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
    						'codigo_postal',
    						'localidad_id'
    					  ];
}
