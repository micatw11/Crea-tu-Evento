<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Publicacion extends Model
{
	protected $table = 'publicaciones';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'titulo', 'oferta', 'descripcion', 'estado', 'created_at', 'updated_at'
    ];

    public function fotos()
    {
        return $this->hasMany('App\Foto');
    }

    public function rubros()
    {
        return $this->belongsToMany('App\Rubro');
    }

    public function updateRubros($rubros){
        $this->rubros()->detach();
        return $this->rubros()->attach($rubros);
    }
}
