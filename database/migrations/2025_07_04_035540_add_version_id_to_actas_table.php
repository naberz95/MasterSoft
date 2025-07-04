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
        Schema::table('actas', function (Blueprint $table) {
            // Agregar columna version_id después de proyecto_id
            $table->foreignId('version_id')
                  ->nullable()
                  ->after('proyecto_id')
                  ->constrained('versions')
                  ->onDelete('set null')
                  ->comment('Versión del documento asociada al acta');
            
            // Índice para optimizar consultas
            $table->index('version_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('actas', function (Blueprint $table) {
            // Eliminar foreign key constraint primero
            $table->dropForeign(['version_id']);
            
            // Eliminar índice
            $table->dropIndex(['version_id']);
            
            // Eliminar columna
            $table->dropColumn('version_id');
        });
    }
};