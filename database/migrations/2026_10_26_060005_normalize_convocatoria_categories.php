<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('convocatorias')
            ->where('tipo_concurso', 'like', '%abiert%')
            ->update(['tipo_concurso' => 'Abierta']);

        DB::table('convocatorias')
            ->where('tipo_concurso', 'like', '%cerrad%')
            ->update(['tipo_concurso' => 'Cerrada']);

        DB::table('convocatorias')
            ->whereRaw('LOWER(tipo_personal) LIKE ?', ['%no%docente%'])
            ->update(['tipo_personal' => 'No docente']);

        DB::table('convocatorias')
            ->where('tipo_personal', 'like', '%docente%')
            ->where('tipo_personal', 'not like', '%no%docente%')
            ->update(['tipo_personal' => 'Docente']);
    }

    public function down(): void
    {
        DB::table('convocatorias')
            ->where('tipo_concurso', 'Abierta')
            ->update(['tipo_concurso' => 'Público Abierto']);

        DB::table('convocatorias')
            ->where('tipo_concurso', 'Cerrada')
            ->update(['tipo_concurso' => 'Público Cerrado']);

        DB::table('convocatorias')
            ->where('tipo_personal', 'No docente')
            ->update(['tipo_personal' => 'No Docente']);
    }
};
