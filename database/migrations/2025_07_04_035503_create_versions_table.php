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
        Schema::create('versions', function (Blueprint $table) {
            $table->id();
            
            // 📋 CONTROL DE VERSIONES DEL DOCUMENTO
            $table->integer('version')->default(1)->comment('Número de versión (1, 2, 3, etc.)');
            $table->date('fecha_creacion')->comment('Fecha de creación de la versión');
            $table->text('descripcion_cambio')->comment('Descripción de los cambios realizados');
            
            // 📊 CONTROL DE REVISIÓN Y APROBACIÓN DEL DOCUMENTO
            $table->string('revisado_por', 255)->nullable()->comment('Nombre de quien revisó');
            $table->date('fecha_revision')->nullable()->comment('Fecha de revisión');
            $table->string('aprobado_por', 255)->nullable()->comment('Nombre de quien aprobó');
            $table->date('fecha_aprobado')->nullable()->comment('Fecha de aprobación');
            $table->enum('estado', ['Pendiente', 'Revisado', 'Aprobado'])->default('Pendiente')->comment('Estado actual del documento');
            $table->date('fecha_aprobacion_documento')->nullable()->comment('Fecha de aprobación final del documento');
            
            // 📝 METADATOS
            $table->timestamps();
            
            // 🔍 ÍNDICES PARA OPTIMIZACIÓN
            $table->index('version');
            $table->index('estado');
            $table->index('fecha_creacion');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('versions');
    }
};