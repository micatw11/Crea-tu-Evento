<?php

namespace App;

use Illuminate\Database\Eloquent\Relations\Pivot;

class CaracteristicaRubro extends Pivot
{
       protected $table = 'caracteristica_rubro';

    protected $fillable = [ 
                            'rubro_id',
    						'caracteristica_id'
    					  ];

}
