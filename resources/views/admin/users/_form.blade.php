<form action="{{ $action }}" method="POST" class="space-y-5 rounded-2xl border border-gray-200 bg-white p-5 shadow-sm sm:p-6" data-account-form>
    @csrf
    @if ($method !== 'POST')
        @method($method)
    @endif

    @if ($errors->any())
        <div role="alert" class="rounded-lg border border-red-200 bg-red-50 p-4 text-sm text-red-800">
            <p class="mb-2 font-semibold">Revisa los datos del formulario:</p>
            <ul class="list-inside list-disc">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div>
        <label for="nombre" class="mb-1 block text-xs font-semibold uppercase text-gray-700">Nombre(s)</label>
        <input id="nombre" type="text" name="nombre" value="{{ old('nombre', $user->nombre ?? '') }}" maxlength="100" required
            class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-rose-900">
    </div>

    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
        <div>
            <label for="apellido_paterno" class="mb-1 block text-xs font-semibold uppercase text-gray-700">Apellido Paterno</label>
            <input id="apellido_paterno" type="text" name="apellido_paterno" value="{{ old('apellido_paterno', $user->apellido_paterno ?? '') }}" maxlength="100" required
                class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-rose-900">
        </div>
        <div>
            <label for="apellido_materno" class="mb-1 block text-xs font-semibold uppercase text-gray-700">Apellido Materno</label>
            <input id="apellido_materno" type="text" name="apellido_materno" value="{{ old('apellido_materno', $user->apellido_materno ?? '') }}" maxlength="100" required
                class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-rose-900">
        </div>
    </div>

    <div>
        <label for="email" class="mb-1 block text-xs font-semibold uppercase text-gray-700">Correo Electrónico</label>
        <input id="email" type="email" name="email" value="{{ old('email', $user->email ?? '') }}" maxlength="255" required autocomplete="email" placeholder="correo@ejemplo.com"
            class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-rose-900">
    </div>

    <div>
        <label for="role" class="mb-1 block text-xs font-semibold uppercase text-gray-700">Rol del sistema</label>
        <select id="role" name="role" data-account-role required
            class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-rose-900">
            @foreach ($roles as $role)
                <option value="{{ $role }}" @selected(old('role', $user->role ?? 'usuario') === $role)>
                    {{ ucfirst($role) }}
                </option>
            @endforeach
        </select>
    </div>

    <div data-applicant-fields @if (old('role', $user->role ?? 'usuario') !== 'usuario') hidden @endif class="space-y-5 rounded-xl border border-rose-100 bg-rose-50/30 p-4 sm:p-5">
        <div>
            <h2 class="text-sm font-bold text-rose-950">Información del perfil de aspirante</h2>
            <p class="mt-1 text-xs leading-5 text-gray-600">Completa los mismos datos solicitados durante el registro de aspirantes.</p>
        </div>

        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
            <div>
                <label for="curp" class="mb-1 block text-xs font-semibold uppercase text-gray-700">CURP</label>
                <input id="curp" type="text" name="curp" value="{{ old('curp', $user->curp ?? '') }}" maxlength="18" minlength="18"
                    @required(old('role', $user->role ?? 'usuario') === 'usuario') data-required-for-applicant placeholder="18 caracteres"
                    class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm uppercase focus:outline-none focus:ring-2 focus:ring-rose-900">
            </div>
            <div>
                <label for="rfc" class="mb-1 block text-xs font-semibold uppercase text-gray-700">RFC</label>
                <input id="rfc" type="text" name="rfc" value="{{ old('rfc', $user->rfc ?? '') }}" maxlength="13" minlength="12"
                    @required(old('role', $user->role ?? 'usuario') === 'usuario') data-required-for-applicant placeholder="12 o 13 caracteres"
                    class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm uppercase focus:outline-none focus:ring-2 focus:ring-rose-900">
            </div>
        </div>

        <div>
            <label for="tipo_usuario" class="mb-1 block text-xs font-semibold uppercase text-gray-700">Tipo de Perfil</label>
            <select id="tipo_usuario" name="tipo_usuario" data-applicant-type
                @required(old('role', $user->role ?? 'usuario') === 'usuario') data-required-for-applicant
                class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-rose-900">
                @foreach ($applicantTypes as $type)
                    <option value="{{ $type }}" @selected(old('tipo_usuario', $user->tipo_usuario ?? 'aspirante_docente') === $type)>
                        {{ $type === 'aspirante_docente' ? 'Aspirante a Plaza Docente' : 'Aspirante a Plaza No Docente / Administrativa' }}
                    </option>
                @endforeach
            </select>
        </div>

        <div data-teaching-position-field @if (old('role', $user->role ?? 'usuario') !== 'usuario' || old('tipo_usuario', $user->tipo_usuario ?? 'aspirante_docente') !== 'aspirante_docente') hidden @endif>
            <label for="plaza_jornada" class="mb-1 block text-xs font-semibold uppercase text-gray-700">Plaza de Jornada</label>
            <select id="plaza_jornada" name="plaza_jornada"
                class="w-full rounded-lg border border-gray-300 px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-rose-900">
                <option value="">Selecciona una plaza docente</option>
                @foreach (\App\Models\Convocatoria::TEACHING_POSITIONS as $positionCode => $positionLabel)
                    <option value="{{ $positionCode }}" @selected(old('plaza_jornada', $user->plaza_jornada ?? '') === $positionCode)>
                        {{ $positionCode }} — {{ $positionLabel }}
                    </option>
                @endforeach
            </select>
            @error('plaza_jornada')
                <p role="alert" class="mt-1 text-sm text-red-700">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
        <div data-password-field>
            <label for="password" class="mb-1 block text-xs font-semibold uppercase text-gray-700">
                Contraseña {{ isset($user) ? '(opcional)' : '' }}
            </label>
            <div class="relative">
                <input id="password" type="password" name="password" @required(! isset($user)) minlength="8" autocomplete="new-password" placeholder="••••••••"
                    class="w-full rounded-lg border border-gray-300 px-3 py-2 pr-20 text-sm focus:outline-none focus:ring-2 focus:ring-rose-900">
                <button type="button" data-password-toggle data-password-label="contraseña"
                    aria-controls="password" aria-label="Mostrar contraseña" aria-pressed="false"
                    class="absolute inset-y-0 right-0 inline-flex items-center rounded-r-lg px-3 text-xs font-semibold text-rose-900 transition hover:bg-rose-50 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-rose-700">
                    <span data-password-toggle-label>Mostrar</span>
                </button>
            </div>
        </div>
        <div data-password-field>
            <label for="password_confirmation" class="mb-1 block text-xs font-semibold uppercase text-gray-700">Confirmar Contraseña</label>
            <div class="relative">
                <input id="password_confirmation" type="password" name="password_confirmation" @required(! isset($user)) minlength="8" autocomplete="new-password" placeholder="••••••••"
                    class="w-full rounded-lg border border-gray-300 px-3 py-2 pr-20 text-sm focus:outline-none focus:ring-2 focus:ring-rose-900">
                <button type="button" data-password-toggle data-password-label="confirmación de contraseña"
                    aria-controls="password_confirmation" aria-label="Mostrar confirmación de contraseña" aria-pressed="false"
                    class="absolute inset-y-0 right-0 inline-flex items-center rounded-r-lg px-3 text-xs font-semibold text-rose-900 transition hover:bg-rose-50 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-rose-700">
                    <span data-password-toggle-label>Mostrar</span>
                </button>
            </div>
        </div>
    </div>

    <div class="flex flex-col-reverse gap-3 pt-2 sm:flex-row sm:justify-end">
        <a href="{{ route('admin.users.index') }}"
            class="rounded-lg border border-gray-300 px-4 py-2.5 text-center text-sm font-semibold text-gray-700 hover:bg-gray-50">Cancelar</a>
        <button type="submit"
            class="rounded-lg bg-rose-900 px-4 py-2.5 text-sm font-semibold text-white hover:bg-rose-800">{{ $submitLabel }}</button>
    </div>
</form>
