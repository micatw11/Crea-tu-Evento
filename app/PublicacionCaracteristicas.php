<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PublicacionCaracteristicas extends Model
{
       protected $table = 'categorias';

    protected $fillable = [ 
                            'publicacion_id',
    						'caracteristica_id',
    						'informacion'
    					  ];

    
    public function caracteristica()
    {
        return $this->belongsTo('App\Caracteristicas');
    }

    public function publicacion()
    {
        return $this->belongsTo('App\Publicacion');
    }
}
