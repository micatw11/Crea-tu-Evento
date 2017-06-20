<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRubrosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rubros', function (Blueprint $table) {
            $table->increments('id');
            $table->string('denominacion', 200);
            $table->date('fecha_habilitacion');
            $table->integer('habilitacion')->unsigned();
            $table->integer('codigo')->unsigned();
            $table->integer('categoria_id')->unsigned();
            $table->integer('proveedor_id')->unsigned();
            $table->integer('domicilio_id')->unsigned()->nullable();
            $table->foreign('proveedor_id')->references('id')->on('proveedores');
            $table->foreign('domicilio_id')->references('id')->on('domicilios');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('rubros');
    }
}
