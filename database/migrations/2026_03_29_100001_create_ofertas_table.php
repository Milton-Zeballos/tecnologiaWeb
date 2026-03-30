<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ofertas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('subasta_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->decimal('monto', 12, 2);
            $table->unsignedInteger('incremento_bs')->comment('10, 50 o 100');
            $table->timestamps();

            $table->index(['subasta_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ofertas');
    }
};
