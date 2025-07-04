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
    Schema::create('compromisos', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('acta_id');
        $table->string('descripcion');
        $table->unsignedBigInteger('acta_persona_id');
        $table->date('fecha')->nullable();
        $table->enum('estado', ['Pendiente', 'En proceso', 'Cumplido'])->default('Pendiente');
        $table->timestamps();

        $table->foreign('acta_id')->references('id')->on('actas')->onDelete('cascade');
        $table->foreign('acta_persona_id')->references('id')->on('acta_persona')->onDelete('cascade');
    });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('compromisos');
    }
};
