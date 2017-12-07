<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePcEstudiantesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pc_estudiantes', function (Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->integer('fila');
            $table->integer('columna');
            $table->timestamps();

            $table->foreign('id')->references('id')->on('dispositivos');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pc_estudiantes');
    }
}
