<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <!-- tus meta y enlaces (vite, css, etc.) -->
</head>
<body class="font-sans antialiased bg-gray-100">
    {{-- Aquí tu nav, sidebar, etc. --}}
    <main class="min-h-screen">
        @yield('content')
    </main>

    <!-- ¡Asegúrate de incluir esto justo antes de cerrar body! -->
    @stack('scripts')
</body>
</html>
