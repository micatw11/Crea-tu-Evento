<?php

namespace App\Http\Services;
use Illuminate\Support\Facades\DB;
class CheckCategoriesService
{

	public function checkCategories($subcategoria_id=null,$categoria_id=null ){
		if($subcategoria_id != null){
			$subcategoria = DB::table('subcategorias')
							->select(DB::raw('count(*) as quantity'), 'subcategorias.categoria_id', 'subcategorias.id')
							->join('rubros', 'subcategorias.id', '=', 'rubros.subcategoria_id')
							->where('subcategorias.id', $subcategoria_id)
							->groupBy('subcategorias.id')->first();
			if($subcategoria->quantity == 0)
			{
				$categoria_id = $subcategoria->categoria_id;
				DB::table('subcategorias')->where('subcategorias.id', $subcategoria_id)->delete();

			}
		}
		if($categoria_id){
			$categoria = DB::table('categorias')
				->select(DB::raw('count(*) as quantity'), 'categorias.id')
				->join('subcategorias', 'categorias.id', '=', 'subcategorias.categoria_id')
				->where('categorias.id', $categoria_id)
				->groupBy('categorias.id')->first();

			if($categoria->quantity == 0)
			{
				DB::table('categorias')->where('categorias.id', $categoria_id)->delete();
			}
		}
	}
}