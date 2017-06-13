<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
   	protected $table = 'localidades';
    
    protected $fillable = [ 
                            'user_id', 
                            'roles_id',
                            'accion',
                            'tabla',
                            'registro_id',
    						'valor_antiguo'
    					  ];
    					  

}
