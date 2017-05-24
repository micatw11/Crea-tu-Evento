<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Telefono extends Model
{
    protected $table = 'telefonos';

    protected $fillable = [ 'cod_area', 
    						'numero',
    						'usuario_id'
    					  ];


    ///RELACIONES//
    public function usuario()
    {
        return $this->belongsTo('App\Usuario');
    }
}
