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
    Schema::create('actas', function (Blueprint $table) {
        $table->id();
        $table->foreignId('tipo_id')->constrained('tipos_acta')->onDelete('restrict');
        $table->string('numero')->unique();
        $table->date('fecha');
        $table->time('hora_inicio');
        $table->time('hora_fin');
        $table->string('lugar');
        $table->foreignId('ciudad_id')->constrained('ciudades')->onDelete('restrict');
        $table->foreignId('empresa_id')->constrained('empresas')->onDelete('restrict');
        $table->foreignId('proyecto_id')->constrained('proyectos')->onDelete('restrict');
        $table->text('objetivo');
        $table->text('agenda');
        $table->longText('desarrollo');
        $table->longText('conclusiones');
        $table->date('proxima_reunion')->nullable();
        $table->foreignId('firmante_empresa_id')->constrained('personas')->onDelete('restrict');
        $table->foreignId('firmante_gp_id')->constrained('personas')->onDelete('restrict');
        $table->boolean('facturable');
        $table->softDeletes();
        $table->timestamps();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('actas');
    }
};
