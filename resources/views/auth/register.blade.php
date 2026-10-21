<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Aspirante - Sistema de Convocatorias</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 font-sans antialiased">

    <div class="min-h-screen flex flex-col justify-center items-center py-12 px-4 sm:px-6 lg:px-8">
        
        <div class="sm:mx-auto sm:w-full sm:max-w-md text-center mb-6">
            <div class="inline-block bg-rose-950 text-white font-bold text-2xl p-3 rounded-lg shadow-md mb-2">
                ITM
            </div>
            <h2 class="text-2xl font-extrabold text-gray-900">Registro de Cuenta de Aspirante</h2>
            <p class="text-xs text-gray-600 mt-1">Ingresa tus datos personales para solicitar plazas docentes o no docentes</p>
        </div>

        <div class="bg-white py-8 px-6 shadow-xl rounded-2xl sm:max-w-xl sm:w-full border border-gray-200">
            <form action="{{ route('register.store') }}" method="POST" class="space-y-5">
                @csrf

                <div>
                    <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">Nombre(s)</label>
                    <input type="text" name="nombre" required placeholder=""
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-rose-900 focus:outline-none">
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">Apellido Paterno</label>
                        <input type="text" name="apellido_paterno" required placeholder=""
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-rose-900 focus:outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">Apellido Materno</label>
                        <input type="text" name="apellido_materno" required placeholder=""
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-rose-900 focus:outline-none">
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">CURP</label>
                        <input type="text" name="curp" maxlength="18" required placeholder="18 caracteres"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm uppercase focus:ring-2 focus:ring-rose-900 focus:outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">RFC</label>
                        <input type="text" name="rfc" maxlength="13" required placeholder="13 caracteres"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm uppercase focus:ring-2 focus:ring-rose-900 focus:outline-none">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">Tipo de Perfil</label>
                    <select name="tipo_usuario" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-rose-900 focus:outline-none">
                        <option value="aspirante_docente">Aspirante a Plaza Docente</option>
                        <option value="aspirante_no_docente">Aspirante a Plaza No Docente / Administrativa</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">Correo Electrónico</label>
                    <input type="email" name="correo" required placeholder="correo@ejemplo.com"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-rose-900 focus:outline-none">
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">Contraseña</label>
                        <input type="password" name="password" required placeholder="••••••••"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-rose-900 focus:outline-none">
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">Confirmar Contraseña</label>
                        <input type="password" name="password_confirmation" required placeholder="••••••••"
                               class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-rose-900 focus:outline-none">
                    </div>
                </div>

                <button type="submit" class="w-full bg-rose-900 hover:bg-rose-800 text-white font-semibold py-2.5 rounded-lg shadow-md transition duration-200 text-sm">
                    Registrar Cuenta e Iniciar Expediente
                </button>
            </form>

            <div class="mt-6 text-center border-t border-gray-100 pt-4">
                <p class="text-xs text-gray-600">
                    ¿Ya tienes una cuenta registrada? 
                    <a href="{{ route('login') }}" class="font-bold text-rose-900 hover:underline">Inicia Sesión aquí</a>
                </p>
            </div>
        </div>

        <div class="mt-6 text-center">
            <a href="/" class="text-xs text-gray-500 hover:text-gray-800">&larr; Volver al catálogo de convocatorias</a>
        </div>
    </div>

</body>
</html>