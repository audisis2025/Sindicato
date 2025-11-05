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
    @vite('resources/css/app.css')
</head>

<body class="bg-[#FFFFFF] font-[Inter] text-[#000000] min-h-screen flex flex-col justify-center items-center p-4 sm:p-6">

    <!-- 🔹 Contenedor principal -->
    <div class="w-full max-w-sm sm:max-w-md md:max-w-lg bg-[#FFFFFF] border border-[#272800]/20 rounded-2xl shadow-lg p-8 sm:p-10 text-center">

        <!-- 🧩 Logo -->
        <img src="{{ asset('assets/img/logo_sindisoft.png') }}"
             alt="Logo SINDISOFT"
             class="mx-auto w-24 sm:w-28 md:w-32 mb-5 rounded-full border border-[#272800]/30 shadow-sm bg-white">

        <!-- 🏷️ Título -->
        <h1 class="text-2xl sm:text-3xl font-[Poppins] font-bold text-[#DC6601] mb-2">
            Bienvenido a SINDISOFT
        </h1>

        <!-- 💬 Subtítulo -->
        <p class="text-[#241178] font-[Inter] text-sm sm:text-base md:text-lg mb-6">
            Sistema de Gestión de Trámites del Sindicato
        </p>

        <!-- 🔘 Botón de acceso -->
        <a href="{{ route('login') }}"
           class="inline-block font-[Poppins] bg-[#DC6601] hover:bg-[#EE0000] text-[#FFFFFF] font-semibold py-2.5 px-6 sm:px-8 rounded-lg transition w-full sm:w-auto shadow-md">
            Iniciar sesión
        </a>
    </div>

    <!-- 📅 Pie institucional -->
    <footer class="mt-10 text-center text-xs sm:text-sm font-[Inter] text-[#272800]/80 leading-relaxed">
        © {{ date('Y') }} Sindicato Nacional de Trabajadores de la Educación – Sección 61<br>
        <span class="text-[#241178] font-semibold">Sistema SINDISOFT</span>
    </footer>

</body>
</html>
