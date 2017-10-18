<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Reserva extends Model
{
    protected $table = 'reservas';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['publicacion_id', 'user_id', 'fecha', 'hora_inicio', 'hora_finalizacion', 'estado', 'precio_total'];

    public function publicacion(){
    	return $this->belongsTo('App\Publicacion');
    }

    public function user(){
    	return $this->belongsTo('App\User');
    }

    public function rubros(){
    	return $this->belongsToMany('App\Rubro');
    }

    public function articulos(){
    	return $this->belongsToMany('App\Articulo')->withPivot('cantidad');
    }
}
