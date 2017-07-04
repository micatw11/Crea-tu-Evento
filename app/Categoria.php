<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
    protected $table = 'categorias';

    protected $fillable = [ 'nombre',
    						'descripcion',
    						'observacion',
    						'estado'
    					  ];

        public function rubro()
    {
        return $this->hasMany('App\Rubro');
    }

}
