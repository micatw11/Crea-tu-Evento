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
        'titulo', 'oferta', 'descripcion', 'estado','fecha_finalizacion', 'rubros_detalle_id',  'created_at', 'updated_at'
    ];

    public function fotos()
    {
        return $this->hasMany('App\Foto');
    }

    public function rubros_detalle()
    {
        return $this->belongsTo('App\RubrosDetalle');
    }

    public function scopeOfProveedor($query, $proveedor_id){
        return $query->join('rubros_detalle', 'rubros_detalle.id','=','publicaciones.rubros_detalle_id')
                ->join('proveedores', 'proveedores.id','=','rubros_detalle.proveedor_id')
                ->where('proveedores.id', '=', $proveedor_id)
                ->select('publicaciones.*');
    }

    public function scopeDarBaja($query) {
        $query->update(['publicaciones.estado' => 0]);
    }

    public function scopeDarAlta($query) {
        $query->update(['publicaciones.estado' => 1]);
    }

    public function caracteristicas()
    {
        return $this->belongsToMany('App\Caracteristica')->withPivot('informacion');
    }

    public function caracteristicasCount()
    {
        return $this->belongsToMany('App\Caracteristica')->selectRaw('count(caracteristicas.id) as caracteristicas_count');
    }

       public function favoritos()
    {
        return $this->hasMany('App\Favorito');
    }

}
