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
        Schema::table('produtos', function (Blueprint $table) {
            if (Schema::hasColumn('produtos', 'preco')) {
                $table->dropColumn('preco');
            }

            if (Schema::hasColumn('produtos', 'quantidade_estoque')) {
                $table->dropColumn('quantidade_estoque');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('produtos', function (Blueprint $table) {
            if (!Schema::hasColumn('produtos', 'preco')) {
                $table->decimal('preco', 10, 2)->default(0);
            }

            if (!Schema::hasColumn('produtos', 'quantidade_estoque')) {
                $table->integer('quantidade_estoque')->default(0);
            }
        });
    }
};
