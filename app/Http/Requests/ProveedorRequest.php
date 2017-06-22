<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProveedorRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'user_id' => 'required|exists:usuarios,id',
            'nombre' => 'required|min:4|max:55',
            'cuit' => 'required|min:9|max:11',
            'habilitacion' => 'required|min:5|max:10',
            'ingresos_brutos' => 'required|min:5|max:10',
            'email' => 'required|email',
            'calle'=>'required|min:4|max:55',
            'numero'=> 'required|min:1|max:10',
            'piso'=> 'min:1|max:10',
            'localidad_id'=> 'required|exists:localidades,id',
            'codigo_postal'=> 'min:3|max:10',
        ];
    }

  
}
