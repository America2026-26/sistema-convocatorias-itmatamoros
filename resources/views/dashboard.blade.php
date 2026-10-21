<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel del Aspirante - Sistema de Convocatorias ITM</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 text-gray-800 font-sans antialiased">

    <!-- Navbar Privada Guinda -->
    <header class="bg-rose-950 text-white shadow-md">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3 flex justify-between items-center">
            <div class="flex items-center space-x-3">
                <div class="bg-white text-rose-950 p-2 rounded-lg font-bold text-lg shadow">
                    ITM
                </div>
                <div>
                    <h1 class="text-base font-bold leading-tight">Panel de Gestión de Candidatura</h1>
                    <p class="text-xs text-rose-200">Sistema de Convocatorias Docentes y No Docentes</p>
                </div>
            </div>

            <!-- Perfil del usuario y botón de cerrar sesión -->
            <div class="flex items-center space-x-4">
                <div class="text-right hidden sm:block">
                    <p class="text-xs font-semibold text-white">Carlos Eduardo Mendoza García</p>
                    <p class="text-xs text-rose-200">Aspirante Docente</p>
                </div>
                <a href="{{ route('logout') }}" class="bg-red-600 hover:bg-red-500 text-white text-xs font-semibold px-3 py-2 rounded-lg shadow transition">
                    Cerrar Sesión
                </a>
            </div>
        </div>
    </header>

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        
        <!-- Banner Guinda Bajito de bienvenida -->
        <div class="bg-rose-50 border border-rose-200 rounded-xl shadow-sm p-6 mb-8 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <h2 class="text-2xl font-bold text-rose-950">¡Bienvenido de nuevo, Carlos!</h2>
                <p class="text-xs text-rose-800 mt-1">Desde este panel puedes consultar convocatorias disponibles, cargar tu expediente y dar seguimiento a tus postulaciones.</p>
            </div>
            <a href="/" class="inline-flex items-center justify-center bg-rose-900 hover:bg-rose-800 text-white text-xs font-semibold px-4 py-2.5 rounded-lg shadow transition">
                Ver Convocatorias Disponibles &rarr;
            </a>
        </div>

        <!-- Módulos principales del aspirante -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <!-- Tarjeta 1: Expediente Digital -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5 hover:border-rose-700 transition">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs font-bold uppercase tracking-wider text-rose-900 bg-rose-100 px-2.5 py-1 rounded">Expediente Digital</span>
                    <span class="text-xs font-bold text-amber-600 bg-amber-50 px-2 py-0.5 rounded">Incompleto</span>
                </div>
                <h3 class="text-base font-bold text-gray-900">Documentos Personales</h3>
                <p class="text-xs text-gray-500 mt-1 mb-4">Sube tu CURP, RFC, Título Profesional y Cédula requeridos para postularte.</p>
                <button class="w-full bg-rose-900 hover:bg-rose-800 text-white text-xs font-medium py-2 rounded-lg transition">
                    Cargar / Actualizar Documentos
                </button>
            </div>

            <!-- Tarjeta 2: Mis Postulaciones -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5 hover:border-rose-700 transition">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs font-bold uppercase tracking-wider text-rose-900 bg-rose-100 px-2.5 py-1 rounded">Postulaciones</span>
                    <span class="text-xs font-bold text-rose-800 bg-rose-50 px-2 py-0.5 rounded">1 Activa</span>
                </div>
                <h3 class="text-base font-bold text-gray-900">Estatus de Solicitudes</h3>
                <p class="text-xs text-gray-500 mt-1 mb-4">Revisa la revisión técnica de la Comisión Dictaminadora.</p>
                <button class="w-full bg-rose-900 hover:bg-rose-800 text-white text-xs font-medium py-2 rounded-lg transition">
                    Ver Estado de Postulación
                </button>
            </div>

            <!-- Tarjeta 3: Dictámenes e Inconformidades -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-5 hover:border-rose-700 transition">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-xs font-bold uppercase tracking-wider text-emerald-900 bg-emerald-50 px-2.5 py-1 rounded">Dictámenes</span>
                    <span class="text-xs font-bold text-gray-500 bg-gray-100 px-2 py-0.5 rounded">0 Pendientes</span>
                </div>
                <h3 class="text-base font-bold text-gray-900">Resultados e Inconformidades</h3>
                <p class="text-xs text-gray-500 mt-1 mb-4">Consulta las actas finales o ingresa un recurso de revisión si es necesario.</p>
                <button class="w-full border border-gray-300 text-gray-700 hover:bg-gray-50 text-xs font-medium py-2 rounded-lg transition">
                    Consultar Histórico
                </button>
            </div>
        </div>

        <!-- Tabla Resumen de Postulaciones -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
                <h3 class="text-base font-bold text-gray-900">Mis Postulaciones Registradas</h3>
                <span class="text-xs text-gray-500">Ciclo Académico 2026</span>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-xs">
                    <thead>
                        <tr class="bg-gray-50 text-gray-500 uppercase tracking-wider border-b border-gray-100">
                            <th class="py-3 px-6">Convocatoria</th>
                            <th class="py-3 px-6">Tipo de Plaza</th>
                            <th class="py-3 px-6">Fecha Postulación</th>
                            <th class="py-3 px-6">Estatus</th>
                            <th class="py-3 px-6 text-right">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 text-gray-700">
                        <tr>
                            <td class="py-4 px-6 font-semibold text-gray-900">Convocatoria Docente Cátedra 2026</td>
                            <td class="py-4 px-6">Profesor Titular A (40 hrs)</td>
                            <td class="py-4 px-6">18/10/2026</td>
                            <td class="py-4 px-6">
                                <span class="bg-amber-100 text-amber-800 px-2.5 py-1 rounded-full font-semibold text-xs">
                                    En Revisión de Expediente
                                </span>
                            </td>
                            <td class="py-4 px-6 text-right">
                                <a href="#" class="text-rose-900 hover:underline font-bold">Ver Detalles</a>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

    </main>

    <footer class="bg-white border-t border-gray-200 py-4 text-center text-xs text-gray-500 mt-12">
        <p>&copy; {{ date('Y') }} Instituto Tecnológico de Matamoros - Módulo Privado del Aspirante.</p>
    </footer>

</body>
</html>