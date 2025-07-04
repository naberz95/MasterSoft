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
        Schema:: create('empresas', function (Blueprint $table) {
            $table->id();
            $table->string('nit')->unique();
            $table->string('nombre');
            $table->string('direccion');
            $table->string('logo_empresa')->nullable();
            $table->string('telefono')->nullable();
            $table->string('email')->nullable();
            $table->softDeletes();
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('empresas');
    }
};
