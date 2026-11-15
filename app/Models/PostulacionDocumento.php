<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PostulacionDocumento extends Model
{
    public const STORAGE_DISK_LOCAL = 'local';

    public const STORAGE_DISK_ONEDRIVE = 'onedrive';

    protected $fillable = [
        'postulacion_id',
        'clave',
        'nombre',
        'path',
        'storage_disk',
    ];

    public function postulacion(): BelongsTo
    {
        return $this->belongsTo(Postulacion::class);
    }
}
