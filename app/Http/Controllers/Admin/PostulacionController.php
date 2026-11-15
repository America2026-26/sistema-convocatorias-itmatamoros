<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Postulacion;
use App\Models\User;
use App\Notifications\PostulacionActualizada;
use App\Services\OneDriveDocumentStorage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\Response;

class PostulacionController extends Controller
{
    public function index(): View
    {
        return view('admin.postulaciones.index', [
            'users' => User::query()
                ->where('role', User::ROLE_USER)
                ->whereHas('postulaciones')
                ->with([
                    'postulaciones' => fn ($query) => $query
                        ->with(['convocatoria:id,titulo', 'documentos'])
                        ->latest(),
                ])
                ->orderBy('name')
                ->paginate(20),
        ]);
    }

    public function returnForEditing(Request $request, Postulacion $postulacion): RedirectResponse
    {
        $actor = $request->user();

        $postulacion = DB::transaction(function () use ($actor, $postulacion): Postulacion {
            $postulacion = Postulacion::query()
                ->lockForUpdate()
                ->findOrFail($postulacion->id);

            if ($postulacion->estatus !== Postulacion::STATUS_PENDING) {
                throw ValidationException::withMessages([
                    'postulacion' => 'Solo se pueden devolver expedientes pendientes de dictamen.',
                ]);
            }

            $applicant = $postulacion->user;
            abort_unless($applicant, 404);

            $postulacion->update([
                'estatus' => Postulacion::STATUS_RETURNED,
                'revisado_por' => $actor->id,
                'revisado_en' => now(),
                'comentarios_revision' => 'El administrador devolvió el expediente para que puedas corregirlo.',
            ]);

            AuditLog::record('postulacion.returned_for_editing', $actor, $applicant, [
                'postulacion_id' => $postulacion->id,
                'convocatoria_id' => $postulacion->convocatoria_id,
            ]);

            $applicant->notify(new PostulacionActualizada(
                $postulacion->load('convocatoria'),
                'El administrador devolvió tu expediente para que puedas corregir tus documentos o información.',
            ));

            return $postulacion;
        });

        return redirect()->route('admin.postulaciones.index')
            ->with('status', 'El expediente fue devuelto al usuario para su edición.');
    }

    public function document(
        Request $request,
        Postulacion $postulacion,
        OneDriveDocumentStorage $documentStorage,
    ): Response {
        abort_unless($request->user()->role === User::ROLE_ADMIN, 403);
        abort_unless($postulacion->documento_path, 404);

        return $documentStorage->response(
            $postulacion->documento_storage_disk,
            $postulacion->documento_path,
            'expediente-'.$postulacion->id.'.pdf',
        );
    }
}
