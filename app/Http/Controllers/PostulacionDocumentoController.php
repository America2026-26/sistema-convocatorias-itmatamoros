<?php

namespace App\Http\Controllers;

use App\Models\Postulacion;
use App\Models\PostulacionDocumento;
use App\Models\User;
use App\Services\OneDriveDocumentStorage;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PostulacionDocumentoController extends Controller
{
    public function show(
        Request $request,
        Postulacion $postulacion,
        PostulacionDocumento $documento,
        OneDriveDocumentStorage $documentStorage,
    ): Response {
        $user = $request->user();

        if ($user->role === User::ROLE_USER) {
            abort_unless((int) $postulacion->user_id === (int) $user->getAuthIdentifier(), 404);
        } elseif (! in_array($user->role, [User::ROLE_REVIEWER, User::ROLE_ADMIN], true)) {
            abort(403);
        }

        abort_unless((int) $documento->postulacion_id === (int) $postulacion->getKey(), 404);

        return $documentStorage->response(
            $documento->storage_disk,
            $documento->path,
            'documento-'.$documento->id.'.pdf',
        );
    }
}
