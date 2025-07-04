<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('resumenes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('acta_id')->constrained('actas')->onDelete('cascade');
            $table->date('fecha');
            $table->text('descripcion');
            $table->decimal('horas', 5, 2)->default(0);
            $table->boolean('facturable')->default(true);
            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('resumenes');
    }
};
