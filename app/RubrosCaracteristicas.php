<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RubrosCaracteristicas extends Model
{
       protected $table = 'categorias';

    protected $fillable = [ 
                            'rubro_id',
    						'caracteristica_id'
    					  ];

    
    public function caracteristica()
    {
        return $this->belongsTo('App\Caracteristicas');
    }

    public function rubro()
    {
        return $this->belongsTo('App\Rubro');
    }
}
