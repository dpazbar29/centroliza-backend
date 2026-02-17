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
        Schema::create('avisos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('centro_id')->constrained()->onDelete('cascade');
            $table->string('titulo');
            $table->text('contenido');
            $table->enum('tipo', ['general', 'profesores', 'alumnos', 'familias', 'urgente']);
            $table->date('fecha_publicacion');
            $table->date('fecha_expiracion')->nullable();
            $table->boolean('visible')->default(1);
            $table->timestamps();

            $table->index(['centro_id', 'fecha_publicacion']);
            $table->index('fecha_expiracion');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('avisos');
    }
};
