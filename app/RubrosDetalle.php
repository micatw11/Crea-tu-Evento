<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RubrosDetalle extends Model
{
    protected $table = 'rubros_detalle';

    protected $fillable = [ 
    						'proveedor_id',
    						'rubro_id',
    						'habilitacion',
                            'fecha_habilitacion',
    						'domicilio_id'
    					  ];

    public function proveedor()
    {
        return $this->belongsTo('App\Proveedor');
    }
    
    public function domicilio()
    {     
        return $this->belongsTo('App\Domicilio');    
    }

    public function rubro()
    {
        return $this->belongsTo('App\Rubro');
    }

    public function publicaciones()
    {
        return $this->hasMany('App\Publicacion');
    }

    public function articulos()
    {
        return $this->hasMany('App\Articulo');
    }
}
