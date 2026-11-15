<?php

namespace App\Models;

use Database\Factories\CalendarioEventoFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property string $titulo
 * @property Carbon $fecha
 * @property string|null $descripcion
 */
class CalendarioEvento extends Model
{
    /** @use HasFactory<CalendarioEventoFactory> */
    use HasFactory;

    protected $fillable = [
        'titulo',
        'fecha',
        'descripcion',
    ];

    protected function casts(): array
    {
        return [
            'fecha' => 'date',
        ];
    }
}
