<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('postulaciones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('convocatoria_id')->constrained()->noActionOnDelete();
            $table->foreignId('user_id')->constrained()->noActionOnDelete();
            $table->string('estatus', 32)->default('pendiente');
            $table->json('informacion')->nullable();
            $table->string('documento_path', 500)->nullable();
            $table->foreignId('revisado_por')->nullable()->constrained('users')->noActionOnDelete();
            $table->timestamp('revisado_en')->nullable();
            $table->text('comentarios_revision')->nullable();
            $table->timestamps();

            $table->unique(['convocatoria_id', 'user_id']);
            $table->index(['estatus', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('postulaciones');
    }
};
