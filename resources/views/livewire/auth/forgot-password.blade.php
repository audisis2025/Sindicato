{{-- ===========================================================
 Nombre de la clase: forgot-password.blade.php
 Descripción: Vista para recuperación de contraseña en el sistema SINDISOFT.
 Fecha de creación: 01/11/2025
 Elaboró: Iker Piza
 Fecha de liberación: 01/11/2025
 Autorizó: Líder Técnico
 Versión: 1.1
 Tipo de mantenimiento: Homogeneización visual completa con login y portada.
 Descripción del mantenimiento: Se aplicaron fuentes Poppins + Inter, paleta oficial (figura 37) y formato institucional.
 Responsable: Iker Piza
 Revisor: QA SINDISOFT
=========================================================== --}}

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperar contraseña - SINDISOFT</title>
    @vite('resources/css/app.css')
</head>

<body class="bg-[#FFFFFF] font-sans text-[#000000]">
    <div class="min-h-screen flex flex-col justify-center items-center">

        <!-- Contenedor principal -->
        <div class="bg-[#FFFFFF] shadow-lg rounded-2xl p-10 text-center border border-[#272800]/20 w-full max-w-md">
            <!-- Logo -->
            <img src="{{ asset('assets/img/logo_sindisoft.png') }}" alt="Logo SINDISOFT"
                class="mx-auto w-32 mb-4 rounded-full border border-[#272800]/30 shadow-sm bg-white">

            <!-- Título -->
            <h1 class="text-3xl font-title font-bold text-[#DC6601] mb-2">
                ¿Olvidaste tu contraseña?
            </h1>
            <p class="text-[#241178] font-sans text-sm mb-6">
                Ingresa tu correo electrónico y recibirás un enlace para restablecerla.
            </p>

            <!-- Estado de sesión -->
            <x-auth-session-status class="text-center text-sm text-[#241178] mb-3" :status="session('status')" />

            <!-- Formulario -->
            <form method="POST" action="{{ route('password.email') }}" class="text-left font-sans">
                @csrf

                <!-- Correo electrónico -->
                <div class="mb-5">
                    <label for="email" class="block text-[#000000] font-semibold mb-1">Correo electrónico</label>
                    <input id="email" name="email" type="email" required autofocus
                        placeholder="correo@ejemplo.com" autocomplete="off" autocorrect="off" autocapitalize="none"
                        spellcheck="false"
                        class="w-full border border-[#272800] rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#DC6601]" />
                </div>

                <!-- Botón -->
                <button type="submit"
                    class="w-full font-title bg-[#DC6601] hover:bg-[#EE0000] text-[#FFFFFF] font-semibold py-2 rounded-lg transition">
                    Enviar enlace de restablecimiento
                </button>
            </form>

            <!-- Enlace para volver -->
            <div class="mt-6 text-center text-sm">
                <span class="text-[#000000]">¿Recordaste tu contraseña?</span>
                <a href="{{ route('login') }}"
                    class="font-title text-[#241178] hover:text-[#EE0000] font-medium ml-1 transition">
                    Iniciar sesión
                </a>
            </div>
        </div>

        <!-- Pie institucional -->
        <footer class="mt-10 text-center text-xs font-sans text-[#000000]/80">
            © {{ date('Y') }} Sindicato Nacional de Trabajadores de la Educación – Sección 61<br>
            Sistema SINDISOFT | Desarrollado bajo estándares PRO-Laravel V3.2
        </footer>
    </div>
</body>

</html>
