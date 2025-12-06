<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('clientes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_user')->constrained('users')->onDelete('cascade');
            $table->foreignId('id_lead')->nullable()->constrained('leads')->onDelete('set null'); // Lead original
            $table->string('nombre_completo');
            $table->string('email');
            $table->string('telefono')->nullable();

            // Información de la propiedad comprada
            $table->string('inmueble_comprado')->nullable(); // Nombre/descripción del inmueble
            $table->decimal('precio_compra', 15, 2)->nullable();
            $table->string('direccion_inmueble')->nullable();
            $table->string('tipo_inmueble')->nullable(); // casa, apartamento, etc.

            // Proceso de entrega
            $table->enum('estado_entrega', [
                'contrato_firmado',
                'proceso_escrituras',
                'avance_obra',
                'ultimos_detalles',
                'entrega_finalizada'
            ])->default('contrato_firmado');

            // Fechas importantes
            $table->date('fecha_compra')->nullable();
            $table->date('fecha_entrega_estimada')->nullable();
            $table->date('fecha_entrega_real')->nullable();

            // Seguimiento
            $table->text('ultimo_seguimiento')->nullable();
            $table->date('proximo_seguimiento')->nullable();
            $table->text('observaciones_entrega')->nullable();

            $table->text('notas')->nullable();
            $table->timestamps();

            $table->index('id_user');
            $table->index('id_lead');
            $table->index('estado_entrega');
            $table->index('proximo_seguimiento');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('clientes');
    }
};
