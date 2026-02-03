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
            $table->enum('tipo', ['directivo', 'jefe_estudios']);
            $table->unique(['centro_id', 'user_id']); // Un usuario un rol jerarquía por centro
            $table->timestamps();
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
