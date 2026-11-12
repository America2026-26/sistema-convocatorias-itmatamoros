<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocumentoExpediente extends Model
{
    use HasFactory;

    protected $table = 'documento_expedientes';

    protected $fillable = [
        'user_id',
        'tipo',
        'nombre_original',
        'ruta',
        'estatus'
    ];
}