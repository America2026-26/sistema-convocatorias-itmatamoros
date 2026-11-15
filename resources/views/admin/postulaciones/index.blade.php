@extends('layouts.app')

@section('title', 'Expedientes de usuarios - Sistema de Convocatorias')

@section('content')
    <section class="mx-auto max-w-6xl space-y-6 px-4 py-8 sm:px-6 lg:px-8">
        <header class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <p class="text-xs font-semibold uppercase tracking-wide text-rose-900">Administración</p>
                <h1 class="mt-1 text-2xl font-bold text-gray-950">Expedientes de usuarios</h1>
                <p class="mt-1 text-sm text-gray-600">Consulta los expedientes por usuario y devuélvelos para correcciones antes del dictamen.</p>
            </div>
            <a href="{{ route('admin.dashboard') }}" class="text-sm font-semibold text-rose-900 hover:underline">&larr; Panel de administración</a>
        </header>

        @if (session('status'))
            <p role="status" class="rounded-lg border border-emerald-200 bg-emerald-50 p-3 text-sm text-emerald-800">{{ session('status') }}</p>
        @endif
        @if ($errors->any())
            <div role="alert" class="rounded-lg border border-red-200 bg-red-50 p-3 text-sm text-red-800">
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <div class="space-y-4">
            @forelse ($users as $user)
                <details class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">
                    <summary class="flex cursor-pointer list-none flex-col gap-3 px-5 py-4 transition hover:bg-gray-50 sm:flex-row sm:items-center sm:justify-between">
                        <div class="min-w-0">
                            <h2 class="truncate font-semibold text-gray-950">{{ $user->name }}</h2>
                            <p class="mt-1 truncate text-sm text-gray-600">{{ $user->email }}</p>
                        </div>
                        <span class="inline-flex w-fit shrink-0 rounded-full bg-rose-100 px-3 py-1 text-xs font-semibold text-rose-900">
                            {{ $user->postulaciones->count() }} {{ $user->postulaciones->count() === 1 ? 'expediente' : 'expedientes' }}
                        </span>
                    </summary>

                    <div class="space-y-4 border-t border-gray-100 bg-gray-50/70 p-4 sm:p-5">
                        @foreach ($user->postulaciones as $postulacion)
                            <article class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm sm:p-5">
                                <div class="flex flex-col justify-between gap-3 sm:flex-row sm:items-start">
                                    <div class="min-w-0">
                                        <span @class([
                                            'inline-flex rounded-full px-2.5 py-1 text-xs font-semibold',
                                            'bg-amber-100 text-amber-900' => $postulacion->estatus === \App\Models\Postulacion::STATUS_PENDING,
                                            'bg-violet-100 text-violet-900' => $postulacion->estatus === \App\Models\Postulacion::STATUS_RETURNED,
                                            'bg-emerald-100 text-emerald-900' => $postulacion->estatus === \App\Models\Postulacion::STATUS_APPROVED,
                                            'bg-red-100 text-red-900' => $postulacion->estatus === \App\Models\Postulacion::STATUS_REJECTED,
                                        ])>
                                            {{ match ($postulacion->estatus) {
                                                \App\Models\Postulacion::STATUS_PENDING => 'Pendiente',
                                                \App\Models\Postulacion::STATUS_RETURNED => 'Devuelta para edición',
                                                \App\Models\Postulacion::STATUS_APPROVED => 'Aprobada',
                                                \App\Models\Postulacion::STATUS_REJECTED => 'Rechazada',
                                                default => ucfirst($postulacion->estatus),
                                            } }}
                                        </span>
                                        <h3 class="mt-2 font-bold text-gray-950">{{ $postulacion->convocatoria->titulo }}</h3>
                                        <p class="mt-1 text-xs text-gray-500">Enviada el {{ $postulacion->created_at->format('d/m/Y H:i') }}</p>
                                    </div>
                                </div>

                                @php($perfil = data_get($postulacion->informacion, 'perfil', []))
                                <dl class="mt-4 grid grid-cols-1 gap-3 rounded-lg bg-slate-50 p-3 text-sm sm:grid-cols-3">
                                    <div><dt class="text-xs font-semibold text-gray-500">Correo</dt><dd class="mt-1 break-words text-gray-800">{{ $perfil['email'] ?? $user->email }}</dd></div>
                                    <div><dt class="text-xs font-semibold text-gray-500">CURP</dt><dd class="mt-1 text-gray-800">{{ $perfil['curp'] ?? 'No proporcionada' }}</dd></div>
                                    <div><dt class="text-xs font-semibold text-gray-500">RFC</dt><dd class="mt-1 text-gray-800">{{ $perfil['rfc'] ?? 'No proporcionado' }}</dd></div>
                                </dl>

                                @if (data_get($postulacion->informacion, 'texto'))
                                    <div class="mt-4 rounded-lg bg-slate-50 p-3">
                                        <h4 class="text-xs font-semibold uppercase tracking-wide text-gray-500">Información adicional</h4>
                                        <p class="mt-1 whitespace-pre-line text-sm text-gray-700">{{ data_get($postulacion->informacion, 'texto') }}</p>
                                    </div>
                                @endif

                                <div class="mt-4">
                                    <h4 class="text-sm font-semibold text-gray-800">Documentos enviados</h4>
                                    @if ($postulacion->documentos->isNotEmpty())
                                        <ul class="mt-2 divide-y divide-gray-100 rounded-lg border border-gray-100">
                                            @foreach ($postulacion->documentos as $documento)
                                                <li class="flex flex-col justify-between gap-2 px-3 py-2.5 sm:flex-row sm:items-center">
                                                    <span class="text-sm text-gray-700">{{ $documento->nombre }}</span>
                                                    <a href="{{ route('admin.postulaciones.documento-requerido', [$postulacion, $documento]) }}" target="_blank" rel="noopener noreferrer" class="text-sm font-semibold text-rose-900 hover:underline">Abrir PDF</a>
                                                </li>
                                            @endforeach
                                        </ul>
                                    @elseif ($postulacion->documento_path)
                                        <a href="{{ route('admin.postulaciones.document', $postulacion) }}" target="_blank" rel="noopener noreferrer" class="mt-2 inline-flex text-sm font-semibold text-rose-900 hover:underline">Abrir expediente PDF</a>
                                    @else
                                        <p class="mt-1 text-sm text-gray-500">No hay documentos registrados.</p>
                                    @endif
                                </div>

                                @if ($postulacion->estatus === \App\Models\Postulacion::STATUS_PENDING)
                                    <form action="{{ route('admin.postulaciones.return-for-editing', $postulacion) }}" method="POST" class="mt-4 border-t border-gray-100 pt-4" onsubmit="return confirm('¿Devolver este expediente al usuario para que pueda corregirlo?')">
                                        @csrf
                                        <button type="submit" class="rounded-lg border border-amber-300 bg-amber-50 px-4 py-2 text-sm font-semibold text-amber-900 transition hover:bg-amber-100">
                                            Devolver documentos para edición
                                        </button>
                                    </form>
                                @elseif ($postulacion->estatus === \App\Models\Postulacion::STATUS_RETURNED)
                                    <p class="mt-4 border-t border-gray-100 pt-4 text-sm text-violet-800">Esperando las correcciones del usuario.</p>
                                @endif
                            </article>
                        @endforeach
                    </div>
                </details>
            @empty
                <p class="rounded-xl border border-gray-200 bg-white p-8 text-center text-sm text-gray-500">Todavía no hay usuarios con expedientes enviados.</p>
            @endforelse
        </div>

        <div>{{ $users->links() }}</div>
    </section>
@endsection
