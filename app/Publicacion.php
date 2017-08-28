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
        'titulo', 'oferta', 'descripcion', 'estado','rubros_detalle_id',  'created_at', 'updated_at'
    ];

    public function fotos()
    {
        return $this->hasMany('App\Foto');
    }

    public function rubros_detalle()
    {
        return $this->belongsTo('App\RubrosDetalle');
    }

     public function publicacion_caracteristicas()
    {
        return $this->hasMany('App\PublicacionCaracteristicas');
    }
}
