{{-- ===========================================================
 Nombre de la clase: login.blade.php
 Descripción: Vista de inicio de sesión para usuarios del sistema SINDISOFT.
 Fecha de creación: 31/10/2025
 Elaboró: Iker Piza
 Fecha de liberación: 01/11/2025
 Autorizó: Líder Técnico
 Versión: 2.0
 Tipo de mantenimiento: Homogeneización visual institucional.
 Descripción del mantenimiento: Sustitución de tonos azules por paleta naranja institucional.
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

<body class="bg-white font-sans text-black">
    <div class="min-h-screen flex flex-col justify-center items-center px-4">

        <div class="text-center w-full max-w-md">

            <img src="{{ asset('assets/img/logo_sindisoft.png') }}" alt="Logo SINDISOFT"
                class="mx-auto w-28 sm:w-32 mb-4 rounded-full border border-[#272800]/30 shadow-sm bg-white">

            <h1 class="text-3xl font-[Poppins] font-bold text-[#DE6601]">
                Bienvenido a SINDISOFT
            </h1>

            <p class="text-[#272800] font-[Inter] text-sm mt-2">
                Inicia sesión con tu usuario y contraseña asignados por el Sindicato
            </p>

            @if (session('status'))
                <div class="bg-[#DE6601]/10 border border-[#DE6601]/30 text-[#DE6601] text-sm font-semibold rounded-lg p-3 mt-4 text-center">
                    {{ session('status') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="bg-[#EE0000]/10 border border-[#EE0000]/30 text-[#EE0000] text-sm font-semibold rounded-lg p-3 mt-4 text-center">
                    {{ $errors->first() }}
                </div>
            @endif

            <form method="POST" action="{{ route('login.store') }}" class="mt-6 text-left font-[Inter]">
                @csrf

                <div class="mb-4">
                    <label for="usuario" class="block text-black font-semibold mb-1">Usuario</label>
                    <input id="usuario" name="usuario" type="text" required placeholder="Ejemplo: jperez61"
                        autocomplete="off" autocorrect="off" autocapitalize="none" spellcheck="false"
                        class="w-full h-12 border border-[#272800] rounded-lg px-3 focus:outline-none focus:ring-2 focus:ring-[#DE6601]" />
                </div>

                <div class="mb-4">
                    <label for="password" class="block text-black font-semibold mb-1">Contraseña</label>
                    <input id="password" name="password" type="password" required autocomplete="current-password"
                        placeholder="********"
                        class="w-full h-12 border border-[#272800] rounded-lg px-3 focus:outline-none focus:ring-2 focus:ring-[#DE6601]" />
                </div>

                <div class="flex items-center mb-5">
                    <input id="remember" name="remember" type="checkbox"
                        class="h-4 w-4 text-[#DE6601] border-[#272800] rounded focus:ring-[#DE6601]">
                    <label for="remember" class="ml-2 text-sm text-black">
                        Recordarme
                    </label>
                </div>

                <button type="submit"
                    class="w-full font-[Poppins] bg-[#DE6601] hover:bg-[#EE0000] text-white font-semibold py-2 rounded-lg transition">
                    Iniciar sesión
                </button>

                <div class="text-center mt-4">
                    <a href="{{ route('password.request') }}"
                        class="text-sm font-[Poppins] text-[#DE6601] hover:text-[#272800] transition">
                        ¿Olvidaste tu contraseña?
                    </a>
                </div>
            </form>
        </div>

        <footer class="mt-10 text-center text-xs font-[Inter] text-[#272800]">
            © {{ date('Y') }} Sindicato Nacional de Trabajadores de la Educación<br>
            Sistema <span class="text-[#DE6601] font-semibold">SINDISOFT</span>
        </footer>
    </div>
</body>

</html>
