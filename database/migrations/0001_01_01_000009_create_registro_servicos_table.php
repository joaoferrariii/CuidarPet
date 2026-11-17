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
        Schema::create('registros_servicos', function (Blueprint $table) {
            $table->id();

            $table->foreignId('pet_id')
                ->constrained('pets')
                ->cascadeOnDelete();

            $table->foreignId('servico_id')
                ->constrained('servicos')
                ->restrictOnDelete();

            $table->date('data');
            $table->timestamps();

            $table->foreignId('forma_pagamento_id')
            ->constrained('formas_pagamento');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('registro_servicos');
    }
};
