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
        Schema::create('reserves', function (Blueprint $table) {
            $table->id();
            $table->string("nombres", 60);
            $table->string("apellidos", 60);
            $table->string("edad", 5);
            $table->string("email", 80)->nullable();
            $table->string("celular", 9);
            $table->boolean("contacto_llamada")->default(false);
            $table->boolean("contacto_whatsapp")->default(false);
            $table->boolean("contacto_correo")->default(false);
            $table->string('especialidad', 60)->default("Consulta General");
            $table->date("fecha_soli");
            $table->time("hora_soli");

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
        Schema::dropIfExists('reserves');
    }
};