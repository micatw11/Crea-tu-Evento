<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Proveedor extends Model
{
    protected $table = 'proveedores';

    protected $fillable = [ 'user_id',
    						'cuit',
    						'nombre',  
    						'ingresos_brutos',
    						'email',
    						'domicilio_id',
    						'estado',
                            'adjunto',
                            'register_by_user_id',
                            'accepted_by_user_id',
                            'rejected_by_user_id',
                            'telefono_id'
    					  ];

    public function user()
    {
        return $this->belongsTo('App\User');    
    }

    public function telefono()
    {     
        return $this->belongsTo('App\Telefono');    
    }

    public function register_by_user()
    {     
        return $this->belongsTo('App\User');    
    }

    public function accepted_by_user()
    {     
        return $this->belongsTo('App\User');    
    }

    public function rejected_by_user()
    {     
        return $this->belongsTo('App\User');    
    }
    public function domicilio()
    {     
        return $this->belongsTo('App\Domicilio');    
    }

    public function prestaciones()
    {
        return $this->hasMany('App\Prestacion');
    }

    public function publicaciones()
    {
        return $this->hasMany('App\Publicacion');
    }

    public function articulos(){
        return $this->hasMany('App\Articulo');
    }

    public function scopeAprobado($query)
    {
        return $query->where('estado', 'Aprobado');
    }

}
