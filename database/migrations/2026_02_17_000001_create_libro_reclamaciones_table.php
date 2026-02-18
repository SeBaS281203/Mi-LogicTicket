<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Libro de Reclamaciones Virtual - INDECOPI Perú.
 * Cumple con los requisitos del Libro de Reclamaciones electrónico.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('libro_reclamaciones', function (Blueprint $table) {
            $table->id();
            $table->string('codigo_reclamo', 32)->unique()->comment('Correlativo tipo LR-AÑO-000001');
            $table->string('tipo_documento', 20)->comment('DNI, CE, Pasaporte');
            $table->string('numero_documento', 20);
            $table->string('nombre_completo', 255);
            $table->string('direccion', 500);
            $table->string('telefono', 30);
            $table->string('email');
            $table->string('tipo_reclamo', 20)->comment('reclamo, queja');
            $table->text('descripcion');
            $table->text('pedido_consumidor')->nullable();
            $table->foreignId('evento_id')->nullable()->constrained('events')->nullOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('estado', 20)->default('pendiente')->comment('pendiente, atendido, cerrado');
            $table->text('respuesta_empresa')->nullable();
            $table->timestamp('fecha_respuesta')->nullable();
            $table->timestamps();
        });

        Schema::table('libro_reclamaciones', function (Blueprint $table) {
            $table->index('codigo_reclamo');
            $table->index('estado');
            $table->index('tipo_reclamo');
            $table->index('created_at');
            $table->index(['estado', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('libro_reclamaciones');
    }
};
