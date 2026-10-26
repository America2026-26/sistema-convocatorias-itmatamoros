<?php

namespace App\Http\Controllers\Dictaminador;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Convocatoria;
use App\Models\Postulacion;
use App\Notifications\PostulacionActualizada;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class PostulanteController extends Controller
{
    public function index(Request $request): View
    {
        $convocatoriaId = $request->integer('convocatoria') ?: null;
        $convocatorias = Convocatoria::query()
            ->withCount([
                'postulaciones',
                'postulaciones as pendientes_count' => fn ($query) => $query
                    ->where('estatus', Postulacion::STATUS_PENDING),
            ])
            ->whereHas('postulaciones')
            ->when($convocatoriaId, fn ($query) => $query->whereKey($convocatoriaId))
            ->when($request->filled('tipo_personal'), fn ($query) => $query
                ->where('tipo_personal', $request->string('tipo_personal')->toString()))
            ->orderBy('tipo_personal')
            ->orderByDesc('created_at')
            ->paginate(15)
            ->withQueryString();

        return view('dictaminador.postulantes.index', [
            'convocatorias' => $convocatorias,
            'tiposPersonal' => Convocatoria::query()
                ->whereHas('postulaciones')
                ->distinct()
                ->orderBy('tipo_personal')
                ->pluck('tipo_personal'),
            'tipoSeleccionado' => $request->string('tipo_personal')->toString(),
            'postulaciones' => $convocatoriaId
                ? Postulacion::query()
                    ->with(['user:id,name,email,tipo_usuario', 'convocatoria:id,tipo_personal'])
                    ->where('convocatoria_id', $convocatoriaId)
                    ->when($request->filled('tipo_personal'), fn ($query) => $query
                        ->whereHas('convocatoria', fn ($convocatoriaQuery) => $convocatoriaQuery
                            ->where('tipo_personal', $request->string('tipo_personal')->toString())))
                    ->orderBy('estatus')
                    ->orderByDesc('created_at')
                    ->paginate(20)
                    ->withQueryString()
                : null,
        ]);
    }

    public function show(Postulacion $postulacion): View
    {
        return view('dictaminador.postulantes.show', [
            'postulacion' => $postulacion->load(['user', 'convocatoria', 'revisor']),
        ]);
    }

    public function review(Request $request, Postulacion $postulacion): RedirectResponse
    {
        $data = $request->validate([
            'estatus' => ['required', Rule::in([
                Postulacion::STATUS_APPROVED,
                Postulacion::STATUS_REJECTED,
            ])],
            'comentarios_revision' => [
                Rule::requiredIf($request->input('estatus') === Postulacion::STATUS_REJECTED),
                'nullable',
                'string',
                'max:5000',
            ],
        ]);
        $actor = $request->user();

        $postulacion = DB::transaction(function () use ($data, $postulacion, $actor): Postulacion {
            $postulacion = Postulacion::query()->lockForUpdate()->findOrFail($postulacion->id);

            if ($postulacion->estatus !== Postulacion::STATUS_PENDING) {
                throw ValidationException::withMessages([
                    'estatus' => 'Esta postulación ya fue dictaminada.',
                ]);
            }

            $postulacion->update([
                'estatus' => $data['estatus'],
                'revisado_por' => $actor->id,
                'revisado_en' => now(),
                'comentarios_revision' => $data['comentarios_revision'] ?? null,
            ]);

            AuditLog::record('postulacion.reviewed', $actor, $postulacion->user, [
                'postulacion_id' => $postulacion->id,
                'convocatoria_id' => $postulacion->convocatoria_id,
                'estatus' => $postulacion->estatus,
                'comentarios_revision' => $postulacion->comentarios_revision,
            ]);

            $applicant = $postulacion->user;
            abort_unless($applicant, 404);

            $applicant->notify(new PostulacionActualizada(
                $postulacion->load('convocatoria'),
                $postulacion->estatus === Postulacion::STATUS_APPROVED
                    ? 'Tu postulación a '.$postulacion->convocatoria->titulo.' fue aprobada.'
                    : 'Tu postulación a '.$postulacion->convocatoria->titulo.' fue rechazada. Revisa las observaciones.',
            ));

            return $postulacion;
        });

        return redirect()->route('dictaminador.postulantes.show', $postulacion)
            ->with('status', 'El dictamen fue guardado y se notificó al usuario.');
    }

    public function document(Postulacion $postulacion): BinaryFileResponse
    {
        abort_unless($postulacion->documento_path, 404);

        $disk = Storage::disk('local');
        abort_unless($disk->exists($postulacion->documento_path), 404);

        return response()->file($disk->path($postulacion->documento_path), [
            'Content-Type' => 'application/pdf',
            'X-Content-Type-Options' => 'nosniff',
            'Content-Disposition' => 'inline; filename="expediente-'.$postulacion->id.'.pdf"',
        ]);
    }
}
