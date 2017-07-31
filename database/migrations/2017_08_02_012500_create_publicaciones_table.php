<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePublicacionesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
         Schema::create('publicaciones', function (Blueprint $table) {
            $table->increments('id');
            $table->date('fecha_finalizacion')->nullable();
            $table->string('titulo');
            $table->string('oferta')->nullable();
            $table->string('descripcion');
            $table->integer('estado')->default(1);
            $table->timestamps();
        });

        //Publicaciones & rubos = publicaciones_rubros
        Schema::create('publicacion_rubro', function (Blueprint $table){
            $table->increments('id');
            $table->integer('publicacion_id')->unsigned();
            $table->integer('rubros_detalle_id')->unsigned();
            $table->foreign('publicacion_id')->references('id')->on('publicaciones');
            $table->foreign('rubros_detalle_id')->references('id')->on('rubros_detalle');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('publicaciones');
        Schema::dropIfExists('publicaciones_rubros');
    }
}
