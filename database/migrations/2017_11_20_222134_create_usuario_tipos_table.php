<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsuarioTiposTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('usuario_tipos', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('usuario')->unsigned();
            $table->integer('tipo')->unsigned();
            $table->timestamps();

            $table->foreign('usuario')->references('id')->on('usuarios');
            $table->foreign('tipo')->references('id')->on('tipo_usuarios');
        });

        Schema::table('materias', function ($table) {
            $table->foreign('docente')->references('id')->on('usuario_tipos');
        });

        Schema::table('reportes', function ($table) {
            $table->foreign('docente')->references('id')->on('usuario_tipos');
        });

        Schema::table('historial_reportes', function ($table) {
            $table->foreign('autor')->references('id')->on('usuario_tipos');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('usuario_tipos');
    }
}
