<x-layouts.app>
    <div class="flex flex-col gap-6">

        <div class="flex justify-between items-center">
            <h1 class="text-3xl font-bold text-[#DE6601]">Gestión de Usuarios</h1>

            <a href="{{ route('users.create') }}"
                class="flex items-center gap-2 bg-[#DE6601] hover:bg-[#EE0000] text-white font-semibold py-2 px-4 rounded-lg transition">
                <x-heroicon-o-plus class="w-5 h-5 text-white" />
                Crear usuario
            </a>
        </div>

        <form method="GET" action="{{ route('users.index') }}" class="flex flex-wrap gap-4 items-center bg-white p-4 border border-[#D9D9D9] rounded-lg">
            <div class="flex flex-col">
                <label for="role" class="text-sm font-semibold text-[#241178]">Tipo de usuario</label>
                <select name="role" id="role"
                        class="border border-[#D9D9D9] rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-[#DE6601] outline-none">
                    <option value="">Todos</option>
                    <option value="admin" {{ request('role') === 'admin' ? 'selected' : '' }}>Administrador</option>
                    <option value="union" {{ request('role') === 'union' ? 'selected' : '' }}>Usuario Sindicato</option>
                    <option value="worker" {{ request('role') === 'worker' ? 'selected' : '' }}>Usuario Trabajador</option>
                </select>
            </div>

            <button type="submit"
                class="bg-[#241178] hover:bg-[#1A0D5A] text-white font-semibold px-4 py-2 rounded-lg transition h-10 mt-5">
                Filtrar
            </button>

            <a href="{{ route('users.index') }}"
                class="bg-[#DE6601] hover:bg-[#EE0000] text-white font-semibold px-4 py-2 rounded-lg transition h-10 mt-5">
                Limpiar
            </a>
        </form>

        <table class="w-full border-collapse border border-[#D9D9D9] text-sm font-[Inter] rounded-lg overflow-hidden">
            <thead class="bg-[#241178] text-white">
                <tr>
                    <th class="p-2 text-left">Usuario</th>
                    <th class="p-2 text-left">Nombre</th>
                    <th class="p-2 text-left">Rol</th>
                    <th class="p-2 text-left">Estado</th>
                    <th class="p-2 text-center">Acciones</th>
                </tr>
            </thead>

            <tbody>
                @forelse ($users as $user)
                    <tr class="border-t border-[#D9D9D9] hover:bg-[#F4F1FA] transition">

                        <td class="p-2">{{ $user->username }}</td>

                        <td class="p-2">{{ $user->name }}</td>

                        <td class="p-2 capitalize">
                            @if ($user->role === 'union')
                                Usuario Sindicato
                            @elseif ($user->role === 'worker')
                                Usuario Trabajador
                            @elseif ($user->role === 'admin')
                                Administrador
                            @else
                                {{ $user->role }}
                            @endif
                        </td>

                        <td class="p-2">
                            @if ($user->active)
                                <span class="text-green-600 font-semibold">Activo</span>
                            @else
                                <span class="text-red-600 font-semibold">Inactivo</span>
                            @endif
                        </td>

                        <td class="p-2 flex flex-wrap gap-2 justify-center">

                            <a href="{{ route('users.edit', $user->id) }}"
                                class="bg-[#241178] hover:bg-[#1A0D5A] text-white px-3 py-1 rounded-md text-sm transition">
                                Editar
                            </a>

                            <form action="{{ route('users.toggle', $user->id) }}" method="POST"
                                  onsubmit="return confirm('¿Deseas cambiar el estado de este usuario?')">
                                @csrf
                                @method('PATCH')

                                <button type="submit"
                                    class="px-3 py-1 rounded-md text-sm text-white font-semibold
                                    {{ $user->active ? 'bg-yellow-600 hover:bg-yellow-700' : 'bg-green-600 hover:bg-green-700' }} transition">
                                    {{ $user->active ? 'Desactivar' : 'Activar' }}
                                </button>
                            </form>

                            <form action="{{ route('users.destroy', $user->id) }}" method="POST"
                                  onsubmit="return confirm('¿Seguro que deseas eliminar este usuario?')">
                                @csrf
                                @method('DELETE')

                                <button type="submit"
                                    class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded-md text-sm transition">
                                    Eliminar
                                </button>
                            </form>

                        </td>
                    </tr>

                @empty
                    <tr>
                        <td colspan="5" class="text-center py-4 text-gray-500">
                            No hay usuarios registrados.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

    </div>
</x-layouts.app>
