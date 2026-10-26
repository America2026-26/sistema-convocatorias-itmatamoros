<footer class="mt-10 border-t border-slate-800 bg-slate-900 py-6 text-slate-300">
    <div class="mx-auto flex max-w-7xl flex-col items-center justify-between gap-4 px-4 text-center sm:flex-row sm:px-6 sm:text-left lg:px-8">
        <p class="text-xs">
            &copy; {{ date('Y') }} Instituto Tecnológico de Matamoros. Sistema de Convocatorias.
        </p>
        <nav aria-label="Enlaces del sitio" class="flex flex-wrap justify-center gap-x-5 gap-y-2 text-xs">
            <a href="{{ url('/') }}" class="transition hover:text-white">Inicio</a>
            <a href="{{ url('/') }}#convocatorias" class="transition hover:text-white">Convocatorias</a>
            @auth
                <a href="{{ route('dashboard') }}" class="transition hover:text-white">Mi panel</a>
                @if (auth()->user()->isAdmin())
                    <a href="{{ route('admin.users.index') }}" class="transition hover:text-white">Cuentas</a>
                    <a href="{{ route('admin.audit-logs.index') }}" class="transition hover:text-white">Historial</a>
                @endif
            @else
                <a href="{{ route('login') }}" class="transition hover:text-white">Iniciar sesión</a>
                <a href="{{ route('register') }}" class="transition hover:text-white">Registro</a>
            @endauth
        </nav>
    </div>
</footer>
