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
        Schema::create('caixas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->decimal('valor_abertura', 12, 2);
            $table->decimal('valor_fechamento', 12, 2)->nullable();
            $table->dateTime('aberto_em');
            $table->dateTime('fechado_em')->nullable();
            $table->enum('status', ['aberto', 'fechado'])->default('aberto');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('caixas');
    }
};
