<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Solo corregir el typo en la columna
        if (Schema::hasColumn('cliente_archivos', 'ulr_archivo')) {
            Schema::table('cliente_archivos', function (Blueprint $table) {
                $table->renameColumn('ulr_archivo', 'url_archivo');
            });
        }
    }

    public function down(): void
    {
        // Revertir si es necesario
        if (Schema::hasColumn('cliente_archivos', 'url_archivo')) {
            Schema::table('cliente_archivos', function (Blueprint $table) {
                $table->renameColumn('url_archivo', 'ulr_archivo');
            });
        }
    }
};
