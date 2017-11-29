<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
//https://gist.github.com/JeffreyWay/5623836 ejemplos
class UserTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testGetRaiz()
    {
	    $response = $this->call('GET', '/');
	    $this->assertEquals(200, $response->status());    
	}
	public function testPostRegisterWithErrors(){
		$user = [	'name' => 'Taylor',
	            'email' => 'asasas',
	            'password' => '123456',
	            'password_confirmation' => '123457',
	            'nombre' => 'Lucas',
	            'apellido' => 'Gomez',
	            'sexo' => '',
	            'localidad_id' => 126,
	            'fecha_nac' => '1980/05/05'
            ];
		$response = $this->call('POST', '/api/register', $user);
		$this->assertEquals(422, $response->status()); 
	}
	public function testPostRegister(){
		$user = [	'name' => 'Taylor',
	            'email' => 'asasas',
	            'password' => '123456',
	            'password_confirmation' => '12345',
	            'nombre' => 'Lucas',
	            'apellido' => 'Gomez',
	            'sexo' => 'M',
	            'localidad_id' => 126,
	            'fecha_nac' => '1980/05/05'
            ];
		$response = $this->call('POST', '/api/register', $user);
		$this->assertEquals(422, $response->status())->see(' '.$response->status()); 
	}
	public function testPostLoginWithError(){
		$response = $this->call('POST', '/api/register', 
			[
	            'email' => 'asdasd@gmail.com',
	            'password' => '64556456',
	            'remember' => false,
            ]);
		$this->assertEquals(401, $response->status()); 
	}
}
