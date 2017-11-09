<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Calificacion extends Model
{
	 protected $table = 'articulos';

    protected $fillable = [
        'publicacion_id', 'reserva_id', 'calidad', 'calidad_precio', 'profesionalidad', 'respuesta', 'recomendar', 'comentario', 'puntuacion_total', 'created_at', 'updated_at'
    ];
}
