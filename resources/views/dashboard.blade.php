{{-- ===========================================================
 Nombre de la clase: dashboard.blade.php
 Descripción: Panel principal de bienvenida adaptativo según el rol del usuario.
 Fecha de creación: 01/11/2025
 Elaboró: Iker Piza
 Fecha de liberación: 01/11/2025
 Autorizó: Líder Técnico
 Versión: 2.0
 Tipo de mantenimiento: Extensión funcional.
 Descripción del mantenimiento: Se agregó visualización automática de notificaciones
 pendientes al iniciar sesión para el rol Trabajador.
 Responsable: Iker Piza
 Revisor: QA SINDISOFT
 =========================================================== --}}

<x-layouts.app :title="__('Dashboard')">
    @php
        $rol = auth()->user()->rol ?? 'trabajador';

        // 🔔 Obtener notificaciones no leídas (solo trabajador)
        $notificaciones = collect();
        if ($rol === 'trabajador') {
            $notificaciones = \App\Models\Notification::where('user_id', auth()->id())
                ->where('estado', 'no_leida')
                ->latest()
                ->take(5)
                ->get();
        }
    @endphp

    <div class="flex flex-col items-center justify-center w-full min-h-[80vh] bg-[#FFFFFF] p-6 sm:p-10 text-center font-[Inter]">

        @if (session('status'))
            <div class="mb-6 w-full max-w-md bg-[#DC6601]/10 border border-[#DC6601]/30 text-[#DC6601] font-semibold rounded-xl p-4 shadow-sm">
                {{ session('status') }}
            </div>
        @endif

        @if ($rol === 'trabajador' && $notificaciones->count() > 0)
            <div class="w-full max-w-2xl mb-8 bg-[#FFF6EE] border border-[#DC6601]/40 rounded-xl shadow-sm p-5 text-left">
                <h3 class="text-[#DC6601] font-bold font-[Poppins] text-lg mb-2">
                    🔔 Tienes {{ $notificaciones->count() }} notificación(es) pendiente(s)
                </h3>
                <ul class="list-disc list-inside text-[#272800] text-sm font-[Inter] space-y-1">
                    @foreach ($notificaciones as $n)
                        <li>
                            <strong>{{ $n->titulo }}:</strong> {{ $n->mensaje }}
                        </li>
                    @endforeach
                </ul>
                <div class="mt-3 text-right">
                    <a href="{{ route('worker.notifications.index') }}"
                       class="text-[#241178] hover:text-[#DC6601] font-semibold text-sm">
                        Ver todas →
                    </a>
                </div>
            </div>
        @endif

        <h1 class="text-2xl sm:text-3xl md:text-4xl font-[Poppins] font-bold text-[#DC6601] mb-3">
            Bienvenido a SINDISOFT
        </h1>

        <p class="text-[#241178] text-base sm:text-lg md:text-xl font-[Inter] mb-3">
            {{ auth()->user()->name }} — Rol:
            <span class="capitalize font-semibold">{{ $rol }}</span>
        </p>

        <div class="w-16 sm:w-20 h-[3px] bg-[#DC6601] rounded-full mb-8"></div>

        <div class="max-w-xl sm:max-w-2xl text-sm sm:text-base md:text-lg text-[#000000] leading-relaxed px-2">
            @if ($rol === 'administrador')
                <p>Como <strong>Administrador del sistema</strong>, puedes gestionar usuarios, configurar el sistema y supervisar los módulos activos desde el menú lateral.</p>
                <p class="mt-3 text-[#241178] font-semibold">Accede a tus herramientas desde la barra izquierda.</p>
            @elseif ($rol === 'sindicato')
                <p>Como <strong>Usuario Sindicato</strong>, puedes registrar y revisar los trámites sindicales, además de consultar la información de los miembros asociados.</p>
                <p class="mt-3 text-[#241178] font-semibold">Utiliza el menú lateral para gestionar tus actividades sindicales.</p>
            @else
                <p>Como <strong>Trabajador</strong>, puedes consultar tus trámites, revisar su estatus, recibir notificaciones y acceder al área de soporte para resolver incidencias.</p>
                <p class="mt-3 text-[#241178] font-semibold">Encuentra todo lo necesario en el menú lateral.</p>
            @endif
        </div>

        <div class="mt-10 sm:mt-14"></div>

        <footer class="text-center text-xs sm:text-sm text-[#272800] font-[Inter] leading-tight">
            © {{ date('Y') }} Sindicato Nacional de Trabajadores de la Educación – Sección 61<br>
            <span class="text-[#241178] font-semibold">Sistema SINDISOFT</span>
        </footer>
    </div>
</x-layouts.app>
