<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\Convocatoria;
use App\Models\Postulacion;
use App\Models\User;
use App\Notifications\NuevaPostulacion;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
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
        abort_unless($postulacion->user_id === $request->user()->id, 404);

        return view('postulaciones.show', [
            'postulacion' => $postulacion->load(['convocatoria', 'documentos']),
        ]);
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

    public function store(Request $request, Convocatoria $convocatoria): RedirectResponse
    {
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
        $storedPaths = [];

        try {
            $postulacion = DB::transaction(function () use ($request, $convocatoria, $data, $requirements, &$storedPaths): Postulacion {
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
                        'texto' => $data['informacion'] ?? null,
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
                    $path = $data['documentos'][$key]->store('postulaciones/'.$postulacion->id, 'local');

                    if (! is_string($path) || $path === '') {
                        throw new \RuntimeException('No se pudo guardar el documento '.$requirement['label'].'.');
                    }

                    $storedPaths[] = $path;
                    $postulacion->documentos()->create([
                        'clave' => $key,
                        'nombre' => $requirement['label'],
                        'path' => $path,
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
            $this->deleteStoredDocuments($storedPaths);

            throw ValidationException::withMessages([
                'convocatoria' => 'Ya enviaste una postulación a esta convocatoria.',
            ]);
        } catch (Throwable $exception) {
            $this->deleteStoredDocuments($storedPaths);

            throw $exception;
        }

        return redirect()->route('postulaciones.show', $postulacion)
            ->with('status', 'Tu postulación fue enviada.');
    }

    /**
     * @param  list<string>  $paths
     */
    private function deleteStoredDocuments(array $paths): void
    {
        if ($paths !== []) {
            Storage::disk('local')->delete($paths);
        }
    }

    public function document(Request $request, Postulacion $postulacion): BinaryFileResponse
    {
        abort_unless($postulacion->user_id === $request->user()->id, 404);
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
