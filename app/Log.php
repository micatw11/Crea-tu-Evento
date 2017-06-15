<?php

namespace App;
use App\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;

class Log extends Model
{
   	protected $table = 'logs';
    
    protected $fillable = [ 
                            'user_id', 
                            'roles_id',
                            'accion',
                            'tabla',
                            'registro_id',
    						'valor_antiguo'
    					  ];


    static public function logs($id, $table_name, $accion, $data = null){
             $log = new Log;

            $log->user_id = Auth::user()->id;
            $log->roles_id = Auth::user()->roles_id;
            $log->tabla = $table_name;
            $log->accion = $accion;
            $log->registro_id = $id;
            $log->valor_antiguo = strval($data);
            $log->save();
        }

}
