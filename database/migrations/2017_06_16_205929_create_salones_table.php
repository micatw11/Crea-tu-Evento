<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSalonesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('salones', function (Blueprint $table) {
            $table->increments('id'); 
            $table->integer('proveedor_id')->unsigned();
            $table->string('descripcion', 200);
            $table->integer('habilitacion')->unsigned();
            $table->date('fecha_habilitacion');
            $table->integer('domicilio_id')->unsigned();
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
        Schema::dropIfExists('salones');
    }
}
