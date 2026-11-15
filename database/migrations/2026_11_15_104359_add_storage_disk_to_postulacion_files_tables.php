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
        Schema::table('postulacion_documentos', function (Blueprint $table) {
            $table->string('storage_disk', 32)->default('local');
        });

        Schema::table('postulaciones', function (Blueprint $table) {
            $table->string('documento_storage_disk', 32)->default('local');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('postulaciones', function (Blueprint $table) {
            $table->dropColumn('documento_storage_disk');
        });

        Schema::table('postulacion_documentos', function (Blueprint $table) {
            $table->dropColumn('storage_disk');
        });
    }
};
