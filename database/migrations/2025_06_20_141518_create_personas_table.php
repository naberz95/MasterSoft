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
        Schema::create('personas', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('iniciales', 10);
            $table->string('cargo');
            $table->string('firma_path')->nullable();
            $table->string('tarjeta_profesional')->nullable();
            $table->date('fecha_tarjeta')->nullable();
            $table->string('cedula')->nullable();
            $table->date('fecha_expedicion_cedula')->nullable();
            $table->string('lugar_expedicion_cedula')->nullable();
            $table->foreignId('empresa_id')->constrained('empresas');
            $table->softDeletes();
            $table->timestamps();
        });
    }



    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('personas');
    }
};
