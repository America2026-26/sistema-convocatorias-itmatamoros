@extends('layouts.app')

@section('title', 'Postulantes - Panel del Dictaminador')

@section('content')
    <section class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-900">Postulantes</h1>
            <p class="mt-1 text-sm text-gray-600">Consulta las solicitudes organizadas por tipo de personal y convocatoria.</p>
        </div>

        <form method="GET" class="mb-5 flex flex-col gap-3 sm:flex-row">
            <label for="tipo_personal" class="sr-only">Filtrar por tipo de personal</label>
            <select id="tipo_personal" name="tipo_personal" class="rounded-lg border border-gray-300 px-3 py-2 text-sm">
                <option value="">Todos los tipos</option>
                @foreach ($tiposPersonal as $tipo)
                    <option value="{{ $tipo }}" @selected($tipoSeleccionado === $tipo)>{{ $tipo }}</option>
                @endforeach
            </select>
            <button class="rounded-lg bg-rose-900 px-4 py-2 text-sm font-semibold text-white hover:bg-rose-800">Filtrar</button>
            @if ($tipoSeleccionado)
                <a href="{{ route('dictaminador.postulantes.index') }}" class="rounded-lg border border-gray-300 px-4 py-2 text-center text-sm font-semibold text-gray-700">Limpiar</a>
            @endif
        </form>

        @if ($postulaciones)
            <a href="{{ route('dictaminador.postulantes.index', ['tipo_personal' => $tipoSeleccionado]) }}" class="mb-4 inline-block text-sm font-semibold text-rose-900 hover:underline">&larr; Todas las convocatorias</a>
            <div class="overflow-x-auto rounded-xl border border-gray-200 bg-white shadow-sm">
                <table class="w-full min-w-[700px] text-left text-sm">
                    <thead class="bg-gray-50 text-xs uppercase text-gray-600">
                        <tr><th scope="col" class="px-5 py-3">Postulante</th><th scope="col" class="px-5 py-3">Tipo</th><th scope="col" class="px-5 py-3">Fecha</th><th scope="col" class="px-5 py-3">Estatus</th><th scope="col" class="px-5 py-3">Expediente</th></tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @forelse ($postulaciones as $postulacion)
                            <tr>
                                <td class="px-5 py-4 font-medium text-gray-900">{{ $postulacion->user->name }}</td>
                                <td class="px-5 py-4 text-gray-700">{{ $postulacion->user->tipo_usuario ?? '—' }}</td>
                                <td class="px-5 py-4 text-gray-700">{{ $postulacion->created_at->format('d/m/Y') }}</td>
                                <td class="px-5 py-4 text-gray-700">{{ ucfirst($postulacion->estatus) }}</td>
                                <td class="px-5 py-4"><a href="{{ route('dictaminador.postulantes.show', $postulacion) }}" class="font-semibold text-rose-900 hover:underline">Revisar expediente</a></td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="px-5 py-8 text-center text-gray-500">Esta convocatoria no tiene postulantes.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-4">{{ $postulaciones->links() }}</div>
        @else
            <div class="space-y-4">
                @forelse ($convocatorias as $convocatoria)
                    <article class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
                        <div class="flex flex-col justify-between gap-3 sm:flex-row sm:items-center">
                            <div>
                                <span class="rounded-full bg-rose-100 px-2.5 py-1 text-xs font-semibold text-rose-900">{{ $convocatoria->tipo_personal }}</span>
                                <h2 class="mt-2 font-bold text-gray-900">{{ $convocatoria->titulo }}</h2>
                                <p class="mt-1 text-sm text-gray-600">{{ $convocatoria->postulaciones_count }} postulante(s) · {{ $convocatoria->pendientes_count }} pendiente(s)</p>
                            </div>
                            <a href="{{ route('dictaminador.postulantes.index', ['convocatoria' => $convocatoria->id, 'tipo_personal' => $tipoSeleccionado]) }}" class="rounded-lg border border-gray-300 px-4 py-2 text-center text-sm font-semibold text-gray-700 hover:bg-gray-50">Ver postulantes</a>
                        </div>
                    </article>
                @empty
                    <p class="rounded-xl border border-gray-200 bg-white p-8 text-center text-sm text-gray-500">Todavía no hay postulaciones para revisar.</p>
                @endforelse
            </div>
            <div class="mt-4">{{ $convocatorias->links() }}</div>
        @endif
    </section>
@endsection
