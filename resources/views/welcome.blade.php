{{-- ===========================================================
 Nombre de la clase: welcome.blade.php
 Descripción: Pantalla principal responsive del portal SINDISOFT.
 Fecha de creación: 01/11/2025
 Elaboró: Iker Piza
 Fecha de liberación: 01/11/2025
 Autorizó: Líder Técnico
 Versión: 1.9
 Tipo de mantenimiento: Ajuste de alineación de logo y capitalización de textos.
 Descripción del mantenimiento: Logo alineado a la izquierda y textos con capitalización estándar.
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

<body class="bg-white text-black font-[Inter] flex flex-col min-h-screen">

    <header class="bg-white border-b border-gray-300 shadow-sm py-4">
        <div class="w-full flex items-center justify-between px-6">

            {{-- LOGO 100% PEGADO AL BORDE IZQUIERDO --}}
            <div class="flex items-center gap-3">
                <img src="{{ asset('assets/img/logo_sindisoft.png') }}" alt="Logo SINDISOFT"
                    class="w-12 h-12 rounded-full border border-gray-300 shadow-sm bg-white">

                <h1 class="text-xl sm:text-2xl font-[Poppins] font-bold text-[#DE6601]">
                    Sindisoft
                </h1>
            </div>

            <nav class="space-x-4">
                <flux:button icon="arrow-right-start-on-rectangle" icon-variant="solid" :href="route('login')"
                    class="!bg-[#DE6601] !text-white !border-none hover:!bg-[#C95500] px-5 py-2 rounded-lg font-semibold transition">
                    Iniciar sesión
                </flux:button>

            </nav>

        </div>
    </header>


    {{-- CUERPO PRINCIPAL --}}
    <main class="flex-grow container mx-auto px-6 py-16 flex flex-col justify-center items-center text-center">

        <h2 class="text-3xl sm:text-4xl font-[Poppins] font-bold text-[#DE6601] mb-4">
            Bienvenido a Sindisoft
        </h2>

        <p class="text-[#272800] font-[Inter] text-base sm:text-lg max-w-xl">
            Sistema de gestión de trámites del Sindicato
        </p>

    </main>

    {{-- PIE DE PÁGINA --}}
    <footer
        class="bg-white border-t border-gray-300 text-center py-6 text-xs sm:text-sm font-[Inter] text-[#272800] leading-relaxed mt-auto">
        © {{ date('Y') }} Sindicato Nacional de Trabajadores de la Educación<br>
        <span class="text-[#DE6601] font-semibold">Sistema Sindisoft</span>
    </footer>

</body>

</html>
