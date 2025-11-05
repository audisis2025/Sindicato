<x-layouts.app>
    <div class="flex flex-col gap-6">
        <!-- 🔸 Título y botón de alta -->
        <div class="flex justify-between items-center">
            <h1 class="text-3xl font-bold text-[#DC6601]">Gestión de Usuarios</h1>
            <a href="{{ route('users.create') }}"
                class="bg-[#DC6601] hover:bg-[#EE0000] text-white font-semibold py-2 px-4 rounded-lg transition">
                + Crear nuevo usuario
            </a>
        </div>

        <!-- 📋 Tabla de usuarios -->
        <table class="w-full border-collapse border border-[#272800]/30">
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
                    <tr class="border-t border-[#272800]/20 hover:bg-[#F9F9F9] transition">
                        <td class="p-2">{{ $user->usuario }}</td>
                        <td class="p-2">{{ $user->name }}</td>
                        <td class="p-2 capitalize">{{ $user->rol }}</td>
                        <td class="p-2">
                            @if ($user->activo)
                                <span class="text-green-600 font-semibold">Activo</span>
                            @else
                                <span class="text-red-600 font-semibold">Inactivo</span>
                            @endif
                        </td>

                        <!-- 🛠️ Acciones -->
                        <td class="p-2 flex flex-wrap gap-2 justify-center">
                            <!-- Editar -->
                            <a href="{{ route('users.edit', $user->id) }}"
                                class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded-md text-sm transition">
                                ✏️ Editar
                            </a>

                            <!-- Activar/Inactivar -->
                            <form action="{{ route('users.toggle', $user->id) }}" method="POST"
                                onsubmit="return confirm('¿Deseas cambiar el estado de este usuario?')">
                                @csrf
                                @method('PATCH')
                                <button type="submit"
                                    class="px-3 py-1 rounded-md text-sm font-semibold text-white {{ $user->activo ? 'bg-yellow-600 hover:bg-yellow-700' : 'bg-green-600 hover:bg-green-700' }} transition">
                                    {{ $user->activo ? 'Desactivar' : 'Activar' }}
                                </button>
                            </form>

                            <!-- Eliminar -->
                            <form action="{{ route('users.destroy', $user->id) }}" method="POST"
                                onsubmit="return confirm('⚠️ ¿Seguro que deseas eliminar este usuario?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded-md text-sm transition">
                                    🗑️ Eliminar
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
