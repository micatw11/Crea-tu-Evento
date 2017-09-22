<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Proveedor extends Model
{
    protected $table = 'proveedores';

    protected $fillable = [ 'user_id',
    						'cuit',
    						'nombre',  
    						'ingresos_brutos',
    						'email',
    						'domicilio_id',
    						'estado',
                            'dni'
    					  ];

    public function user()
    {     
        return $this->belongsTo('App\User');    
    }

    public function domicilio()
    {     
        return $this->belongsTo('App\Domicilio');    
    }

    public function rubros_detalles()
    {
        return $this->hasMany('App\RubrosDetalle');
    }

    public function publicaciones()
    {
        return $this->hasMany('App\Publicacion');
    }

    public function articulos(){
        return $this->hasMany('App\Articulo');
    }

}
