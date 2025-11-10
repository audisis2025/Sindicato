{{-- ===========================================================
 Nombre de la clase: sidebar.blade.php
 Descripción: Menú lateral institucional responsive de SINDISOFT con paleta clara PRO-Laravel V3.2.
 Fecha de creación: 01/11/2025
 Elaboró: Iker Piza
 Fecha de liberación: 01/11/2025
 Autorizó: Líder Técnico
 Versión: 1.9
 Tipo de mantenimiento: Adaptación responsive.
 Descripción del mantenimiento: Se mejoró la estructura y estilos para compatibilidad móvil,
 se mantuvo la paleta institucional y se optimizó el layout en pantallas pequeñas.
 Responsable: Iker Piza
 Revisor: QA SINDISOFT
=========================================================== --}}

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    @include('partials.head')
</head>

<body class="min-h-screen bg-[#FFFFFF] text-[#000000] font-[Inter] flex flex-col lg:flex-row">

    <!-- 🔹 Sidebar -->
    <flux:sidebar
        class="w-full lg:w-64 min-h-[60vh] lg:min-h-screen border-b lg:border-b-0 lg:border-e border-[#D9D9D9]
               bg-[#FFFFFF] shadow-md text-[#000000] flex flex-col justify-between px-4 py-5 lg:py-6 transition-all duration-300">

        <!-- 🔸 Encabezado -->
        <div>
            <!-- Logo y título -->
            <a href="{{ route('dashboard') }}"
                class="flex items-center justify-center lg:justify-start space-x-3 mb-6 px-2">
                <img src="{{ asset('assets/img/logo_sindisoft.png') }}" alt="Logo SINDISOFT"
                    class="w-10 h-10 rounded-lg border border-[#272800]/30 shadow-sm">
                <span class="font-[Poppins] font-bold text-[#DC6601] text-lg tracking-wide">
                    SINDISOFT
                </span>
            </a>

            @php $rol = auth()->user()->rol ?? 'trabajador'; @endphp

            <!-- 🔹 Menú principal -->
            <div class="text-sm">
                <p class="text-[#272800] font-semibold mb-3 tracking-wider uppercase text-center lg:text-left">
                    Menú principal
                </p>

                <nav class="space-y-2 font-[Inter] text-center lg:text-left">
                    <a href="{{ route('dashboard') }}"
                        class="flex items-center gap-3 px-2 py-2 rounded-lg transition
                        {{ request()->routeIs('dashboard')
                            ? 'bg-[#DC6601]/10 text-[#DC6601] font-semibold'
                            : 'text-[#241178] hover:text-[#DC6601]' }}">
                        <x-heroicon-o-home
                            class="w-5 h-5 {{ request()->routeIs('dashboard') ? 'text-[#DC6601]' : 'text-[#241178]' }}" />
                        <span>Inicio</span>
                    </a>


                    @if ($rol === 'administrador')
                        <a href="{{ route('users.index') }}"
                            class="flex justify-center lg:justify-start items-center gap-3 px-2 py-2 rounded-lg transition
                            {{ request()->routeIs('users.index')
                                ? 'bg-[#DC6601]/10 text-[#DC6601] font-semibold'
                                : 'text-[#241178] hover:text-[#DC6601]' }}">
                            <x-heroicon-o-users
                                class="w-5 h-5 {{ request()->routeIs('users.index') ? 'text-[#DC6601]' : 'text-[#241178]' }}" />
                            <span>Gestión de usuarios</span>
                        </a>

                        <a href="{{ route('users.create') }}"
                            class="flex justify-center lg:justify-start items-center gap-3 px-2 py-2 rounded-lg transition
                            {{ request()->routeIs('users.create')
                                ? 'bg-[#DC6601]/10 text-[#DC6601] font-semibold'
                                : 'text-[#241178] hover:text-[#DC6601]' }}">
                            <x-heroicon-o-user-plus
                                class="w-5 h-5 {{ request()->routeIs('users.create') ? 'text-[#DC6601]' : 'text-[#241178]' }}" />
                            <span>Dar de alta usuario</span>
                        </a>


                        {{-- <a href="{{ route('admin.configuration') }}"
                            class="flex justify-center lg:justify-start items-center gap-3 px-2 py-2 rounded-lg transition
                            {{ request()->routeIs('admin.configuration')
                                ? 'bg-[#DC6601]/10 text-[#DC6601] font-semibold'
                                : 'text-[#241178] hover:text-[#DC6601]' }}">
                            <x-heroicon-o-cog-6-tooth
                                class="w-5 h-5 {{ request()->routeIs('admin.configuration') ? 'text-[#DC6601]' : 'text-[#241178]' }}" />
                            <span>Configuración del sistema</span>
                        </a> --}}
                        <a href="{{ route('admin.configuration.logs') }}"
                            class="flex justify-center lg:justify-start items-center gap-3 px-2 py-2 rounded-lg transition
                            {{ request()->routeIs('admin.configuration.logs')
                                ? 'bg-[#DC6601]/10 text-[#DC6601] font-semibold'
                                : 'text-[#241178] hover:text-[#DC6601]' }}">
                            <x-heroicon-o-clipboard-document-list
                                class="w-5 h-5 {{ request()->routeIs('admin.configuration.logs') ? 'text-[#DC6601]' : 'text-[#241178]' }}" />
                            <span>Bitácora del sistema</span>
                        </a>



                        </a>
                    @elseif ($rol === 'sindicato')
                        <!-- 🧾 Gestión de Trámites -->
                        <a href="{{ route('union.procedures.index') }}"
                            class="flex justify-center lg:justify-start items-center gap-3 px-2 py-2 rounded-lg transition
                            {{ request()->routeIs('union.procedures.*')
                                ? 'bg-[#DC6601]/10 text-[#DC6601] font-semibold'
                                : 'text-[#241178] hover:text-[#DC6601]' }}">
                            <x-heroicon-o-document-text
                                class="w-5 h-5 {{ request()->routeIs('union.procedures.*') ? 'text-[#DC6601]' : 'text-[#241178]' }}" />
                            <span>Gestión de trámites</span>
                        </a>

                        <!-- 👷 Gestión de Miembros / Trabajadores -->
                        <a href="{{ route('union.members.index') }}"
                            class="flex justify-center lg:justify-start items-center gap-3 px-2 py-2 rounded-lg transition
                        {{ request()->routeIs('union.members.*')
                            ? 'bg-[#DC6601]/10 text-[#DC6601] font-semibold'
                            : 'text-[#241178] hover:text-[#DC6601]' }}">
                            <x-heroicon-o-users
                                class="w-5 h-5 {{ request()->routeIs('union.members.*') ? 'text-[#DC6601]' : 'text-[#241178]' }}" />
                            <span>Trabajadores registrados</span>
                        </a>

                        <a href="{{ route('union.workers.requests.index') }}"
                            class="flex justify-center lg:justify-start items-center gap-3 px-2 py-2 rounded-lg transition
{{ request()->routeIs('union.workers.requests.*') ? 'bg-[#DC6601]/10 text-[#DC6601] font-semibold' : 'text-[#241178] hover:text-[#DC6601]' }}">
                            <x-heroicon-o-inbox-stack
                                class="w-5 h-5 {{ request()->routeIs('union.workers.requests.*') ? 'text-[#DC6601]' : 'text-[#241178]' }}" />
                            <span>Solicitudes de trabajadores</span>
                        </a>



                        <!-- 📊 Reportes -->
                        <a href="{{ route('union.reports.index') }}"
                            class="flex justify-center lg:justify-start items-center gap-3 px-2 py-2 rounded-lg transition
{{ request()->routeIs('union.reports.*') ? 'bg-[#DC6601]/10 text-[#DC6601] font-semibold' : 'text-[#241178] hover:text-[#DC6601]' }}">
                            <x-heroicon-o-chart-bar
                                class="w-5 h-5 {{ request()->routeIs('union.reports.*') ? 'text-[#DC6601]' : 'text-[#241178]' }}" />
                            <span>Reportes y consultas</span>
                        </a>


                        <!-- 📢 Portal informativo -->
                        <a href="{{ route('union.news.index') }}"
                            class="flex justify-center lg:justify-start items-center gap-3 px-2 py-2 rounded-lg transition
        {{ request()->routeIs('union.news.*')
            ? 'bg-[#DC6601]/10 text-[#DC6601] font-semibold'
            : 'text-[#241178] hover:text-[#DC6601]' }}">
                            <x-heroicon-o-megaphone
                                class="w-5 h-5 {{ request()->routeIs('union.news.*') ? 'text-[#DC6601]' : 'text-[#241178]' }}" />
                            <span>Noticias y convocatorias</span>
                        </a>
                    @elseif ($rol === 'trabajador')
                        <!-- 👷 Panel del Trabajador -->
                        <a href="{{ route('worker.index') }}"
                            class="flex justify-center lg:justify-start items-center gap-3 px-2 py-2 rounded-lg transition
        {{ request()->routeIs('worker.index')
            ? 'bg-[#DC6601]/10 text-[#DC6601] font-semibold'
            : 'text-[#241178] hover:text-[#DC6601]' }}">
                            <x-heroicon-o-clipboard-document-list
                                class="w-5 h-5 {{ request()->routeIs('worker.index') ? 'text-[#DC6601]' : 'text-[#241178]' }}" />
                            <span>Mis trámites</span>
                        </a>

                        <a href="{{ route('worker.notifications.index') }}"
                            class="flex items-center gap-3 px-3 py-2 rounded-lg transition
   {{ request()->routeIs('worker.notifications.*') ? 'bg-[#DC6601]/10 text-[#DC6601] font-semibold' : 'text-[#241178] hover:text-[#DC6601]' }}">
                            <x-heroicon-o-bell
                                class="w-5 h-5
   {{ request()->routeIs('worker.notifications.*') ? 'text-[#DC6601]' : 'text-[#241178]' }}" />
                            <span>Notificaciones</span>
                        </a>

                        <a href="{{ route('worker.news.index') }}"
                            class="flex items-center gap-3 px-3 py-2 rounded-lg transition
   {{ request()->routeIs('worker.news.*') ? 'bg-[#DC6601]/10 text-[#DC6601] font-semibold' : 'text-[#241178] hover:text-[#DC6601]' }}">
                            <x-heroicon-o-megaphone
                                class="w-5 h-5
   {{ request()->routeIs('worker.news.*') ? 'text-[#DC6601]' : 'text-[#241178]' }}" />
                            <span>Convocatorias y anuncios</span>
                        </a>
                    @endif

                </nav>
            </div>
        </div>

        <!-- 🔹 Menú inferior -->
        <div class="border-t border-[#D9D9D9]/50 pt-4 mt-4">
            <div class="flex flex-col lg:flex-row lg:items-center gap-3 px-2 text-center lg:text-left">
                <div
                    class="flex h-10 w-10 items-center justify-center mx-auto lg:mx-0 rounded-lg bg-[#DC6601]/10 text-[#DC6601] font-semibold">
                    {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                </div>
                <div>
                    <p class="text-sm font-[Poppins] font-semibold text-[#000000] leading-tight">
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
                    class="flex justify-center lg:justify-start items-center gap-2 text-[#EE0000] hover:text-[#DC6601]
                           font-semibold text-sm transition">
                    <x-heroicon-o-arrow-right-start-on-rectangle class="w-5 h-5" />
                    Cerrar sesión
                </button>
            </form>
        </div>
    </flux:sidebar>

    <!-- 🔸 Header móvil -->
    <flux:header
        class="lg:hidden bg-[#FFFFFF] shadow-sm text-[#000000] px-4 py-3 flex items-center justify-between sticky top-0 z-50">
        <flux:sidebar.toggle class="text-[#DC6601]" inset="left">
            <x-heroicon-o-bars-3 class="w-6 h-6" />
        </flux:sidebar.toggle>

        <flux:dropdown position="top" align="end">
            <flux:profile :initials="strtoupper(substr(auth()->user()->name, 0, 2))" icon-trailing="chevron-down"
                class="text-[#241178]" />
            <flux:menu class="bg-[#FFFFFF] text-[#000000] border border-[#D9D9D9] rounded-xl shadow-md">
                <flux:menu.item :href="route('profile.edit')" wire:navigate>
                    <x-heroicon-o-user class="w-5 h-5 inline-block mr-2 text-[#241178]" />
                    Perfil de usuario
                </flux:menu.item>

                <flux:menu.separator />

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                        class="flex items-center w-full justify-center text-[#EE0000] hover:text-[#DC6601]">
                        <x-heroicon-o-arrow-right-start-on-rectangle class="w-5 h-5 inline-block mr-2" />
                        Cerrar sesión
                    </button>
                </form>
            </flux:menu>
        </flux:dropdown>
    </flux:header>

    <!-- 🔹 Contenido principal -->
    <main class="flex-1 w-full px-4 sm:px-6 py-6">
        {{ $slot }}
    </main>

    @fluxScripts

    {{-- ===========================================================
     SweetAlert2 Global – Sistema SINDISOFT
     Configuración de estilo institucional
    =========================================================== --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // 🎨 Configuración visual global de SweetAlert2 (paleta SINDISOFT)
        const swalMixin = Swal.mixin({
            background: '#FFFFFF',
            color: '#241178',
            confirmButtonColor: '#DC6601',
            cancelButtonColor: '#241178',
            iconColor: '#DC6601'
        });
    </script>

    {{-- ===========================================================
     Mensajes automáticos de sesión
    =========================================================== --}}
    @if (session('success'))
        <script>
            swalMixin.fire({
                icon: 'success',
                title: 'Éxito',
                text: '{{ session('success') }}'
            });
        </script>
    @endif

    @if (session('error'))
        <script>
            swalMixin.fire({
                icon: 'error',
                title: 'Error',
                text: '{{ session('error') }}'
            });
        </script>
    @endif

    @if (session('status'))
        <script>
            swalMixin.fire({
                icon: 'info',
                title: 'Información',
                text: '{{ session('status') }}'
            });
        </script>
    @endif

</body>

</html>
