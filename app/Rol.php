<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Rol extends Model
{
    protected $table = 'roles';

    protected $fillable = [ 'nombre',
    						'descripcion'
    					  ];


    ///RELACIONES//
    public function user()
    {
        return $this->hashOne('App\User');
    }
}
