{{-- 
* Nombre de la vista           : dashboard.blade.php
* Descripción de la vista      : Panel principal de bienvenida adaptativo según el rol del usuario.
* Fecha de creación            : 01/11/2025
* Elaboró                      : Iker Piza
* Fecha de liberación          : 01/11/2025
* Autorizó                     : Líder Técnico
* Version                      : 2.2
* Fecha de mantenimiento       : 01/11/2025
* Folio de mantenimiento       : N/A
* Tipo de mantenimiento        : Correctivo y perfectivo
* Descripción del mantenimiento: Homogeneización visual en tonos naranjas institucionales y corrección de fuentes.
* Responsable                  : Iker Piza
* Revisor                      : QA SINDISOFT
--}}

<x-layouts.app :title="__('Dashboard')">

    @php
        $rol = auth()->user()->role ?? 'trabajador';

        $notificaciones = collect();
        if ($rol === 'trabajador') {
            $notificaciones = \App\Models\Notification::where('user_id', auth()->id())
                ->where('status', 'unread')
                ->latest()
                ->take(5)
                ->get();
        }
    @endphp

    <div class="flex flex-col items-center justify-center w-full min-h-[80vh] bg-white p-6 sm:p-10 text-center font-sans">

        @if (session('status'))
            <div class="mb-6 w-full max-w-md bg-[#DE6601]/10 border border-[#DE6601]/30 text-[#DE6601] font-semibold rounded-xl p-4 shadow-sm">
                {{ session('status') }}
            </div>
        @endif

        @if ($rol === 'trabajador' && $notificaciones->count() > 0)
            <div class="w-full max-w-2xl mb-8 bg-[#FFF6EE] border border-[#DE6601]/40 rounded-xl shadow-sm p-5 text-left">

                <h3 class="text-[#DE6601] font-bold font-sans text-lg mb-2">
                    Tienes {{ $notificaciones->count() }} notificación(es) pendiente(s)
                </h3>

                <ul class="list-disc list-inside text-[#272800] text-sm font-sans space-y-1">
                    @foreach ($notificaciones as $n)
                        <li>
                            <strong>{{ $n->title }}:</strong> {{ $n->message }}
                        </li>
                    @endforeach
                </ul>

                <div class="mt-3 text-right">
                    <a href="{{ route('worker.notifications.index') }}" class="text-[#DE6601] hover:text-[#272800] font-semibold text-sm">
                        Ver todas
                    </a>
                </div>
            </div>
        @endif

        <h1 class="text-2xl sm:text-3xl md:text-4xl font-sans font-bold text-[#DE6601] mb-3">
            Bienvenido a Sindisoft
        </h1>

        <p class="text-[#272800] text-base sm:text-lg md:text-xl font-sans mb-3">
            {{ auth()->user()->name }} — Rol:
            <span class="capitalize font-semibold">
                {{ $rol === 'admin' ? 'Administrador' : ($rol === 'union' ? 'Sindicato' : 'Trabajador') }}
            </span>
        </p>

        <div class="w-16 sm:w-20 h-[3px] bg-[#DE6601] rounded-full mb-8"></div>

        <div class="max-w-xl sm:max-w-2xl text-sm sm:text-base md:text-lg text-black leading-relaxed px-2">

            @if ($rol === 'admin')
                <p>
                    Como <strong>Administrador del sistema</strong>, puedes gestionar usuarios, configurar el sistema y supervisar los módulos activos desde el menú lateral.
                </p>

                <p class="mt-3 text-[#DE6601] font-semibold">
                    Accede a tus herramientas desde la barra izquierda.
                </p>

            @elseif ($rol === 'union')
                <p>
                    Como <strong>Usuario Sindicato</strong>, puedes registrar y revisar los trámites sindicales, además de consultar la información de los miembros asociados.
                </p>

                <p class="mt-3 text-[#DE6601] font-semibold">
                    Utiliza el menú lateral para gestionar tus actividades sindicales.
                </p>
            @endif

        </div>

        <footer class="mt-10 text-center text-xs sm:text-sm text-[#272800] font-sans leading-tight">
            © {{ date('Y') }} Sindicato Nacional de Trabajadores de la Educación<br>
            <span class="text-[#DE6601] font-semibold">Sistema SINDISOFT</span>
        </footer>

    </div>

</x-layouts.app>