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
        Schema::create('roles_jerarquia', function (Blueprint $table) {
            $table->id();
            $table->foreignId('centro_id')->constrained('centros')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('usuarios')->onDelete('cascade');
            $table->enum('tipo', ['directivo', 'jefe_estudios', 'coordinador', 'tutor'])->default('directivo');
            $table->tinyInteger('orden_prioridad')->default(0);
            $table->date('fecha_asignacion');
            $table->timestamps();

            $table->unique(['centro_id', 'user_id'], 'centro_user_unique');
            $table->index(['centro_id', 'orden_prioridad']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('roles_jerarquia');
    }
};
