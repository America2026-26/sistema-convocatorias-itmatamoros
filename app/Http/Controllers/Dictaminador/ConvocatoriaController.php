<?php

namespace App\Http\Controllers\Dictaminador;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Convocatoria;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class ConvocatoriaController extends Controller
{
    public function index(): View
    {
        return view('dictaminador.convocatorias.index', [
            'convocatorias' => Convocatoria::query()
                ->withCount([
                    'postulaciones',
                    'postulaciones as pendientes_count' => fn($query) => $query
                        ->where('estatus', 'pendiente'),
                ])
                ->orderByDesc('created_at')
                ->orderByDesc('id')
                ->paginate(15),
        ]);
    }
    /**
     * @return array<string, mixed>
     */
}
