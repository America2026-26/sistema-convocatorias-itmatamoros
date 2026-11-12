<?php

namespace App\Http\Controllers;

use App\Models\Postulacion;
use App\Models\PostulacionDocumento;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class PostulacionDocumentoController extends Controller
{
    public function show(
        Request $request,
        Postulacion $postulacion,
        PostulacionDocumento $documento,
    ): BinaryFileResponse {
        $user = $request->user();

        if ($user->role === User::ROLE_USER) {
            abort_unless($postulacion->user_id === $user->id, 404);
        } elseif ($user->role !== User::ROLE_REVIEWER) {
            abort(403);
        }

        abort_unless($documento->postulacion_id === $postulacion->id, 404);

        $disk = Storage::disk('local');
        abort_unless($disk->exists($documento->path), 404);

        return response()->file($disk->path($documento->path), [
            'Content-Type' => 'application/pdf',
            'X-Content-Type-Options' => 'nosniff',
            'Content-Disposition' => 'inline; filename="documento-'.$documento->id.'.pdf"',
        ]);
    }
}
