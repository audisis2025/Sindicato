{{-- ===========================================================
 Nombre de la clase: welcome.blade.php
 Descripción: Pantalla principal responsive del portal SINDISOFT.
 Fecha de creación: 01/11/2025
 Elaboró: Iker Piza
 Fecha de liberación: 01/11/2025
 Autorizó: Líder Técnico
 Versión: 1.5
 Tipo de mantenimiento: Homogeneización visual y adaptación responsive.
 Descripción del mantenimiento: Se ajustó el layout al diseño mobile-first
 con fuentes Poppins + Inter y la paleta institucional (figura 37).
 Responsable: Iker Piza
 Revisor: QA SINDISOFT
=========================================================== --}}

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SINDISOFT - Portal del Sindicato</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-[#FFFFFF] text-[#000000] font-[Inter] flex flex-col min-h-screen">

    {{-- Encabezado --}}
    <header class="bg-[#FFFFFF] border-b border-[#272800]/20 shadow-sm py-4">
        <div class="container mx-auto px-6 flex items-center justify-between">

            <div class="flex items-center gap-3">
                <img src="{{ asset('assets/img/logo_sindisoft.png') }}" alt="Logo SINDISOFT"
                    class="w-12 h-12 rounded-full border border-[#272800]/30 shadow-sm bg-white">

                <h1 class="text-xl sm:text-2xl font-[Poppins] font-bold text-[#DC6601]">
                    SINDISOFT
                </h1>
            </div>

            <nav>
                <a href="{{ route('login') }}"
                    class="font-[Poppins] text-[#DC6601] hover:text-[#EE0000] font-semibold px-4 py-2 rounded-lg transition">
                    Iniciar sesión
                </a>
            </nav>

        </div>
    </header>

    <main class="flex-grow container mx-auto px-6 py-16 flex flex-col justify-center items-center text-center">

        <h2 class="text-3xl sm:text-4xl font-[Poppins] font-bold text-[#DC6601] mb-4">
            Bienvenido a SINDISOFT
        </h2>

        <p class="text-[#241178] font-[Inter] text-base sm:text-lg max-w-xl">
            Sistema de Gestión de Trámites del Sindicato
        </p>

    </main>


    {{-- Pie de página --}}
    <footer
        class="bg-[#FFFFFF] border-t border-[#272800]/20 text-center py-6 text-xs sm:text-sm font-[Inter] text-[#272800]/80 leading-relaxed mt-auto">
        © {{ date('Y') }} Sindicato Nacional de Trabajadores de la Educación<br>
        <span class="text-[#241178] font-semibold">Sistema SINDISOFT</span>
    </footer>

</body>

</html>
