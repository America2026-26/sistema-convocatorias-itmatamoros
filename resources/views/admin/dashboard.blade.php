@extends('layouts.app')

@section('title', 'Panel de Administración - Sistema de Convocatorias')

@section('content')
    <section class="mx-auto max-w-7xl space-y-6 px-4 py-8 sm:px-6 lg:px-8">
        <header class="flex flex-col gap-6 rounded-2xl bg-gradient-to-br from-rose-950 via-rose-900 to-slate-900 p-6 text-white shadow-sm sm:p-8 lg:flex-row lg:items-center lg:justify-between">
            <div class="max-w-2xl">
                <p class="text-xs font-semibold uppercase tracking-[0.18em] text-rose-200">Administración del sistema</p>
                <h1 class="mt-2 text-2xl font-bold sm:text-3xl">Panel de administración</h1>
                <p class="mt-2 text-sm leading-6 text-rose-100">
                    Supervisa las cuentas, las convocatorias y la actividad registrada en la plataforma.
                </p>
            </div>
            <nav aria-label="Acciones de administración" class="flex flex-col gap-3 sm:flex-row">
                <a href="{{ route('admin.users.index') }}" class="rounded-xl bg-white px-4 py-2.5 text-center text-sm font-semibold text-rose-950 transition hover:bg-rose-50">
                    Administrar cuentas
                </a>
                <a href="{{ route('admin.audit-logs.index') }}" class="rounded-xl border border-white/40 px-4 py-2.5 text-center text-sm font-semibold text-white transition hover:bg-white/10">
                    Historial de cambios
                </a>
            </nav>
        </header>

        <div class="grid gap-4 sm:grid-cols-2 xl:grid-cols-3">
            <article class="rounded-2xl bg-white border border-rose-200  p-5 shadow-sm">
                <p class="text-sm font-medium text-rose-950">Convocatorias creadas</p>
                <p class="mt-3 text-3xl font-bold text-rose-950">{{ number_format($convocatoriasCount) }}</p>
                <p class="mt-1 text-xs text-rose-900">Registradas en el sistema</p>
            </article>
            <article class="rounded-2xl border border-sky-200 bg-white p-5 shadow-sm">
                <p class="text-sm font-medium text-sky-900">Usuarios en el sistema</p>
                <p class="mt-3 text-3xl font-bold text-sky-950">{{ number_format($usersCount) }}</p>
                <p class="mt-1 text-xs text-sky-800">Cuentas activas de todos los roles</p>
            </article>
            <article class="rounded-2xl border border-amber-200 bg-white p-5 shadow-sm">
                <p class="text-sm font-medium text-amber-950">Postulaciones pendientes</p>
                <p class="mt-3 text-3xl font-bold text-amber-950">{{ number_format($pendingCount) }}</p>
                <p class="mt-1 text-xs text-amber-900">En espera de dictamen</p>
            </article>
        </div>

        <section class="space-y-4 rounded-2xl border border-gray-200 bg-white p-5 shadow-sm sm:p-6" aria-labelledby="admin-tools-heading">
            <div>
                <h2 id="admin-tools-heading" class="text-lg font-bold text-gray-950">Herramientas de administración</h2>
                <p class="mt-1 text-sm text-gray-600">Accede a las tareas principales de control del sistema.</p>
            </div>

            <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-3">
                <a href="{{ route('admin.users.index') }}" class="group rounded-xl border border-gray-200 p-5 transition hover:-translate-y-0.5 hover:border-rose-300 hover:shadow-sm">
                    <span class="inline-flex rounded-full bg-rose-100 px-2.5 py-1 text-xs font-semibold text-rose-900">Cuentas</span>
                    <h3 class="mt-3 font-bold text-gray-950 group-hover:text-rose-900">Administrar cuentas</h3>
                    <p class="mt-1 text-sm leading-6 text-gray-600">Crear, editar y gestionar las cuentas de usuarios, dictaminadores y administradores.</p>
                </a>
                <a href="{{ route('admin.postulaciones.index') }}" class="group rounded-xl border border-gray-200 p-5 transition hover:-translate-y-0.5 hover:border-amber-300 hover:shadow-sm">
                    <span class="inline-flex rounded-full bg-amber-100 px-2.5 py-1 text-xs font-semibold text-amber-900">Expedientes</span>
                    <h3 class="mt-3 font-bold text-gray-950 group-hover:text-amber-900">Revisar expedientes</h3>
                    <p class="mt-1 text-sm leading-6 text-gray-600">Consulta documentos por usuario y devuélvelos para correcciones antes del dictamen.</p>
                </a>
                <a href="{{ route('admin.audit-logs.index') }}" class="group rounded-xl border border-gray-200 p-5 transition hover:-translate-y-0.5 hover:border-rose-300 hover:shadow-sm">
                    <span class="inline-flex rounded-full bg-slate-100 px-2.5 py-1 text-xs font-semibold text-slate-700">Actividad</span>
                    <h3 class="mt-3 font-bold text-gray-950 group-hover:text-rose-900">Historial de cambios</h3>
                    <p class="mt-1 text-sm leading-6 text-gray-600">Consultar los eventos del sistema, las acciones realizadas y sus responsables.</p>
                </a>
            </div>
        </section>
    </section>
@endsection
