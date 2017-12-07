<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSemestresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('semestres', function (Blueprint $table) {
            $table->increments('id');
            $table->string('ano');
            $table->string('semestre');
            $table->timestamps();
        });
        Schema::table('materias', function ($table) {
            $table->integer('semestre')->unsigned();
            $table->foreign('semestre')->references('id')->on('semestres');
        });

        Schema::table('reportes', function ($table) {
            $table->integer('semestre')->unsigned();
            $table->foreign('semestre')->references('id')->on('semestres');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('semestres');
    }
}
