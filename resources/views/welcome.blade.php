{{-- 
* Nombre de la vista          : welcome.blade.php
* Descripción de la vista     : Página de bienvenida pública del sistema SINDISOFT, que presenta información
*                               general del sistema y permite el acceso a la pantalla de inicio de sesión.
* Fecha de creación           : 03/11/2025
* Elaboró                     : Iker Piza
* Fecha de liberación         : 14/01/2026
* Autorizó                    : Salvador Monroy
* Versión                     : 1.0
* Fecha de mantenimiento      :
* Folio de mantenimiento      :
* Tipo de mantenimiento       :
* Descripción del mantenimiento:
* Responsable                 :
* Revisor                     :
--}}

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bienvenido | Sindisoft</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans bg-zinc-50 dark:bg-zinc-900 text-black dark:text-white flex flex-col min-h-screen">

    <header class="bg-white dark:bg-zinc-800 border-b border-zinc-200 dark:border-zinc-700 py-4 shadow-sm">
        <div class="container mx-auto px-6 flex items-center">

            <div class="flex items-center gap-3">
                <img src="{{ asset('assets/img/logo_Sindisoft.png') }}" alt="Sindisoft" class="h-10 w-auto" />

                <flux:heading level="1" size="xl" class="text-2xl !font-bold text-custom-orange">
                    Sindisoft
                </flux:heading>
            </div>

            <div class="ml-auto">
                <flux:button icon="user-circle" icon-variant="outline" variant="primary" :href="route('login')"
                    class="bg-black text-white hover:bg-custom-gray">
                    Iniciar sesión
                </flux:button>
            </div>

        </div>
    </header>

    <main class="flex-grow container mx-auto px-6 py-16 text-center flex flex-col justify-center">

        <flux:heading level="2" size="xl" class="text-4xl !font-extrabold mb-4">
            Bienvenido a Sindisoft
        </flux:heading>

        <flux:text class="text-lg text-black/70 dark:text-white/70 mb-12">
            Sistema de gestión de trámites del Sindicato, diseñado para optimizar procesos,
            seguimiento y control institucional.
        </flux:text>

    </main>

    <footer
        class="bg-custom-orange dark:bg-zinc-800 border-t border-zinc-200 dark:border-zinc-700 text-center py-6 mt-auto">
        <flux:text class="text-white text-sm">
            © {{ date('Y') }} Sindicato Nacional de Trabajadores de la Educación
        </flux:text>
    </footer>

</body>

</html>
