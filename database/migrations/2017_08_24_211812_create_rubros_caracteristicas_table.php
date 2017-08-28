<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRubrosCaracteristicasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
         Schema::create('rubros_caracteristicas', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('rubro_id')->unsigned();
            $table->integer('caracteristica_id')->unsigned();

            $table->foreign('rubro_id')->references('id')->on('rubros');
            $table->foreign('caracteristica_id')->references('id')->on('caracteristicas');
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
        Schema::dropIfExists('rubros_caracteristicas');
    }
}
