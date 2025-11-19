{{-- ===========================================================
 Nombre de la clase: sidebar.blade.php
 Descripción: Menú lateral institucional responsive de SINDISOFT con paleta clara PRO-Laravel V3.2.
 Fecha de creación: 01/11/2025
 Elaboró: Iker Piza
 Fecha de liberación: 01/11/2025
 Autorizó: Líder Técnico
 Versión: 2.0
 Tipo de mantenimiento: Correctivo y perfectivo.
 Descripción del mantenimiento: Alineación total entre roles del sidebar y roles reales del sistema.
 Responsable: Iker Piza
 Revisor: QA SINDISOFT
=========================================================== --}}

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    @include('partials.head')
</head>

<body class="min-h-screen bg-white text-black font-[Inter] flex flex-col lg:flex-row">

    @php
        // Rol REAL de la base de datos
        $roleDB = auth()->user()->role ?? 'worker';

        // Mapa hacia los nombres usados en este sidebar
        $rol = match ($roleDB) {
            'admin' => 'administrador',
            'union' => 'sindicato',
            default => 'trabajador',
        };
    @endphp

    <flux:sidebar
        class="w-full lg:w-64 min-h-[60vh] lg:min-h-screen border-b lg:border-b-0 lg:border-e border-[#D9D9D9]
               bg-white shadow-md text-black flex flex-col justify-between px-4 py-5 lg:py-6 transition-all duration-300">

        <div>
            <a href="{{ route('dashboard') }}"
                class="flex items-center justify-center lg:justify-start space-x-3 mb-6 px-2">
                <img src="{{ asset('assets/img/logo_sindisoft.png') }}" 
                    alt="Logo SINDISOFT"
                    class="w-10 h-10 rounded-lg border border-[#272800]/30 shadow-sm">
                <span class="font-[Poppins] font-bold text-[#DE6601] text-lg tracking-wide">
                    SINDISOFT
                </span>
            </a>

            <div class="text-sm">
                <p class="text-[#272800] font-semibold mb-3 tracking-wider uppercase text-center lg:text-left">
                    Menú principal
                </p>

                <nav class="space-y-2 font-[Inter] text-center lg:text-left">

                    <!-- INICIO -->
                    <a href="{{ route('dashboard') }}"
                        class="flex items-center gap-3 px-2 py-2 rounded-lg transition
                        {{ request()->routeIs('dashboard')
                            ? 'bg-[#DE6601]/10 text-[#DE6601] font-semibold'
                            : 'text-[#241178] hover:text-[#DE6601]' }}">
                        <x-heroicon-o-home
                            class="w-5 h-5 {{ request()->routeIs('dashboard') ? 'text-[#DE6601]' : 'text-[#241178]' }}" />
                        <span>Inicio</span>
                    </a>

                    <!-- ADMINISTRADOR -->
                    @if ($rol === 'administrador')
                        
                        <a href="{{ route('users.index') }}"
                            class="flex items-center gap-3 px-2 py-2 rounded-lg transition
                            {{ request()->routeIs('users.index')
                                ? 'bg-[#DE6601]/10 text-[#DE6601] font-semibold'
                                : 'text-[#241178] hover:text-[#DE6601]' }}">
                            <x-heroicon-o-users class="w-5 h-5" />
                            <span>Gestión de usuarios</span>
                        </a>

                        <a href="{{ route('users.create') }}"
                            class="flex items-center gap-3 px-2 py-2 rounded-lg transition
                            {{ request()->routeIs('users.create')
                                ? 'bg-[#DE6601]/10 text-[#DE6601] font-semibold'
                                : 'text-[#241178] hover:text-[#DE6601]' }}">
                            <x-heroicon-o-user-plus class="w-5 h-5" />
                            <span>Dar de alta usuario</span>
                        </a>

                        <a href="{{ route('admin.configuration.logs') }}"
                            class="flex items-center gap-3 px-2 py-2 rounded-lg transition
                            {{ request()->routeIs('admin.configuration.logs')
                                ? 'bg-[#DE6601]/10 text-[#DE6601] font-semibold'
                                : 'text-[#241178] hover:text-[#DE6601]' }}">
                            <x-heroicon-o-clipboard-document-list class="w-5 h-5" />
                            <span>Bitácora del sistema</span>
                        </a>

                    @endif

                    <!-- SINDICATO -->
                    @if ($rol === 'sindicato')

                        <a href="{{ route('union.procedures.index') }}"
                            class="flex items-center gap-3 px-2 py-2 rounded-lg transition
                            {{ request()->routeIs('union.procedures.*')
                                ? 'bg-[#DE6601]/10 text-[#DE6601] font-semibold'
                                : 'text-[#241178] hover:text-[#DE6601]' }}">
                            <x-heroicon-o-document-text class="w-5 h-5" />
                            <span>Gestión de trámites</span>
                        </a>

                        <a href="{{ route('union.members.index') }}"
                            class="flex items-center gap-3 px-2 py-2 rounded-lg transition
                            {{ request()->routeIs('union.members.*')
                                ? 'bg-[#DE6601]/10 text-[#DE6601] font-semibold'
                                : 'text-[#241178] hover:text-[#DE6601]' }}">
                            <x-heroicon-o-users class="w-5 h-5" />
                            <span>Trabajadores registrados</span>
                        </a>

                        <a href="{{ route('union.workers.requests.index') }}"
                            class="flex items-center gap-3 px-2 py-2 rounded-lg transition
                            {{ request()->routeIs('union.workers.requests.*')
                                ? 'bg-[#DE6601]/10 text-[#DE6601] font-semibold'
                                : 'text-[#241178] hover:text-[#DE6601]' }}">
                            <x-heroicon-o-inbox-stack class="w-5 h-5" />
                            <span>Solicitudes de trabajadores</span>
                        </a>

                        <a href="{{ route('union.reports.index') }}"
                            class="flex items-center gap-3 px-2 py-2 rounded-lg transition
                            {{ request()->routeIs('union.reports.*')
                                ? 'bg-[#DE6601]/10 text-[#DE6601] font-semibold'
                                : 'text-[#241178] hover:text-[#DE6601]' }}">
                            <x-heroicon-o-chart-bar class="w-5 h-5" />
                            <span>Reportes y consultas</span>
                        </a>

                        <a href="{{ route('union.news.index') }}"
                            class="flex items-center gap-3 px-2 py-2 rounded-lg transition
                            {{ request()->routeIs('union.news.*')
                                ? 'bg-[#DE6601]/10 text-[#DE6601] font-semibold'
                                : 'text-[#241178] hover:text-[#DE6601]' }}">
                            <x-heroicon-o-megaphone class="w-5 h-5" />
                            <span>Noticias y convocatorias</span>
                        </a>

                    @endif

                    <!-- TRABAJADOR -->
                    @if ($rol === 'trabajador')

                        <a href="{{ route('worker.index') }}"
                            class="flex items-center gap-3 px-3 py-2 rounded-lg transition
                            {{ request()->routeIs('worker.index')
                                ? 'bg-[#DE6601]/10 text-[#DE6601] font-semibold'
                                : 'text-[#241178] hover:text-[#DE6601]' }}">
                            <x-heroicon-o-clipboard-document-list class="w-5 h-5" />
                            <span>Mis trámites</span>
                        </a>

                        <a href="{{ route('worker.notifications.index') }}"
                            class="flex items-center gap-3 px-3 py-2 rounded-lg transition
                            {{ request()->routeIs('worker.notifications.*')
                                ? 'bg-[#DE6601]/10 text-[#DE6601] font-semibold'
                                : 'text-[#241178] hover:text-[#DE6601]' }}">
                            <x-heroicon-o-bell class="w-5 h-5" />
                            <span>Notificaciones</span>
                        </a>

                        <a href="{{ route('worker.news.index') }}"
                            class="flex items-center gap-3 px-3 py-2 rounded-lg transition
                            {{ request()->routeIs('worker.news.*')
                                ? 'bg-[#DE6601]/10 text-[#DE6601] font-semibold'
                                : 'text-[#241178] hover:text-[#DE6601]' }}">
                            <x-heroicon-o-megaphone class="w-5 h-5" />
                            <span>Convocatorias y anuncios</span>
                        </a>

                    @endif

                </nav>
            </div>
        </div>

        <div class="border-t border-[#D9D9D9]/50 pt-4 mt-4">
            <div class="flex flex-col lg:flex-row lg:items-center gap-3 px-2 text-center lg:text-left">
                <div class="flex h-10 w-10 items-center justify-center mx-auto lg:mx-0 rounded-lg bg-[#DE6601]/10 text-[#DE6601] font-semibold">
                    {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                </div>
                <div>
                    <p class="text-sm font-[Poppins] font-semibold text-black leading-tight">
                        {{ auth()->user()->name }}
                    </p>
                    <p class="text-xs text-[#241178] leading-tight">
                        {{ auth()->user()->email ?? 'Sin correo' }}
                    </p>
                </div>
            </div>

            <form method="POST" action="{{ route('logout') }}" class="mt-3 px-2 text-center lg:text-left">
                @csrf
                <button type="submit"
                    class="flex justify-center lg:justify-start items-center gap-2 text-[#EE0000] hover:text-[#DE6601]
                           font-semibold text-sm transition">
                    <x-heroicon-o-arrow-right-start-on-rectangle class="w-5 h-5" />
                    Cerrar sesión
                </button>
            </form>
        </div>
    </flux:sidebar>

    <flux:header
        class="lg:hidden bg-white shadow-sm text-black px-4 py-3 flex items-center justify-between sticky top-0 z-50">
        <flux:sidebar.toggle class="text-[#DE6601]" inset="left">
            <x-heroicon-o-bars-3 class="w-6 h-6" />
        </flux:sidebar.toggle>

        <flux:dropdown position="top" align="end">
            <flux:profile :initials="strtoupper(substr(auth()->user()->name, 0, 2))"
                icon-trailing="chevron-down" class="text-[#241178]" />
            <flux:menu class="bg-white text-black border border-[#D9D9D9] rounded-xl shadow-md">

                <flux:menu.item :href="route('profile.edit')" wire:navigate>
                    <x-heroicon-o-user class="w-5 h-5 inline-block mr-2 text-[#241178]" />
                    Perfil de usuario
                </flux:menu.item>

                <flux:menu.separator />

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                        class="flex items-center w-full justify-center text-[#EE0000] hover:text-[#DE6601]">
                        <x-heroicon-o-arrow-right-start-on-rectangle class="w-5 h-5 inline-block mr-2" />
                        Cerrar sesión
                    </button>
                </form>
            </flux:menu>
        </flux:dropdown>
    </flux:header>

    <main class="flex-1 w-full px-4 sm:px-6 py-6">
        {{ $slot }}
    </main>

    @fluxScripts

</body>
</html>
