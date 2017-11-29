<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableProveedoresLogs extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('proveedores', function (Blueprint $table) {
            $table->integer('register_by_user_id')->unsigned()->default(1);
            $table->integer('accepted_by_user_id')->unsigned()->nullable()->default(null);
            $table->integer('rejected_by_user_id')->unsigned()->nullable()->default(null);
            $table->foreign('register_by_user_id')->references('id')->on('users');
            $table->foreign('accepted_by_user_id')->references('id')->on('users');
            $table->foreign('rejected_by_user_id')->references('id')->on('users');
        });
        Schema::table('logs', function (Blueprint $table) {
            $table->string('url')->nullable()->default(null);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
