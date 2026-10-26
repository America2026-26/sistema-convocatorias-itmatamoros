@extends('layouts.app')

@section('title', 'Convocatorias - Panel del Dictaminador')

@section('content')
    <section class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
        <div class="mb-6 flex flex-col justify-between gap-4 sm:flex-row sm:items-center">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Convocatorias</h1>
                <p class="mt-1 text-sm text-gray-600">Crea, edita, cierra o elimina convocatorias.</p>
            </div>
            <a href="{{ route('dictaminador.convocatorias.create') }}" class="rounded-lg bg-rose-900 px-4 py-2.5 text-center text-sm font-semibold text-white hover:bg-rose-800">Nueva convocatoria</a>
        </div>

        @if (session('status'))
            <p role="status" class="mb-4 rounded-lg border border-emerald-200 bg-emerald-50 p-3 text-sm text-emerald-800">{{ session('status') }}</p>
        @endif
        @if ($errors->any())
            <div role="alert" class="mb-4 rounded-lg border border-red-200 bg-red-50 p-3 text-sm text-red-800">
                @foreach ($errors->all() as $error)<p>{{ $error }}</p>@endforeach
            </div>
        @endif

        <div class="space-y-4">
            @forelse ($convocatorias as $convocatoria)
                <article class="rounded-xl border border-gray-200 bg-white p-5 shadow-sm">
                    <div class="flex flex-col justify-between gap-4 lg:flex-row lg:items-start">
                        <div>
                            <div class="mb-2 flex flex-wrap gap-2">
                                <span class="rounded-full bg-rose-100 px-2.5 py-1 text-xs font-semibold text-rose-900">{{ $convocatoria->tipo_personal }}</span>
                                <span class="rounded-full bg-gray-100 px-2.5 py-1 text-xs font-semibold text-gray-700">{{ $convocatoria->estatus }}</span>
                            </div>
                            <h2 class="text-lg font-bold text-gray-900">{{ $convocatoria->titulo }}</h2>
                            <p class="mt-1 text-sm text-gray-600">{{ $convocatoria->tipo_concurso }} · {{ $convocatoria->categoria_nivel }}</p>
                            <p class="mt-2 text-xs text-gray-500">Vigencia: {{ $convocatoria->fecha_inicio->format('d/m/Y') }} – {{ $convocatoria->fecha_fin->format('d/m/Y') }}</p>
                            <p class="mt-1 text-xs text-gray-500">{{ $convocatoria->postulaciones_count }} postulaciones · {{ $convocatoria->pendientes_count }} pendientes</p>
                        </div>
                        <div class="flex flex-wrap gap-3">
                            <a href="{{ route('dictaminador.postulantes.index', ['convocatoria' => $convocatoria->id]) }}" class="rounded-lg border border-gray-300 px-3 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50">Postulantes</a>
                            <a href="{{ route('dictaminador.convocatorias.edit', $convocatoria) }}" class="rounded-lg border border-gray-300 px-3 py-2 text-sm font-semibold text-gray-700 hover:bg-gray-50">Editar</a>
                            @if ($convocatoria->postulaciones_count === 0)
                                <form action="{{ route('dictaminador.convocatorias.destroy', $convocatoria) }}" method="POST" onsubmit="return confirm('¿Eliminar esta convocatoria?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="rounded-lg border border-red-200 px-3 py-2 text-sm font-semibold text-red-700 hover:bg-red-50">Eliminar</button>
                                </form>
                            @endif
                        </div>
                    </div>
                </article>
            @empty
                <p class="rounded-xl border border-gray-200 bg-white p-8 text-center text-sm text-gray-500">Todavía no hay convocatorias.</p>
            @endforelse
        </div>
        <div class="mt-5">{{ $convocatorias->links() }}</div>
    </section>
@endsection
