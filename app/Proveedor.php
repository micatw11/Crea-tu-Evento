<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Proveedor extends Model
{
   protected $table = 'proveedores';

    protected $fillable = [ 'user_id', 
    						'cuit',
    						'nombre', 
    						'habilitacion', 
    						'IIBB',
                            'persona',
    						'email',
    						'domicilio_id'
    					  ];
}
