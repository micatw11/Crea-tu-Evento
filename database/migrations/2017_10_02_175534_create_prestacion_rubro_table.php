<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePrestacionRubroTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('prestacion_rubro', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('prestacion_id')->unsigned();
            $table->integer('rubro_id')->unsigned();

            $table->foreign('prestacion_id')->references('id')->on('prestaciones');
            $table->foreign('rubro_id')->references('id')->on('rubros');
            $table->timestamps();
        });

        Schema::table('publicaciones', function (Blueprint $table) {
            $table->integer('prestacion_id')->unsigned();
           
            $table->foreign('prestacion_id')->references('id')->on('prestaciones');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('prestacion_rubro');
    }
}
