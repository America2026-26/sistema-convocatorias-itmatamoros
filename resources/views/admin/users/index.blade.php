@extends('layouts.admin')

@section('content')
<div class="bg-white p-8 rounded-[2rem] border border-slate-200 shadow-xl shadow-slate-200/50">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h3 class="font-bold text-xl text-slate-800">Lista de Cuentas y Usuarios</h3>
            <p class="text-slate-500 text-sm">Administra los accesos de aspirantes, dictaminadores y administradores.</p>
        </div>
        <a href="{{ route('admin.users.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-full font-bold shadow-lg shadow-blue-500/30 transition-all flex items-center gap-2">
            <i class="fas fa-plus text-xs"></i> Crear Usuario
        </a>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-xl mb-4">
            {{ session('success') }}
        </div>
    @endif

    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="text-[12px] uppercase text-slate-400 border-b border-slate-100">
                    <th class="py-4 px-4 font-bold tracking-widest">ID</th>
                    <th class="py-4 px-4 font-bold tracking-widest">Nombre</th>
                    <th class="py-4 px-4 font-bold tracking-widest">Correo Electrónico</th>
                    <th class="py-4 px-4 font-bold tracking-widest">Tipo / Rol</th>
                    <th class="py-4 px-4 font-bold tracking-widest text-right">Acciones</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @foreach ($usuarios as $usuario)
                    <tr class="group hover:bg-slate-50/50 transition-colors">
                        <td class="py-4 px-4 font-bold text-slate-700">#{{ $usuario->id }}</td>
                        <td class="py-4 px-4 text-slate-700 font-semibold">{{ $usuario->name ?? $usuario->nombre }}</td>
                        <td class="py-4 px-4 text-slate-500 font-mono text-xs">{{ $usuario->email }}</td>
                        <td class="py-4 px-4">
                            <span class="px-3 py-1 rounded-full text-xs font-bold 
                                @if($usuario->role === 'admin') bg-purple-100 text-purple-700 
                                @elseif($usuario->role === 'reviewer') bg-amber-100 text-amber-700 
                                @else bg-blue-100 text-blue-700 @endif">
                                {{ strtoupper($usuario->role ?? 'USUARIO') }}
                            </span>
                        </td>
                        <td class="py-4 px-4 text-right space-x-2">
                            <a href="{{ route('admin.users.edit', $usuario->id) }}" class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1.5 rounded-lg text-xs font-bold transition-all shadow-sm">
                                Editar
                            </a>
                            <form action="{{ route('admin.users.destroy', $usuario->id) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-3 py-1.5 rounded-lg text-xs font-bold transition-all shadow-sm" onclick="return confirm('¿Estás seguro de eliminar este usuario?')">
                                    Eliminar
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection