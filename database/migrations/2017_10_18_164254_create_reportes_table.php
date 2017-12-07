<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReportesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reportes', function (Blueprint $table) {
            $table->increments('id');
            $table->dateTime('fecha');
            $table->dateTime('fecha_solucion')->nullable();
            $table->integer('dispositivo')->unsigned();
            $table->integer('docente')->unsigned(); //PROFESOR
            $table->enum('perisferico',array('teclado','mouse','pantalla','torre'))->nullable(); //SI NO ES PC NO SE ACTIVA ESTA OPCION
            $table->integer('estado')->unsigned();
            $table->integer('cantidad')->desfault(1);
            $table->timestamps();

            $table->foreign('estado')->references('id')->on('estado_reportes');
            //  $table->foreign('docente')->references('id')->on('usuarios');
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('reportes');
    }
}
