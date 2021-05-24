<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableChecklist extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('checklists', function (Blueprint $table) {
            $table->id();
            $table->string('descripcion');
            $table->boolean('activo');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('checklists_titulos', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('checklist_id')->unsigned();
            $table->string('seccion')->nullable();
            $table->string('titulo');
            $table->string('subtitulo')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('checklists_reactivos', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('checklist_titulo_id')->unsigned();
            $table->integer('orden');
            $table->string('descripcion');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('checklists_reactivos');
        Schema::dropIfExists('checklists_titulos');
        Schema::dropIfExists('checklists');
    }
}
