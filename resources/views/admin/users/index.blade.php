@extends('layouts.app')

@section('title', 'Administrar cuentas - Sistema de Convocatorias')

@section('content')
    <section class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
        <div class="mb-6 flex flex-col justify-between gap-4 sm:flex-row sm:items-center">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Cuentas de usuario</h1>
                <p class="mt-1 text-sm text-gray-600">Administra los usuarios, dictaminadores y administradores.</p>
            </div>
            <a href="{{ route('admin.users.create') }}"
                class="inline-flex items-center justify-center rounded-lg bg-rose-900 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-rose-800">
                Crear cuenta
            </a>
        </div>

        @if (session('status'))
            <p role="status" class="mb-4 rounded-lg border border-emerald-200 bg-emerald-50 p-3 text-sm text-emerald-800">{{ session('status') }}</p>
        @endif
        @if (session('error'))
            <p role="alert" class="mb-4 rounded-lg border border-red-200 bg-red-50 p-3 text-sm text-red-800">{{ session('error') }}</p>
        @endif
        @if ($errors->any())
            <div role="alert" class="mb-4 rounded-lg border border-red-200 bg-red-50 p-3 text-sm text-red-800">
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <div class="overflow-x-auto rounded-xl border border-gray-200 bg-white shadow-sm">
            <table class="w-full min-w-[760px] text-left text-sm">
                <thead class="bg-gray-50 text-xs uppercase tracking-wide text-gray-600">
                    <tr>
                        <th scope="col" class="px-5 py-3">Nombre</th>
                        <th scope="col" class="px-5 py-3">Correo</th>
                        <th scope="col" class="px-5 py-3">Rol</th>
                        <th scope="col" class="px-5 py-3">Tipo de perfil</th>
                        <th scope="col" class="px-5 py-3 text-right">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse ($users as $user)
                        <tr>
                            <td class="px-5 py-4 font-medium text-gray-900">{{ $user->name }}</td>
                            <td class="px-5 py-4 text-gray-700">{{ $user->email }}</td>
                            <td class="px-5 py-4 text-gray-700">{{ ucfirst($user->role) }}</td>
                            <td class="px-5 py-4 text-gray-700">{{ $user->tipo_usuario ?? '—' }}</td>
                            <td class="px-5 py-4">
                                <div class="flex justify-end gap-3">
                                    <a href="{{ route('admin.users.edit', $user) }}"
                                        class="font-semibold text-rose-900 hover:underline">Editar</a>
                                    <form action="{{ route('admin.users.destroy', $user) }}" method="POST"
                                        onsubmit="return confirm('¿Eliminar la cuenta de {{ $user->name }}?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="font-semibold text-red-700 hover:underline">Eliminar</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-5 py-8 text-center text-gray-500">Todavía no hay cuentas registradas.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">{{ $users->links() }}</div>
    </section>
@endsection
