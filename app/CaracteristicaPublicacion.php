<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CaracteristicasPublicacion extends Model
{
       protected $table = 'caracteristica_publicacion';

    protected $fillable = [ 
                            'publicacion_id',
    						'caracteristica_id',
    						'informacion'
    					  ];

}
