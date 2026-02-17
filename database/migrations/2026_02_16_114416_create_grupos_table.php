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
        Schema::create('grupos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('curso_id')->constrained()->onDelete('cascade');
            $table->foreignId('asignatura_id')->constrained()->onDelete('cascade');
            $table->foreignId('profesor_tutor_id')->nullable()->constrained('usuarios')->nullOnDelete();
            $table->string('nombre_grupo');
            $table->tinyInteger('capacidad_maxima')->default(30);
            $table->timestamps();

            $table->unique(['curso_id', 'asignatura_id', 'nombre_grupo']);
            $table->index(['curso_id', 'asignatura_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('grupos');
    }
};
