<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableReportesRespuestas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reportes_respuestas', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('reporte_id')->unsigned();
            $table->bigInteger('checklist_reactivo_id')->unsigned();
            $table->boolean('tiene_informacion')->nullable();
            $table->boolean('no_aplica')->nullable();
            $table->text('comentarios')->nullable();
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
        Schema::dropIfExists('reportes_respuestas');
    }
}
