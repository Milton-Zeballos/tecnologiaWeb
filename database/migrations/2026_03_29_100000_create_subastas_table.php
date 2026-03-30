<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('subastas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();

            $table->string('titulo_subasta');
            $table->string('nombre_producto');
            $table->text('descripcion');

            $table->string('media_path')->nullable();
            $table->string('media_type', 20)->default('image'); // image|video

            $table->decimal('precio_inicial', 12, 2);
            $table->decimal('precio_minimo', 12, 2)->comment('Reserva: venta válida solo si la oferta gana >= este monto');

            $table->dateTime('empieza_en');
            $table->unsignedInteger('duracion_minutos');
            $table->unsignedInteger('extension_por_oferta_minutos');

            $table->dateTime('termina_en');

            $table->boolean('finalizada')->default(false);
            $table->timestamp('cerrada_en')->nullable();
            $table->foreignId('ganador_user_id')->nullable()->constrained('users')->nullOnDelete();

            $table->decimal('ultima_oferta_monto', 12, 2)->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('subastas');
    }
};
