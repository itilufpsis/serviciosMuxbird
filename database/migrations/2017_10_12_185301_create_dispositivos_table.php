<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDispositivosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dispositivos', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('numero')->nullable();
            $table->integer("numero_reportes")->default(0);
            $table->integer('tipo')->unsigned();
            $table->integer('salon')->unsigned();
            $table->string('referencia')->nullable();
            $table->timestamps();

            $table->foreign('tipo')->references('id')->on('tipo_dispositivos');
            $table->foreign('salon')->references('id')->on('salons');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('dispositivos');
    }
}
