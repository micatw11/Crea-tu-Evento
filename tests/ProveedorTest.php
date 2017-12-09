<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\User;
use App\Proveedor;
class ProveedorTest extends TestCase
{
	use WithoutMiddleware;
	use DatabaseMigrations;

    public function testShowProveedor()
    {
    	$proveedor = Proveedor::aprobado()->first();
        // Assert that each user in the list has at least an id, name and email attribute.
        $this->get('api/proveedor/'.$proveedor->id)
             ->seeJsonStructure([
				'data' => [
					'id', 'cuit', 'nombre', 'email', 'ingresos_brutos', 'user_id', 'domicilio_id', 'estado', 'created_at', 'updated_at',
					'adjunto', 'observaciones', 'register_by_user_id', 'accepted_by_user_id', 'rejected_by_user_id', 'telefono_id',
					'domicilio' => [
						'id', 'tipo_domicilio', 'calle', 'numero', 'piso', 'localidad_id', 'created_at', 'updated_at',
						'localidad' => [
							'id', 'provincia_id', 'nombre', 'created_at', 'updated_at',
							'provincia' => [
								'id', 'nombre', 'created_at', 'updated_at'
							]
						]
					],
					'user' => [
						'id','name', 'email', 'created_at', 'updated_at', 'estado', 'roles_id',
						'usuario' => [
							'id','avatar','nombre', 'apellido', 'fecha_nac', 'sexo', 'user_id', 'localidad_id', 'created_at', 'updated_at'
						]
					],
					'telefono' => [
						'id', 'cod_area', 'numero', 'created_at', 'updated_at'
					]
				]
             ]);
    }

    
}
