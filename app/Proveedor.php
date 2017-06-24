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
    						'ingresos_brutos',
                            'persona',
    						'email',
    						'domicilio_id',
    						'estado'
    					  ];

    public function user()
    {     
        return $this->belongsTo('App\User');    
    }

    public function domicilio()
    {     
        return $this->belongsTo('App\Domicilio');    
    }
}