<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('leads', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_etapa')->nullable();
            $table->unsignedBigInteger('id_user');
            $table->string('orden');
            $table->string('nombre');
            $table->string('correo');
            $table->string('numero_telefono');
            $table->string('nombre_proyecto')->nullable();
            $table->timestamp('fecha_creado')->nullable();
            $table->timestamps();

            //FOREIGN KEYS
            $table->foreign('id_etapa')->references('id')->on('etapas');
            $table->foreign('id_user')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leads');
    }
};
