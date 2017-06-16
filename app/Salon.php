<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Salon extends Model
{
    protected $table = 'salones';

    protected $fillable = [ 'proveedor_id', 
    						'denominacion',//descripcion de la actividad 
    						'codigo', 
    						'habilitacion',
                            'fecha_habilitacion',
    						'domicilio_id'
    					  ];
}
