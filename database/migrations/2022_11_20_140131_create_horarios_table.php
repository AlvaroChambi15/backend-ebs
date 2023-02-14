<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('horarios', function (Blueprint $table) {
            $table->id();
            $table->date("fecha");
            $table->time("hora");
            $table->string("estado", 20);
            $table->string("detalle")->nullable();
            $table->string("nota")->nullable();

            //Relacion de 1 Solicitud : N Horarios
            $table->bigInteger("reserve_id")->unsigned()->nullable();
            $table->foreign("reserve_id")->references("id")->on("reserves");

            //Relacion de 1 Horario : 1 Citas
            /* $table->bigInteger("cita_id")->unsigned()->nullable();
            $table->foreign("cita_id")->references("id")->on("citas"); */

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
        Schema::dropIfExists('horarios');
    }
};