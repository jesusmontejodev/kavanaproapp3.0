<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('registro_referidos', function (Blueprint $table) {
            $table->id();

            // El usuario nuevo
            $table->unsignedBigInteger('user_id');

            // El usuario que refirió
            $table->unsignedBigInteger('referido_por');

            $table->timestamps();

            // Relaciones
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('referido_por')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('registro_referidos');
    }
};
