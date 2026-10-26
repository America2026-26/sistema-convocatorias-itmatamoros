<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Convocatoria extends Model
{
    protected $fillable = [
        'titulo',
        'tipo_concurso',
        'tipo_personal',
        'categoria_nivel',
        'plaza_disponible',
        'jornada_horario',
        'salario',
        'fecha_inicio',
        'fecha_fin',
        'estatus',
        'requisitos_especificos',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'fecha_inicio' => 'date',
            'fecha_fin' => 'date',
            'salario' => 'decimal:2',
        ];
    }

    public function postulaciones(): HasMany
    {
        return $this->hasMany(Postulacion::class);
    }

    public function isOpen(): bool
    {
        return $this->estatus === 'Activa'
            && now()->betweenIncluded(
                $this->fecha_inicio->copy()->startOfDay(),
                $this->fecha_fin->copy()->endOfDay(),
            );
    }
}
