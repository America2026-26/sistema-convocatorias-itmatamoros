<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('convocatorias', function (Blueprint $table) {
            $table->id();
            $table->string('titulo', 255);
            $table->string('tipo_concurso', 100);
            $table->string('tipo_personal', 100);
            $table->string('categoria_nivel', 150);
            $table->string('plaza_disponible', 150);
            $table->string('jornada_horario', 150);
            $table->decimal('salario', 12, 2)->nullable();
            $table->date('fecha_inicio');
            $table->date('fecha_fin')->index();
            $table->string('estatus', 32)->default('Activa')->index();
            $table->text('requisitos_especificos')->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->timestamps();

            $table->index(['estatus', 'fecha_fin']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('convocatorias');
    }
};
