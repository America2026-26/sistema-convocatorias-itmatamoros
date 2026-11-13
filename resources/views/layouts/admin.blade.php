<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Administración - TecNM</title>
    <!-- Tailwind CSS / Estilos -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-slate-100 font-sans antialiased">
    <div class="min-h-screen flex">
        <!-- Sidebar o Navegación de Administrador -->
        <aside class="w-64 bg-slate-900 text-slate-300 p-6 space-y-6">
            <h2 class="text-white text-lg font-bold">Admin TecNM</h2>
            <nav class="space-y-2">
                <a href="{{ route('admin.expedientes.index') }}" class="block px-4 py-2 rounded-lg hover:bg-slate-800 text-sm font-medium">Expedientes</a>
                <a href="{{ route('admin.users.index') }}" class="block px-4 py-2 rounded-lg hover:bg-slate-800 text-sm font-medium">Cuentas y Usuarios</a>
                <a href="{{ route('admin.audit-logs.index') }}" class="block px-4 py-2 rounded-lg hover:bg-slate-800 text-sm font-medium">Auditoría</a>
            </nav>
        </aside>

        <!-- Contenido Principal -->
        <div class="flex-1 flex flex-col">
            <header class="bg-white shadow-sm h-16 flex items-center justify-between px-8">
                <span class="font-bold text-slate-700">Panel de Control</span>
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="text-red-600 hover:text-red-800 text-sm font-bold">Cerrar Sesión</button>
                </form>
            </header>

            <main class="p-8">
                @yield('content')
            </main>
        </div>
    </div>
</body>
</html>