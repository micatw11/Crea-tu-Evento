10.
<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Articulo extends Model
{
    protected $table = 'articulos';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'proveedor_id', 'stock', 'nombre', 'precio', 'tipo', 'estado'
    ];

    public function proveedor()
    {
        return $this->belongsTo('App\Proveedor');
    }

    public function publicaciones()
    {
        return $this->belongsToMany('App\Publicacion');
    }
}
