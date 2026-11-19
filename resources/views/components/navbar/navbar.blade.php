<header class="sticky top-0 z-50 brand-surface shadow-lg">
    <div class="mx-auto flex max-w-7xl items-center justify-between gap-3 px-4 py-3 sm:px-6 lg:px-8">
        <a href="{{ url('/') }}" class="flex min-w-0 items-center gap-3">
            <img src="https://dev.matamoros.tecnm.mx/tecnm_itm/sitio/drive/logo-del-tec-guindo-289x300.png"
                alt="Logotipo del Instituto Tecnológico de Matamoros"
                class="h-11 w-auto shrink-0 object-contain sm:h-14">
            <span class="min-w-0">
                <span class="block text-sm font-bold leading-tight sm:text-base">Sistema de Convocatorias</span>
                <span class="hidden text-xs text-white/80 sm:block">Instituto Tecnológico de Matamoros</span>
            </span>
        </a>

        <div class="flex items-center gap-2">
            @auth
                <nav aria-label="Navegación principal" class="hidden items-center gap-1 md:flex">
                    <a href="{{ route('dashboard') }}"
                        class="rounded-lg px-3 py-2 text-sm transition hover:bg-white/10">Panel</a>
                    @if (auth()->user()->isAdmin())
                        <a href="{{ route('admin.convocatorias.index') }}"
                            class="rounded-lg px-3 py-2 text-sm transition hover:bg-white/10">Convocatorias</a>
                        <a href="{{ route('admin.users.index') }}"
                            class="rounded-lg px-3 py-2 text-sm transition hover:bg-white/10">Cuentas</a>
                        <a href="{{ route('admin.postulaciones.index') }}"
                            class="rounded-lg px-3 py-2 text-sm transition hover:bg-white/10">Expedientes</a>
                        <a href="{{ route('admin.audit-logs.index') }}"
                            class="rounded-lg px-3 py-2 text-sm transition hover:bg-white/10">Historial</a>

                    @elseif (auth()->user()->role === \App\Models\User::ROLE_REVIEWER)
                        <a href="{{ route('dictaminador.convocatorias.index') }}"
                            class="rounded-lg px-3 py-2 text-sm transition hover:bg-white/10">Convocatorias</a>
                        <a href="{{ route('dictaminador.postulantes.index') }}"
                            class="rounded-lg px-3 py-2 text-sm transition hover:bg-white/10">Postulantes</a>
                        <a href="{{ route('dashboard') }}"
                            class="rounded-lg px-3 py-2 text-sm transition hover:bg-white/10">Dictaminaciones</a>
                    @else
                        <a href="{{ route('home') }}#convocatorias" data-applicant-convocatorias-link
                            class="rounded-lg px-3 py-2 text-sm transition hover:bg-white/10">Convocatorias</a>
                        <a href="{{ route('postulaciones.index') }}"
                            class="rounded-lg px-3 py-2 text-sm transition hover:bg-white/10">Mis postulaciones</a>
                    @endif
                </nav>
            @endauth

            @auth
                <div class="relative" data-notifications-menu>
                    <button type="button" data-notifications-toggle aria-controls="notifications-dropdown"
                        aria-expanded="false" aria-label="Notificaciones, {{ $unreadNotificationsCount }} nuevas"
                        class="relative inline-flex h-10 w-10 items-center justify-center rounded-full text-white transition hover:bg-white/10 focus:outline-none focus:ring-2 focus:ring-indigo-300">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                d="M18 8a6 6 0 0 0-12 0c0 7-3 7-3 9h18c0-2-3-2-3-9M10 21h4" />
                        </svg>
                        @if ($unreadNotificationsCount > 0)
                            <span
                                class="absolute right-0 top-0 flex h-4 min-w-4 items-center justify-center rounded-full bg-red-500 px-1 text-[10px] font-bold text-white">
                                {{ $unreadNotificationsCount > 99 ? '99+' : $unreadNotificationsCount }}
                            </span>
                        @endif
                    </button>
                    <div id="notifications-dropdown" data-notifications-dropdown
                        class="absolute right-0 top-full z-50 mt-2 hidden w-[min(22rem,calc(100vw-2rem))] overflow-hidden rounded-xl border border-gray-200 bg-white text-gray-800 shadow-xl">
                        <div class="flex items-center justify-between border-b border-gray-100 px-4 py-3">
                            <div>
                                <p class="text-sm font-semibold text-gray-900">Notificaciones</p>
                                <p class="text-xs text-gray-500">
                                    {{ $unreadNotificationsCount }} sin leer
                                </p>
                            </div>
                            <a href="{{ route('notifications.index') }}"
                                class="text-xs font-semibold text-rose-900 hover:underline">Ver todas</a>
                        </div>
                        <div class="max-h-80 overflow-y-auto">
                            @forelse ($recentNotifications as $notification)
                                <a href="{{ route('notifications.show', $notification->id) }}"
                                    class="block border-b border-gray-100 px-4 py-3 transition hover:bg-gray-50 {{ $notification->read_at ? '' : 'bg-rose-50/70' }}">
                                    <span class="flex items-start gap-3">
                                        @if (!$notification->read_at)
                                            <span class="mt-1.5 h-2 w-2 shrink-0 rounded-full bg-rose-600"
                                                aria-label="No leída"></span>
                                        @else
                                            <span class="mt-1.5 h-2 w-2 shrink-0"></span>
                                        @endif
                                        <span class="min-w-0 flex-1">
                                            <span class="block text-sm font-semibold text-gray-900">
                                                {{ data_get($notification->data, 'titulo', 'Notificación') }}
                                            </span>
                                            <span class="mt-0.5 line-clamp-2 block text-xs text-gray-600">
                                                {{ data_get($notification->data, 'mensaje', '') }}
                                            </span>
                                            <time datetime="{{ $notification->created_at->toIso8601String() }}"
                                                class="mt-1 block text-[11px] text-gray-500">
                                                {{ $notification->created_at->format('d/m/Y H:i') }}
                                            </time>
                                        </span>
                                    </span>
                                </a>
                            @empty
                                <p class="px-4 py-8 text-center text-sm text-gray-500">
                                    No tienes notificaciones.
                                </p>
                            @endforelse
                        </div>
                        <a href="{{ route('notifications.index') }}"
                            class="block bg-gray-50 px-4 py-3 text-center text-sm font-semibold text-gray-700 transition hover:bg-gray-100">
                            Abrir bandeja de notificaciones
                        </a>
                    </div>
                </div>

                <div class="relative flex items-center gap-2" data-profile-menu>
                    <button type="button" data-profile-toggle aria-controls="profile-menu" aria-expanded="false"
                        aria-label="Abrir menú del perfil"
                        class="inline-flex h-10 w-10 items-center justify-center rounded-full border border-white/30 bg-white/10 text-white transition hover:border-white/50 hover:bg-white/20 focus:outline-none focus:ring-2 focus:ring-indigo-300">
                        @if (auth()->user()->profile_photo_path)
                            <img src="{{ route('profile.photo', ['v' => auth()->user()->profile_photo_path]) }}" alt=""
                                class="h-full w-full rounded-full object-cover">
                        @else
                            <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.7"
                                    d="M20 21a8 8 0 0 0-16 0m8-10a5 5 0 1 0 0-10 5 5 0 0 0 0 10Z" />
                            </svg>
                        @endif
                    </button>

                    @if (
                            auth()->user()->role === \App\Models\User::ROLE_USER
                            && auth()->user()->tipo_usuario === 'aspirante_docente'
                            && filled(auth()->user()->plaza_jornada)
                        )
                        <div class="hidden min-w-0 items-center gap-2 lg:flex">
                            <span class="max-w-[12rem] truncate text-xs text-white/80">
                                {{ auth()->user()->name }}
                            </span>
                            <span class="shrink-0 rounded-full bg-white/15 px-2.5 py-1 text-[11px] font-semibold text-white">
                                Plaza {{ auth()->user()->plaza_jornada ?? 'N/A'  }}
                            </span>
                        </div>
                    @endif

                    @if (
                            auth()->user()->role === \App\Models\User::ROLE_USER
                            && auth()->user()->tipo_usuario === 'aspirante_no_docente'
                        )
                        <div class="hidden min-w-0 items-center gap-2 lg:flex">
                            <span class="max-w-[12rem] truncate text-xs text-white/80">
                                {{ auth()->user()->name }}
                            </span>
                        </div>
                    @endif

                    @if (
                            auth()->user()->role === \App\Models\User::ROLE_REVIEWER
                        )
                        <div class="hidden min-w-0 items-center gap-2 lg:flex">
                            <span class="max-w-[12rem] truncate text-xs text-white/80">
                                {{ auth()->user()->name }}
                            </span>
                            <span class="shrink-0 rounded-full bg-white/15 px-2.5 py-1 text-[11px] font-semibold text-white">
                                Dictaminador
                            </span>
                        </div>
                    @endif

                    @if (
                            auth()->user()->role === \App\Models\User::ROLE_ADMIN
                        )
                        <div class="hidden min-w-0 items-center gap-2 lg:flex">
                            <span class="max-w-[12rem] truncate text-xs text-white/80">
                                {{ auth()->user()->name }}
                            </span>
                            <span class="shrink-0 rounded-full bg-white/15 px-2.5 py-1 text-[11px] font-semibold text-white">
                                Administrador
                            </span>
                        </div>
                    @endif

                    <div id="profile-menu" data-profile-dropdown
                        class="absolute right-0 top-full z-50 mt-2 hidden w-64 overflow-hidden rounded-xl border border-gray-200 bg-white py-2 text-gray-800 shadow-xl">
                        <div class="border-b border-gray-100 px-4 pb-3 pt-2">
                            <p class="truncate text-sm font-semibold text-gray-900">{{ auth()->user()->name }}</p>
                            <p class="truncate text-xs text-gray-500">{{ auth()->user()->email }}</p>
                        </div>
                        <div class="space-y-1 p-2">
                            <a href="{{ route('profile.show') }}"
                                class="flex items-center gap-3 rounded-lg px-3 py-2 text-sm transition hover:bg-gray-50">
                                <svg class="h-5 w-5 text-gray-500" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                        d="M20 21a8 8 0 0 0-16 0m8-10a5 5 0 1 0 0-10 5 5 0 0 0 0 10Z" />
                                </svg>
                                Mi perfil
                            </a>

                            <!-- Enlace activo: Estado de Expediente Digital -->
                            <!-- <a href="{{ route('expediente.estado') }}"
                                        class="flex items-center gap-3 rounded-lg px-3 py-2 text-sm transition hover:bg-gray-50">
                                        <svg class="h-5 w-5 text-gray-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" aria-hidden="true">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5.586a1 1 0 0 1 .707.293l5.414 5.414a1 1 0 0 1 .293.707V19a2 2 0 0 1-2 2Z" />
                                        </svg>
                                        Estado de Expediente
                                    </a> -->

                            <!-- Enlace activo: Calendario Institucional / Fechas Clave -->
                            <a href="{{ route('calendario.institucional') }}"
                                class="flex items-center gap-3 rounded-lg px-3 py-2 text-sm transition hover:bg-gray-50">
                                <svg class="h-5 w-5 text-gray-500" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2Z" />
                                </svg>
                                Calendario Institucional
                            </a>

                            <!-- <a href="{{ route('profile.settings') }}"
                                        class="flex items-center gap-3 rounded-lg px-3 py-2 text-sm transition hover:bg-gray-50">
                                        <svg class="h-5 w-5 text-gray-500" viewBox="0 0 24 24" fill="none" stroke="currentColor" aria-hidden="true">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                                d="M12 8a4 4 0 1 0 0 8 4 4 0 0 0 0-8Zm0-6v2m0 16v2m10-10h-2M4 12H2m17.07-7.07-1.42 1.42M6.35 17.65l-1.42 1.42m14.14 0-1.42-1.42M6.35 6.35 4.93 4.93" />
                                        </svg>
                                        Configuración
                                        <span class="ml-auto text-[10px] text-gray-500">Próximamente</span>
                                    </a> -->
                            <!-- 
                                    <button type="button" data-theme-toggle aria-pressed="false"
                                        class="flex w-full items-center gap-3 rounded-lg px-3 py-2 text-left text-sm transition hover:bg-gray-50"></button> -->

                            <button type="button"
                                class="flex w-full items-center gap-3 rounded-lg px-3 py-2 text-left text-sm transition hover:bg-gray-50">
                                <svg data-theme-sun class="hidden h-5 w-5 text-amber-500" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" aria-hidden="true">
                                    <circle cx="12" cy="12" r="4" stroke-width="1.8" />
                                    <path stroke-linecap="round" stroke-width="1.8"
                                        d="M12 2v2m0 16v2M4.93 4.93l1.42 1.42m11.3 11.3 1.42 1.42M2 12h2m16 0h2M4.93 19.07l1.42-1.42m11.3-11.3 1.42-1.42" />
                                </svg>
                                <svg data-theme-moon class="h-5 w-5 text-indigo-500" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                        d="M20.9 13A9 9 0 0 1 11 3.1 9 9 0 1 0 20.9 13Z" />
                                </svg>
                                <span data-theme-label>Modo oscuro</span>
                                <span class="ml-auto text-[10px] text-gray-500">Próximamente</span>
                            </button>
                        </div>
                        <div class="border-t border-gray-100 px-2 pt-2">
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" aria-label="Cerrar sesión" title="Cerrar sesión"
                                    class="inline-flex w-full items-center gap-3 rounded-lg px-3 py-2 text-left text-sm text-red-700 transition hover:bg-red-50">
                                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8"
                                            d="M10 17l5-5-5-5m5 5H3m9-9h7a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-7" />
                                    </svg>
                                    Cerrar sesión
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endauth

            @auth
                <button type="button" data-navbar-toggle aria-controls="mobile-navigation" aria-expanded="false"
                    aria-label="Abrir menú de navegación"
                    class="inline-flex items-center justify-center rounded-lg p-2 text-white transition hover:bg-slate-800 focus:outline-none focus:ring-2 focus:ring-indigo-300 md:hidden">
                    <svg data-menu-icon class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                    <svg data-close-icon class="hidden h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                        aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18 18 6M6 6l12 12" />
                    </svg>
                </button>
            @endauth
        </div>
    </div>

    @auth
        <nav id="mobile-navigation" data-navbar-menu aria-label="Navegación móvil"
            class="hidden border-t border-white/15 px-4 pb-4 pt-2 md:hidden">
            <a href="{{ route('dashboard') }}" class="block rounded-lg px-3 py-2 text-sm transition hover:bg-white/10">Mi
                panel</a>
            @if (auth()->user()->isAdmin())
                <a href="{{ route('admin.users.index') }}"
                    class="block rounded-lg px-3 py-2 text-sm transition hover:bg-white/10">Cuentas</a>
                <a href="{{ route('admin.postulaciones.index') }}"
                    class="block rounded-lg px-3 py-2 text-sm transition hover:bg-white/10">Expedientes</a>
                <a href="{{ route('admin.audit-logs.index') }}"
                    class="block rounded-lg px-3 py-2 text-sm transition hover:bg-white/10">Historial</a>
            @elseif (auth()->user()->role === \App\Models\User::ROLE_REVIEWER)
                <a href="{{ route('dictaminador.convocatorias.index') }}"
                    class="block rounded-lg px-3 py-2 text-sm transition hover:bg-white/10">Convocatorias</a>
                <a href="{{ route('dictaminador.postulantes.index') }}"
                    class="block rounded-lg px-3 py-2 text-sm transition hover:bg-white/10">Postulantes</a>
            @else
                <a href="{{ route('home') }}#convocatorias" data-applicant-convocatorias-link
                    class="block rounded-lg px-3 py-2 text-sm transition hover:bg-white/10">Convocatorias</a>
                <a href="{{ route('postulaciones.index') }}"
                    class="block rounded-lg px-3 py-2 text-sm transition hover:bg-white/10">Mis postulaciones</a>
            @endif
        </nav>
    @endauth
</header>