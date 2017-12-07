<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHistorialReportesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('historial_reportes', function (Blueprint $table) {
            $table->increments('id');
            $table->dateTime('fecha');
            $table->integer('reporte')->unsigned();
            $table->integer('autor')->unsigned(); //PROFESOR O ESTUDIANTE
            $table->integer('estado')->unsigned();
            $table->string('descripcion')->nullable();
            $table->timestamps();

            $table->foreign('reporte')->references('id')->on('reportes');
            $table->foreign('estado')->references('id')->on('estado_reportes');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('historial_reportes');
    }
}
