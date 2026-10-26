<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Postulacion extends Model
{
    protected $table = 'postulaciones';

    public const STATUS_PENDING = 'pendiente';

    public const STATUS_APPROVED = 'aprobada';

    public const STATUS_REJECTED = 'rechazada';

    public const STATUSES = [
        self::STATUS_PENDING,
        self::STATUS_APPROVED,
        self::STATUS_REJECTED,
    ];

    protected $fillable = [
        'convocatoria_id',
        'user_id',
        'estatus',
        'informacion',
        'documento_path',
        'revisado_por',
        'revisado_en',
        'comentarios_revision',
    ];

    protected function casts(): array
    {
        return [
            'informacion' => 'array',
            'revisado_en' => 'datetime',
        ];
    }

    public function convocatoria(): BelongsTo
    {
        return $this->belongsTo(Convocatoria::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class)->withTrashed();
    }

    public function revisor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'revisado_por')->withTrashed();
    }
}
