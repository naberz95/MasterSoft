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
        Schema::create('consecutivos_acta', function (Blueprint $table) {
            $table->id();
            $table->string('anio', 2);    // 2 dígitos de año (por ejemplo: '24')
            $table->string('mes', 2);     // 2 dígitos de mes (por ejemplo: '07')
            $table->integer('consecutivo')->default(0);
            $table->timestamps();

            $table->unique(['anio', 'mes']);
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('consecutivos_acta');
    }
};
