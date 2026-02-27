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
        Schema::create('vendas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('caixa_id')->constrained('caixas')->cascadeOnDelete();
            $table->decimal('total_bruto', 12, 2);
            $table->decimal('desconto', 12, 2)->default(0);
            $table->decimal('total_liquido', 12, 2);
            $table->enum('forma_pagamento', ['dinheiro', 'pix', 'credito', 'debito', 'crediario']);
            $table->enum('status', ['aberta', 'finalizada', 'cancelada'])->default('aberta');
            $table->timestamp('created_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vendas');
    }
};
