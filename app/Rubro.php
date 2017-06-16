<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Rubro extends Model
{
    protected $table = 'rubros';

    protected $fillable = [ 'proveedor_id', 
    						'categoria_id',
    						'denominacion',//descripcion de la actividad 
    						'codigo', 
    						'habilitacion',
                            'fecha_habilitacion',
    						'domicilio_id'
    					  ];
}
