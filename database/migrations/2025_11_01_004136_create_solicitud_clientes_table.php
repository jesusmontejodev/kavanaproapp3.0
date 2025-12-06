<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('solicitud_clientes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_user');
            $table->unsignedBigInteger('id_lead');
            $table->text('descripcion_solicitud');
            $table->enum('estado', ['pendiente', 'aprobada', 'rechazada'])->default('pendiente');
            $table->text('comentario_admin')->nullable();
            $table->timestamp('fecha_revision')->nullable();
            $table->timestamps();

            // Foreign keys explícitas
            $table->foreign('id_user')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('id_lead')->references('id')->on('leads')->onDelete('cascade');

            $table->index('id_user');
            $table->index('id_lead');
            $table->index('estado');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('solicitud_clientes');
    }
};
