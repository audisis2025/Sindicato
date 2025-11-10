{{-- ===========================================================
 Nombre de la clase: login.blade.php
 Descripción: Vista de inicio de sesión para usuarios del sistema SINDISOFT.
 Fecha de creación: 31/10/2025
 Elaboró: Iker Piza
 Fecha de liberación: 01/11/2025
 Autorizó: Líder Técnico
 Versión: 1.8
 Tipo de mantenimiento: Correctivo y perfectivo.
 Descripción del mantenimiento: Eliminado el entorno tipo tarjeta para un diseño más limpio.
 Responsable: Iker Piza
 Revisor: QA SINDISOFT
=========================================================== --}}

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SINDISOFT - Inicio de Sesión</title>
    @vite('resources/css/app.css')
</head>

<body class="bg-[#FFFFFF] font-sans text-[#000000]">
    <div class="min-h-screen flex flex-col justify-center items-center px-4">

        <!-- Contenedor principal sin tarjeta -->
        <div class="text-center w-full max-w-md">

            <img src="{{ asset('assets/img/logo_sindisoft.png') }}" alt="Logo SINDISOFT"
                class="mx-auto w-28 sm:w-32 mb-4 rounded-full border border-[#272800]/30 shadow-sm bg-white">

            <h1 class="text-3xl font-[Poppins] font-bold text-[#DC6601]">
                Bienvenido a SINDISOFT
            </h1>
            <p class="text-[#241178] font-[Inter] text-sm mt-2">
                Inicia sesión con tu usuario y contraseña asignados por el Sindicato
            </p>

            @if (session('status'))
                <div
                    class="bg-[#DC6601]/10 border border-[#DC6601]/30 text-[#DC6601] text-sm font-semibold rounded-lg p-3 mt-4 text-center">
                    {{ session('status') }}
                </div>
            @endif

            @if ($errors->any())
                <div
                    class="bg-[#EE0000]/10 border border-[#EE0000]/30 text-[#EE0000] text-sm font-semibold rounded-lg p-3 mt-4 text-center">
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('login.store') }}" class="mt-6 text-left font-[Inter]">
                @csrf

                <div class="mb-4">
                    <label for="usuario" class="block text-[#000000] font-semibold mb-1">Usuario</label>
                    <input id="usuario" name="usuario" type="text" required placeholder="Ejemplo: jperez61"
                        autocomplete="off" autocorrect="off" autocapitalize="none" spellcheck="false"
                        class="w-full border border-[#272800] rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#DC6601]" />
                </div>

                <div class="mb-4">
                    <label for="password" class="block text-[#000000] font-semibold mb-1">Contraseña</label>
                    <input id="password" name="password" type="password" required autocomplete="current-password"
                        placeholder="********"
                        class="w-full border border-[#272800] rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#DC6601]" />
                </div>

                <div class="flex items-center mb-5">
                    <input id="remember" name="remember" type="checkbox"
                        class="h-4 w-4 text-[#DC6601] border-[#272800] rounded focus:ring-[#DC6601]">
                    <label for="remember" class="ml-2 text-sm text-[#000000]">
                        Recordarme
                    </label>
                </div>

                <button type="submit"
                    class="w-full font-[Poppins] bg-[#DC6601] hover:bg-[#EE0000] text-[#FFFFFF] font-semibold py-2 rounded-lg transition">
                    Iniciar sesión
                </button>

                <div class="text-center mt-4">
                    <a href="{{ route('password.request') }}"
                        class="text-sm font-[Poppins] text-[#241178] hover:text-[#EE0000] transition">
                        ¿Olvidaste tu contraseña?
                    </a>
                </div>
            </form>
        </div>

        <footer class="mt-10 text-center text-xs font-[Inter] text-[#000000]/80">
            © {{ date('Y') }} Sindicato Nacional de Trabajadores de la Educación<br>
            Sistema <span class="text-[#241178] font-semibold">SINDISOFT</span>
        </footer>
    </div>
</body>

</html>
