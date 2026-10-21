<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión - Sistema de Convocatorias</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 font-sans antialiased">

    <div class="min-h-screen flex flex-col justify-center items-center py-12 px-4 sm:px-6 lg:px-8">
        
        <div class="sm:mx-auto sm:w-full sm:max-w-md text-center mb-6">
            <div class="inline-block bg-rose-950 text-white font-bold text-2xl p-3 rounded-lg shadow-md mb-2">
                ITM
            </div>
            <h2 class="text-2xl font-extrabold text-gray-900">Iniciar Sesión</h2>
            <p class="text-xs text-gray-600 mt-1">Accede a tu panel de seguimiento de expediente y dictámenes</p>
        </div>

        <div class="bg-white py-8 px-6 shadow-xl rounded-2xl sm:max-w-md sm:w-full border border-gray-200">
            <form action="{{ route('login.store') }}" method="POST" class="space-y-5">
                @csrf

                <div>
                    <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">Correo Electrónico</label>
                    <input type="email" name="correo" required placeholder="correo@ejemplo.com"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-rose-900 focus:outline-none">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">Contraseña</label>
                    <input type="password" name="password" required placeholder="••••••••"
                           class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-rose-900 focus:outline-none">
                </div>

                <button type="submit" class="w-full bg-rose-900 hover:bg-rose-800 text-white font-semibold py-2.5 rounded-lg shadow-md transition duration-200 text-sm">
                    Ingresar al Sistema
                </button>
            </form>

            <div class="mt-6 text-center border-t border-gray-100 pt-4">
                <p class="text-xs text-gray-600">
                    ¿Aún no tienes cuenta? 
                    <a href="{{ route('register') }}" class="font-bold text-rose-900 hover:underline">Regístrate aquí</a>
                </p>
            </div>
        </div>

        <div class="mt-6 text-center">
            <a href="/" class="text-xs text-gray-500 hover:text-gray-800">&larr; Volver a las convocatorias</a>
        </div>
    </div>

</body>
</html>