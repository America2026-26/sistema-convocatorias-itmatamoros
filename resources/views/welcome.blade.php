<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema Integral de Gestión y Automatización de Convocatorias</title>
    <!-- CDN de Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 text-gray-800 font-sans antialiased">

    <!-- Encabezado / Navbar en Guinda Oscuro -->
    <header class="bg-rose-950 text-white shadow-lg sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 flex justify-between items-center">
            <div class="flex items-center space-x-3">
                <div class="bg-white text-rose-950 p-2 rounded-lg font-bold text-xl shadow">
                    ITM
                </div>
                <div>
                    <h1 class="text-lg sm:text-xl font-bold leading-tight">Sistema de Convocatorias Docentes y No Docentes</h1>
                    <p class="text-xs text-rose-200">Plataforma Institucional de Gestión y Automatización</p>
                </div>
            </div>
            <div class="space-x-3">
                <a href="#convocatorias" class="text-sm px-4 py-2 rounded-md hover:bg-rose-900 transition">Convocatorias</a>
                <a href="{{ route('login') }}" class="bg-emerald-600 hover:bg-emerald-500 text-white text-sm font-semibold px-4 py-2 rounded-md shadow transition">
                    Iniciar Sesión / Registro
                </a>
            </div>
        </div>
    </header>

    <!-- Sección Hero / Banner con degradado Guinda a Guinda Bajito -->
    <section class="bg-gradient-to-r from-rose-950 via-rose-900 to-red-900 text-white py-12 px-4 sm:px-6 lg:px-8 shadow-inner">
        <div class="max-w-7xl mx-auto text-center space-y-4">
            <span class="bg-rose-800 text-rose-100 text-xs font-semibold px-3 py-1 rounded-full uppercase tracking-wider">Módulo de Oposiciones y Convocatorias</span>
            <h2 class="text-3xl sm:text-4xl font-extrabold">Postulación y Registro de Expedientes Digitales</h2>
            <p class="max-w-2xl mx-auto text-rose-100 text-sm sm:text-base">
                Consulta las plazas docentes y no docentes disponibles, revisa los requisitos e inicia tu proceso de postulación digital.
            </p>
        </div>
    </section>

    <!-- Listado de Convocatorias -->
    <main id="convocatorias" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-2xl font-bold text-gray-900">Convocatorias Vigentes</h3>
            <span class="text-sm text-gray-500">Mostrando {{ count($convocatorias) }} convocatoria(s) activa(s)</span>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            @foreach($convocatorias as $convocatoria)
                <div class="bg-white rounded-xl shadow-md border border-gray-200 overflow-hidden hover:shadow-lg transition duration-200 flex flex-col justify-between">
                    <!-- Cabecera de la tarjeta -->
                    <div class="p-6">
                        <div class="flex justify-between items-start gap-2 mb-3">
                            <span class="text-xs font-semibold uppercase px-2.5 py-1 rounded-md {{ $convocatoria->tipo_personal == 'Docente' ? 'bg-rose-100 text-rose-900' : 'bg-amber-100 text-amber-800' }}">
                                {{ $convocatoria->tipo_personal }} - {{ $convocatoria->tipo_concurso }}
                            </span>
                            <span class="text-xs font-semibold bg-emerald-100 text-emerald-800 px-2.5 py-1 rounded-md">
                                {{ $convocatoria->estatus }}
                            </span>
                        </div>

                        <h4 class="text-xl font-bold text-gray-900 mb-2 leading-snug">
                            {{ $convocatoria->titulo }}
                        </h4>

                        <!-- Detalles clave -->
                        <div class="grid grid-cols-2 gap-4 text-xs text-gray-600 my-4 bg-rose-50/50 p-3 rounded-lg border border-rose-100">
                            <div>
                                <span class="block font-semibold text-gray-500">Categoría / Nivel:</span>
                                <span class="text-gray-800 font-medium">{{ $convocatoria->categoria_nivel }}</span>
                            </div>
                            <div>
                                <span class="block font-semibold text-gray-500">Plazas Disponibles:</span>
                                <span class="text-gray-800 font-medium">{{ $convocatoria->plaza_disponible }}</span>
                            </div>
                            <div>
                                <span class="block font-semibold text-gray-500">Jornada / Horario:</span>
                                <span class="text-gray-800 font-medium">{{ $convocatoria->jornada_horario }}</span>
                            </div>
                            <div>
                                <span class="block font-semibold text-gray-500">Remuneración Salarial:</span>
                                <span class="text-emerald-700 font-bold">${{ number_format($convocatoria->salario, 2) }} MXN</span>
                            </div>
                        </div>

                        <!-- Fechas vigencia -->
                        <div class="text-xs text-gray-500 flex items-center justify-between mb-4">
                            <span><strong>Apertura:</strong> {{ \Carbon\Carbon::parse($convocatoria->fecha_inicio)->format('d/m/Y') }}</span>
                            <span><strong>Cierre:</strong> {{ \Carbon\Carbon::parse($convocatoria->fecha_fin)->format('d/m/Y') }}</span>
                        </div>

                        <!-- Requisitos específicos -->
                        <div class="border-t border-gray-100 pt-3">
                            <p class="text-xs text-gray-600 line-clamp-2">
                                <strong class="text-gray-700">Requisitos:</strong> {{ $convocatoria->requisitos_especificos }}
                            </p>
                        </div>
                    </div>

                    <!-- Botón de acción en Guinda -->
                    <div class="bg-gray-50 px-6 py-3 border-t border-gray-100 flex justify-between items-center">
                        <a href="#" class="text-xs font-semibold text-rose-900 hover:text-rose-700">Ver bases completas &rarr;</a>
                        <a href="{{ route('login') }}" class="bg-rose-900 hover:bg-rose-800 text-white font-medium text-xs px-4 py-2 rounded-lg shadow transition">
                            Postularme a esta plaza
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-rose-950 text-rose-200 py-6 text-center text-xs mt-12 border-t border-rose-900">
        <p>&copy; {{ date('Y') }} Instituto Tecnológico de Matamoros - Sistema de Gestión e Inconformidades de Convocatorias.</p>
    </footer>

</body>
</html>