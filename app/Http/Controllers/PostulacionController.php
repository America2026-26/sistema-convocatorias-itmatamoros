<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\Convocatoria;
use App\Models\Postulacion;
use App\Models\PostulacionDocumento;
use App\Models\User;
use App\Notifications\NuevaPostulacion;
use App\Services\OneDriveDocumentStorage;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use RuntimeException;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class PostulacionController extends Controller
{
    public function index(Request $request): View
    {
        return view('postulaciones.index', [
            'postulaciones' => Postulacion::query()
                ->with('convocatoria:id,titulo,tipo_personal,categoria_nivel')
                ->where('user_id', $request->user()->id)
                ->latest()
                ->paginate(15),
        ]);
    }

    public function show(Request $request, Postulacion $postulacion): View
    {
        abort_unless((int) $postulacion->user_id === (int) $request->user()->getAuthIdentifier(), 404);

        return view('postulaciones.show', [
            'postulacion' => $postulacion->load(['convocatoria', 'documentos']),
        ]);
    }

    public function edit(Request $request, Postulacion $postulacion): View
    {
        abort_unless($request->user()->role === User::ROLE_USER, 403);
        abort_unless((int) $postulacion->user_id === (int) $request->user()->getAuthIdentifier(), 404);
        abort_unless($postulacion->estatus === Postulacion::STATUS_RETURNED, 403);

        $postulacion->load(['convocatoria', 'documentos']);

        return view('postulaciones.edit', [
            'postulacion' => $postulacion,
            'documentos' => $postulacion->convocatoria->applicantDocumentRequirements(),
        ]);
    }

    public function update(
        Request $request,
        Postulacion $postulacion,
        OneDriveDocumentStorage $documentStorage,
    ): RedirectResponse {
        abort_unless($request->user()->role === User::ROLE_USER, 403);
        abort_unless((int) $postulacion->user_id === (int) $request->user()->getAuthIdentifier(), 404);
        abort_unless($postulacion->estatus === Postulacion::STATUS_RETURNED, 403);

        $requirements = $postulacion->convocatoria->applicantDocumentRequirements();
        $rules = [
            'informacion' => ['nullable', 'string', 'max:10000'],
            'documentos' => ['nullable', 'array:'.implode(',', array_keys($requirements))],
        ];

        foreach (array_keys($requirements) as $key) {
            $rules['documentos.'.$key] = ['nullable', 'file', 'mimes:pdf', 'extensions:pdf', 'max:10240'];
        }

        $data = $request->validate($rules);
        $replacementKeys = array_values(array_filter(
            array_keys($requirements),
            fn (string $key): bool => ($data['documentos'][$key] ?? null) instanceof UploadedFile,
        ));
        $information = $data['informacion'] ?? null;
        $storedDocuments = [];
        $oldDocuments = [];
        $uploadedPaths = [];

        try {
            foreach (array_keys($requirements) as $key) {
                $file = $data['documentos'][$key] ?? null;

                if (! $file instanceof UploadedFile) {
                    continue;
                }

                $path = $this->storeDocument(
                    $file,
                    'postulacion-'.$postulacion->id.'-'.$key.'-'.str()->uuid().'.pdf',
                );
                $uploadedPaths[$key] = $path;
                $storedDocuments[] = [
                    'disk' => PostulacionDocumento::STORAGE_DISK_LOCAL,
                    'path' => $path,
                ];
            }

            $postulacion = DB::transaction(function () use (
                $request,
                $postulacion,
                $requirements,
                $replacementKeys,
                $information,
                $uploadedPaths,
                &$oldDocuments,
            ): Postulacion {
                $postulacion = Postulacion::query()
                    ->lockForUpdate()
                    ->findOrFail($postulacion->id);

                abort_unless((int) $postulacion->user_id === (int) $request->user()->getAuthIdentifier(), 404);

                if ($postulacion->estatus !== Postulacion::STATUS_RETURNED) {
                    throw ValidationException::withMessages([
                        'expediente' => 'Este expediente ya no está habilitado para edición.',
                    ]);
                }

                if ($replacementKeys === [] && $information === data_get($postulacion->informacion, 'texto')) {
                    throw ValidationException::withMessages([
                        'expediente' => 'Actualiza al menos un documento o la información adicional antes de reenviar.',
                    ]);
                }

                $informationUpdated = $information !== data_get($postulacion->informacion, 'texto');
                $postulacion->load('documentos');
                $replacedDocumentKeys = [];

                foreach (array_keys($requirements) as $key) {
                    $path = $uploadedPaths[$key] ?? null;

                    if (! is_string($path)) {
                        continue;
                    }

                    $documento = $postulacion->documentos->firstWhere('clave', $key);

                    if ($documento) {
                        $oldDocuments[] = [
                            'disk' => $documento->storage_disk,
                            'path' => $documento->path,
                        ];
                        $documento->update([
                            'path' => $path,
                            'storage_disk' => PostulacionDocumento::STORAGE_DISK_LOCAL,
                        ]);
                    } else {
                        $postulacion->documentos()->create([
                            'clave' => $key,
                            'nombre' => $requirement['label'],
                            'path' => $path,
                            'storage_disk' => PostulacionDocumento::STORAGE_DISK_LOCAL,
                        ]);
                    }

                    $replacedDocumentKeys[] = $key;
                }

                $postulacion->update([
                    'estatus' => Postulacion::STATUS_PENDING,
                    'informacion' => [
                        'texto' => $information,
                        'perfil' => data_get($postulacion->informacion, 'perfil', []),
                    ],
                    'revisado_por' => null,
                    'revisado_en' => null,
                    'comentarios_revision' => null,
                ]);

                AuditLog::record('postulacion.resubmitted', $request->user(), $request->user(), [
                    'postulacion_id' => $postulacion->id,
                    'convocatoria_id' => $postulacion->convocatoria_id,
                    'replaced_documents' => $replacedDocumentKeys,
                    'information_updated' => $informationUpdated,
                ]);

                $postulacion->load(['user', 'convocatoria']);
                $message = $postulacion->user->name.' actualizó su expediente de '
                    .$postulacion->convocatoria->titulo.' para una nueva revisión.';

                User::query()
                    ->where('role', User::ROLE_REVIEWER)
                    ->get()
                    ->each(fn (User $reviewer) => $reviewer->notify(new NuevaPostulacion(
                        $postulacion,
                        'Expediente actualizado',
                        $message,
                    )));

                return $postulacion;
            });
        } catch (Throwable $exception) {
            $this->deleteStoredDocuments($storedDocuments, $documentStorage);

            throw $exception;
        }

        $this->deleteStoredDocuments($oldDocuments, $documentStorage);

        return redirect()->route('postulaciones.show', $postulacion)
            ->with('status', 'Tu expediente fue actualizado y enviado para revisión.');
    }

    public function create(Request $request, Convocatoria $convocatoria): View|RedirectResponse
    {
        abort_unless($request->user()->role === User::ROLE_USER, 403);

        if (! $convocatoria->isOpen()) {
            return redirect()->route('convocatorias.show', $convocatoria)
                ->withErrors(['convocatoria' => 'Esta convocatoria no está recibiendo postulaciones.']);
        }

        $postulacionExistente = $convocatoria->postulaciones()
            ->where('user_id', $request->user()->id)
            ->first();

        if ($postulacionExistente) {
            return redirect()->route('postulaciones.show', $postulacionExistente);
        }

        return view('postulaciones.create', [
            'convocatoria' => $convocatoria,
            'documentos' => $convocatoria->applicantDocumentRequirements(),
        ]);
    }

    public function store(
        Request $request,
        Convocatoria $convocatoria,
        OneDriveDocumentStorage $documentStorage,
    ): RedirectResponse {
        abort_unless($request->user()->role === User::ROLE_USER, 403);

        if (! $convocatoria->isOpen()) {
            throw ValidationException::withMessages([
                'convocatoria' => 'Esta convocatoria no se encuentra abierta para recibir postulaciones.',
            ]);
        }

        if (Postulacion::query()
            ->where('convocatoria_id', $convocatoria->id)
            ->where('user_id', $request->user()->id)
            ->exists()) {
            throw ValidationException::withMessages([
                'convocatoria' => 'Ya enviaste una postulación a esta convocatoria.',
            ]);
        }

        $requirements = $convocatoria->applicantDocumentRequirements();
        $rules = [
            'informacion' => ['nullable', 'string', 'max:10000'],
            'documentos' => ['required', 'array:'.implode(',', array_keys($requirements))],
        ];

        foreach (array_keys($requirements) as $key) {
            $rules['documentos.'.$key] = ['required', 'file', 'mimes:pdf', 'extensions:pdf', 'max:10240'];
        }

        $data = $request->validate($rules);
        $storedDocuments = [];
        $uploadedDocuments = [];
        $information = $data['informacion'] ?? null;

        try {
            foreach (array_keys($requirements) as $key) {
                $path = $this->storeDocument(
                    $data['documentos'][$key],
                    'postulacion-'.$convocatoria->id.'-'.$request->user()->id.'-'.$key.'-'.str()->uuid().'.pdf',
                );
                $uploadedDocuments[$key] = $path;
                $storedDocuments[] = [
                    'disk' => PostulacionDocumento::STORAGE_DISK_LOCAL,
                    'path' => $path,
                ];
            }

            $postulacion = DB::transaction(function () use (
                $request,
                $convocatoria,
                $requirements,
                $uploadedDocuments,
                $information,
            ): Postulacion {
                $convocatoria = Convocatoria::query()
                    ->lockForUpdate()
                    ->findOrFail($convocatoria->id);

                if (! $convocatoria->isOpen()) {
                    throw ValidationException::withMessages([
                        'convocatoria' => 'Esta convocatoria ya no está recibiendo postulaciones.',
                    ]);
                }

                if (Postulacion::query()
                    ->where('convocatoria_id', $convocatoria->id)
                    ->where('user_id', $request->user()->id)
                    ->exists()) {
                    throw ValidationException::withMessages([
                        'convocatoria' => 'Ya enviaste una postulación a esta convocatoria.',
                    ]);
                }

                $postulacion = Postulacion::create([
                    'convocatoria_id' => $convocatoria->id,
                    'user_id' => $request->user()->id,
                    'estatus' => Postulacion::STATUS_PENDING,
                    'informacion' => [
                        'texto' => $information,
                        'perfil' => $request->user()->only([
                            'nombre',
                            'apellido_paterno',
                            'apellido_materno',
                            'curp',
                            'rfc',
                            'email',
                            'tipo_usuario',
                        ]),
                    ],
                ]);

                foreach ($requirements as $key => $requirement) {
                    $postulacion->documentos()->create([
                        'clave' => $key,
                        'nombre' => $requirement['label'],
                        'path' => $uploadedDocuments[$key],
                        'storage_disk' => PostulacionDocumento::STORAGE_DISK_LOCAL,
                    ]);
                }

                AuditLog::record('postulacion.created', $request->user(), $request->user(), [
                    'postulacion_id' => $postulacion->id,
                    'convocatoria_id' => $convocatoria->id,
                    'convocatoria' => $convocatoria->titulo,
                ]);

                User::query()
                    ->where('role', User::ROLE_REVIEWER)
                    ->get()
                    ->each(fn (User $reviewer) => $reviewer->notify(new NuevaPostulacion($postulacion)));

                return $postulacion;
            });
        } catch (UniqueConstraintViolationException $exception) {
            $this->deleteStoredDocuments($storedDocuments, $documentStorage);

            throw ValidationException::withMessages([
                'convocatoria' => 'Ya enviaste una postulación a esta convocatoria.',
            ]);
        } catch (Throwable $exception) {
            $this->deleteStoredDocuments($storedDocuments, $documentStorage);

            throw $exception;
        }

        return redirect()->route('dashboard')
            ->with('status', 'Tu postulación fue enviada.');
    }

    /**
     * @param  list<array{disk: string, path: string}>  $documents
     */
    private function deleteStoredDocuments(
        array $documents,
        OneDriveDocumentStorage $documentStorage,
    ): void {
        foreach ($documents as $document) {
            try {
                if (! $documentStorage->delete($document['disk'], $document['path'])) {
                    Log::warning('No se pudo eliminar un documento de postulación.', $document);
                }
            } catch (Throwable $exception) {
                Log::warning('No se pudo eliminar un documento de postulación.', [
                    ...$document,
                    'exception' => $exception->getMessage(),
                ]);
            }
        }
    }

    private function storeDocument(UploadedFile $file, string $filename): string
    {
        $path = $file->storeAs('postulaciones', $filename, PostulacionDocumento::STORAGE_DISK_LOCAL);

        if (! is_string($path) || $path === '') {
            throw new RuntimeException('No se pudo guardar el documento de postulación.');
        }

        return $path;
    }

    public function document(
        Request $request,
        Postulacion $postulacion,
        OneDriveDocumentStorage $documentStorage,
    ): Response {
        abort_unless((int) $postulacion->user_id === (int) $request->user()->getAuthIdentifier(), 404);
        abort_unless($postulacion->documento_path, 404);

        return $documentStorage->response(
            $postulacion->documento_storage_disk,
            $postulacion->documento_path,
            'expediente-'.$postulacion->id.'.pdf',
        );
    }
}
