<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Doctrine\DBAL\Schema as DoctrineSchema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('usuarios', function (Blueprint $table) {
            $table->dropForeign(['centro_id']);
        });

        Schema::table('usuarios', function (Blueprint $table) {
            $table->unsignedBigInteger('centro_id')->nullable()->change();
        });

        Schema::table('usuarios', function (Blueprint $table) {
            $table->foreign('centro_id')->references('id')->on('centros')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('usuarios', function (Blueprint $table) {
            $table->dropForeign(['centro_id']);
            $table->unsignedBigInteger('centro_id')->nullable(false)->change();
            $table->foreign('centro_id')->references('id')->on('centros')->onDelete('cascade');
        });
    }
};