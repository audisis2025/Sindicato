{{-- 
* Nombre de la vista          : index.blade.php
* Descripción de la vista     : Vista principal para la gestión de usuarios del sistema, que permite al
*                               administrador consultar, filtrar, crear, editar, activar, desactivar
*                               y eliminar usuarios según su rol.
* Fecha de creación           : 07/11/2025
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

<x-layouts.app :title="__('Gestión de Usuarios')">

    <div class="p-6 w-full max-w-6xl mx-auto space-y-8">

        <div class="flex items-center justify-between">
            <h1 class="text-3xl font-bold text-[#DE6601]">
                Gestión de Usuarios
            </h1>
            <flux:button icon="plus" variant="primary" :href="route('users.create')"
                class="!bg-blue-600 hover:!bg-blue-700 !text-white">
                Crear usuario
            </flux:button>
        </div>

        <form method="GET" action="{{ route('users.index') }}" class="flex flex-wrap gap-4 items-end">

            <div class="flex flex-col">
                <label for="role" class="text-sm font-semibold text-[#241178]">
                    Tipo de usuario
                </label>
                <select name="role" id="role"
                    class="border border-zinc-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-600 outline-none">
                    <option value="">Todos</option>
                    <option value="union" {{ request('role') === 'union' ? 'selected' : '' }}>
                        Usuario Sindicato
                    </option>
                    <option value="worker" {{ request('role') === 'worker' ? 'selected' : '' }}>
                        Usuario Trabajador
                    </option>
                </select>
            </div>

            <flux:button icon="magnifying-glass" variant="primary" type="submit"
                class="!bg-gray-500 hover:!bg-gray-600 !text-white">
                Buscar
            </flux:button>

            <flux:button icon="arrow-path" variant="primary" :href="route('users.index')"
                class="!bg-green-600 hover:!bg-green-700 !text-white">
                Actualizar
            </flux:button>
        </form>

        <div class="overflow-x-auto border border-zinc-200 rounded-xl shadow-sm">

            <table class="min-w-full divide-y divide-zinc-200">
                <thead class="bg-zinc-100">
                    <tr>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-black">Nombre</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-black">Rol</th>
                        <th class="px-4 py-3 text-left text-sm font-semibold text-black">Estado</th>
                        <th class="px-4 py-3 text-center text-sm font-semibold text-black">Acciones</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-zinc-200 bg-white">
                    @forelse ($users as $user)
                        <tr class="hover:bg-zinc-50 transition">

                            <td class="px-4 py-3 text-sm text-black">
                                {{ $user->name }}
                            </td>

                            <td class="px-4 py-3 text-sm capitalize text-black">
                                @switch($user->role)
                                    @case('union')
                                        Usuario Sindicato
                                    @break

                                    @case('worker')
                                        Usuario Trabajador
                                    @break

                                    @default
                                        {{ $user->role }}
                                @endswitch
                            </td>

                            <td class="px-4 py-3 text-sm">
                                @if ($user->active)
                                    <span class="text-green-700 font-semibold">Activo</span>
                                @else
                                    <span class="text-red-600 font-semibold">Inactivo</span>
                                @endif
                            </td>

                            <td class="px-4 py-3 text-sm">
                                <div class="flex items-center justify-center gap-3">

                                    <flux:button size="xs" icon="pencil-square" variant="primary"
                                        :href="route('users.edit', $user->id)"
                                        class="!bg-gray-500 hover:!bg-gray-600 !text-white">
                                        Editar
                                    </flux:button>

                                    <form method="POST" action="{{ route('users.toggle', $user->id) }}"
                                        onsubmit="return confirm('¿Deseas cambiar el estado de este usuario?')">
                                        @csrf
                                        @method('PATCH')

                                        <flux:button size="xs"
                                            icon="{{ $user->active ? 'x-circle' : 'check-circle' }}" variant="primary"
                                            type="submit"
                                            class="{{ $user->active ? '!bg-red-600 hover:!bg-red-700' : '!bg-green-600 hover:!bg-green-700' }} !text-white">
                                            {{ $user->active ? 'Desactivar' : 'Activar' }}
                                        </flux:button>
                                    </form>

                                    <form method="POST" action="{{ route('users.destroy', $user->id) }}"
                                        onsubmit="return confirm('¿Seguro que deseas eliminar este usuario?')">
                                        @csrf
                                        @method('DELETE')

                                        <flux:button size="xs" icon="trash" variant="danger" type="submit"
                                            class="!bg-red-600 hover:!bg-red-700 !text-white">
                                            Eliminar
                                        </flux:button>
                                    </form>

                                </div>
                            </td>
                        </tr>

                        @empty
                            <tr>
                                <td colspan="4" class="px-4 py-6 text-center text-sm text-zinc-500">
                                    No hay usuarios registrados.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

            </div>

        </div>

    </x-layouts.app>
