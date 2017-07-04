<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Rubro extends Model
{
    protected $table = 'rubros';

    protected $fillable = [ 'proveedor_id',
    						'tipo_rubro', 
    						'categoria_id',
    						'denominacion',
    						'descripcion',
    						'habilitacion',
                            'fecha_habilitacion',
    						'domicilio_id'
    					  ];
    					      ///RELACIONES//
    public function proveedor()
    {
        return $this->belongsTo('App\Proveedor');
    }

    public function domicilio()
    {     
        return $this->belongsTo('App\Domicilio');    
    }

    public function publicaciones()
    {
        return $this->belongsToMany('App\Publicacion');
    }

}