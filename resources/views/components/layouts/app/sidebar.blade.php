{{-- 
* Nombre de la vista          : sidebar.blade.php
* Descripción de la vista     : Layout principal del sistema SINDISOFT que define la estructura base de la interfaz,
*                               incluyendo sidebar por rol, header responsivo y contenedor de contenido ($slot).
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
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    @include('partials.head')
</head>

<body class="min-h-screen bg-white text-black font-sans flex flex-col lg:flex-row">

    @php
        $roleDB = auth()->check() ? auth()->user()->role : 'guest';

        $rol = match ($roleDB) {
            'admin' => 'administrador',
            'union' => 'sindicato',
            'worker' => 'trabajador',
            default => 'invitado',
        };

        $rutaInicio = match ($rol) {
            'administrador' => 'dashboard',
            'sindicato' => 'dashboard',
            'trabajador' => 'worker.catalog.index',
            default => 'dashboard',
        };
    @endphp

    <flux:sidebar sticky stashable class="border-e border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900">
        <flux:sidebar.toggle class="lg:hidden" icon="x-mark" />

        <a href="{{ route($rutaInicio) }}" class="me-5 flex items-center space-x-2 rtl:space-x-reverse">
            <img src="{{ asset('assets/img/logo_sindisoft.png') }}" alt="SINDISOFT" class="h-8 w-8 rounded-lg" />
            <span class="font-semibold text-custom-orange">
                Sindisoft
            </span>
        </a>

        <flux:navlist variant="outline">

            <flux:navlist.group :heading="__('Menú')" class="grid">

                <flux:navlist.item icon="home" :href="route($rutaInicio)" :current="request()->routeIs($rutaInicio)">
                    Inicio
                </flux:navlist.item>

                @if ($rol === 'administrador')
                    <flux:navlist.item icon="users" :href="route('users.index')"
                        :current="request()->routeIs('users.*')">
                        Gestión de usuarios
                    </flux:navlist.item>

                    <flux:navlist.item icon="user-plus" :href="route('users.create')"
                        :current="request()->routeIs('users.create')">
                        Dar de alta usuario
                    </flux:navlist.item>

                    <flux:navlist.item icon="user-circle" :href="route('admin.profile.edit')"
                        :current="request()->routeIs('admin.profile.*')">
                        Mi perfil
                    </flux:navlist.item>

                    <flux:navlist.item icon="clipboard-document-list" :href="route('admin.configuration.logs')"
                        :current="request()->routeIs('admin.configuration.logs')">
                        Bitácora del sistema
                    </flux:navlist.item>
                @endif

                @if ($rol === 'sindicato')
                    <flux:navlist.item icon="document-text" :href="route('union.procedures.index')"
                        :current="request()->routeIs('union.procedures.*')">
                        Gestión de trámites
                    </flux:navlist.item>

                    <flux:navlist.item icon="users" :href="route('union.members.index')"
                        :current="request()->routeIs('union.members.*')">
                        Trabajadores registrados
                    </flux:navlist.item>

                    <flux:navlist.item icon="inbox-stack" :href="route('union.requests.index')"
                        :current="request()->routeIs('union.requests.*')">
                        Solicitudes de trabajadores
                    </flux:navlist.item>

                    <flux:navlist.item icon="megaphone" :href="route('union.news.index')"
                        :current="request()->routeIs('union.news.*')">
                        Noticias y convocatorias
                    </flux:navlist.item>
                @endif

                @if ($rol === 'trabajador')
                    <flux:navlist.item icon="clipboard-document-list" :href="route('worker.index')"
                        :current="request()->routeIs('worker.index')">
                        Mis trámites
                    </flux:navlist.item>

                    <flux:navlist.item icon="bell" :href="route('worker.notifications.index')"
                        :current="request()->routeIs('worker.notifications.*')">
                        Notificaciones
                    </flux:navlist.item>

                    <flux:navlist.item icon="megaphone" :href="route('worker.news.index')"
                        :current="request()->routeIs('worker.news.*')">
                        Convocatorias y anuncios
                    </flux:navlist.item>
                @endif

            </flux:navlist.group>
        </flux:navlist>

        <flux:spacer />

        <flux:dropdown class="hidden lg:block" position="bottom" align="start">
            <flux:profile :name="auth()->user()->name" :initials="strtoupper(substr(auth()->user()->name, 0, 2))"
                icon:trailing="chevrons-up-down" />

            <flux:menu class="w-[220px]">

                <flux:menu.radio.group>
                    <div class="p-0 text-sm font-normal">
                        <div class="flex items-center gap-2 px-1 py-1.5">
                            <span
                                class="flex h-8 w-8 items-center justify-center rounded-lg bg-neutral-200 dark:bg-neutral-700">
                                {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                            </span>

                            <div class="grid flex-1 leading-tight">
                                <span class="truncate font-semibold">
                                    {{ auth()->user()->name }}
                                </span>
                                <span class="truncate text-xs">
                                    {{ auth()->user()->email }}
                                </span>
                            </div>
                        </div>
                    </div>
                </flux:menu.radio.group>

                <flux:menu.separator />
              
                <form method="POST" action="{{ route('logout') }}" class="w-full">
                    @csrf
                    <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle" class="w-full">
                        Cerrar sesión
                    </flux:menu.item>
                </form>

            </flux:menu>
        </flux:dropdown>
    </flux:sidebar>

    <flux:header
        class="lg:hidden bg-white shadow-sm text-black px-4 py-3 flex items-center justify-between sticky top-0 z-50">

        <flux:sidebar.toggle class="text-[#DE6601]" inset="left">
            <x-heroicon-o-bars-3 class="w-6 h-6" />
        </flux:sidebar.toggle>

        <flux:dropdown position="top" align="end">
            <flux:profile :initials="strtoupper(substr(auth()->user()->name, 0, 2))" icon-trailing="chevron-down"
                class="text-[#241178]" />

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
