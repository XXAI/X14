<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableUsuariosDireccionesProyectos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('usuarios_direcciones_proyectos', function (Blueprint $table) {
            $table->integer('user_id')->unsigned();
            $table->bigInteger('direccion_id')->unsigned();
            $table->bigInteger('proyecto_id')->unsigned()->nullable();
            $table->boolean('todos_proyectos')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('usuarios_direcciones_proyectos');
    }
}
