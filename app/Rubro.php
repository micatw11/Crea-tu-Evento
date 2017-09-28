<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Rubro extends Model
{
    protected $table = 'rubros';

    protected $fillable = [ 
                            'nombre',
                            'salon',
                            'servicio',
                            'producto'
    					  ];
    
    ///RELACIONES//

    public function prestaciones()
    {
        return $this->hasMany('App\Prestacion');
    }

    public function caracteristicas()
    {
        return $this->belongsToMany('App\Caracteristica');
    }
   
}