<!DOCTYPE html>
<html lang="es" data-theme="light">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Sistema de Convocatorias ITM')</title>
    <script>
        document.documentElement.dataset.theme = localStorage.getItem('itm-color-mode') === 'dark' ? 'dark' : 'light';
    </script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen flex flex-col bg-gray-50 text-gray-800 font-sans antialiased">
    @include('components.navbar.navbar')

    <main class="flex-1">
        @yield('content')
    </main>

    @include('components.footer.footer')
</body>

</html>
