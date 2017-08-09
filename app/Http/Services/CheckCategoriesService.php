<?php

namespace App\Http\Services;
use Illuminate\Support\Facades\DB;

class CheckCategoriesService
{
    /**
    * @param  $subcategoria_id  
     * Elimina la subcategoria dada por parametro 
     * si esta no esta relacionada a ningun rubro.
     *
     */
	public function checkSubcategories($subcategoria_id=null )
	{
		if($subcategoria_id != null)
		{
            $subcategoria = DB::table('subcategorias')
                ->select(DB::raw('count(rubros.subcategoria_id) as quantity'), 'subcategorias.categoria_id')
                    ->join('rubros', 'subcategorias.id', '=', 'rubros.subcategoria_id')
                	->where('subcategorias.id', $subcategoria_id)->first();

			if($subcategoria->quantity === 0)
			{
				$categoria_id = $subcategoria->categoria_id;
				DB::table('subcategorias')->where('subcategorias.id', $subcategoria_id)->delete();

				$this->checkCategories($categoria_id);
			}
		}

	}

    /**
    * @param  $categoria_id  
     * Elimina la categoria dada por parametro 
     * si esta no esta relacionada a ninguna subcategoria.
     *
     */
	public function checkCategories($categoria_id=null)
	{
		if($categoria_id)
		{
			$categoria = DB::table('categorias')
				->select(DB::raw('count(subcategorias.categoria_id) as quantity'), 'categorias.id')
				->join('subcategorias', 'categorias.id', '=', 'subcategorias.categoria_id')
				->where('categorias.id', $categoria_id)->first();

			if($categoria->quantity == 0)
			{
				DB::table('categorias')->where('categorias.id', $categoria_id)->delete();
			}
		}
	}

}