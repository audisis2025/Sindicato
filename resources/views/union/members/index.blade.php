{{-- ===========================================================
 Nombre de la clase: members-index.blade.php
 Descripción: Vista para listar y administrar trabajadores registrados.
 Versión: 1.3 (Con filtros por nombre y género)
=========================================================== --}}

<x-layouts.app :title="__('Trabajadores registrados')">
    <div class="flex flex-col gap-6 p-6 w-full">

        <!-- 🔸 Título y botón de alta -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
            <h1 class="text-3xl font-[Poppins] font-bold text-[#DC6601]">
                Gestión de Trabajadores
            </h1>

            <a href="{{ route('union.members.create') }}"
                class="inline-flex items-center gap-2 bg-[#DC6601] hover:bg-[#EE0000] text-white font-semibold px-4 py-2 rounded-lg transition">
                <x-heroicon-o-plus class="w-5 h-5" />
                Registrar nuevo trabajador
            </a>
        </div>

        <!-- 🔎 Filtros -->
        <form method="GET" action="{{ route('union.members.index') }}"
              class="bg-white border border-[#D9D9D9] p-4 rounded-2xl shadow-md font-[Inter] grid grid-cols-1 sm:grid-cols-3 gap-4">

            <!-- Filtro por nombre -->
            <div>
                <label class="block font-semibold text-[#272800] mb-1">Nombre</label>
                <input type="text" name="name" value="{{ request('name') }}"
                       placeholder="Buscar por nombre..."
                       class="w-full border border-[#D9D9D9] rounded-lg px-3 py-2 
                              focus:ring-2 focus:ring-[#DC6601] outline-none">
            </div>

            <!-- Filtro por género -->
            <div>
                <label class="block font-semibold text-[#272800] mb-1">Género</label>
                <select name="gender"
                        class="w-full border border-[#D9D9D9] rounded-lg px-3 py-2 
                               focus:ring-2 focus:ring-[#DC6601] outline-none">
                    <option value="">Todos</option>
                    <option value="H"  {{ request('gender') === 'H' ? 'selected' : '' }}>Hombre</option>
                    <option value="M"  {{ request('gender') === 'M' ? 'selected' : '' }}>Mujer</option>
                    <option value="ND" {{ request('gender') === 'ND' ? 'selected' : '' }}>No definido</option>
                    <option value="X"  {{ request('gender') === 'X' ? 'selected' : '' }}>Prefiero no decirlo</option>
                </select>
            </div>

            <!-- Botón -->
            <div class="flex items-end">
                <button class="w-full sm:w-auto px-4 py-2 bg-[#241178] hover:bg-[#3828a8] 
                               text-white font-semibold rounded-lg transition">
                    Filtrar
                </button>
            </div>
        </form>

        <!-- 📋 Tabla -->
        <div class="overflow-x-auto bg-white border border-[#D9D9D9] rounded-2xl shadow-md">
            <table class="w-full border-collapse text-sm font-[Inter]">
                <thead class="bg-[#241178] text-white">
                    <tr>
                        <th class="p-2 text-left">Nombre</th>
                        <th class="p-2 text-left">Correo</th>
                        <th class="p-2 text-left">CURP</th>
                        <th class="p-2 text-left">RFC</th>
                        <th class="p-2 text-left">Sexo</th>
                        <th class="p-2 text-left">Clave presupuestal</th>
                        <th class="p-2 text-center">Estado</th>
                        <th class="p-2 text-center">Acciones</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($workers as $worker)
                        <tr class="border-t border-[#272800]/10 hover:bg-[#F9F9F9] transition">

                            <td class="p-2 max-w-[140px] truncate" title="{{ $worker->name }}">
                                {{ $worker->name ?? '—' }}
                            </td>

                            <td class="p-2">{{ $worker->email ?? '—' }}</td>
                            <td class="p-2">{{ $worker->curp ?? '—' }}</td>
                            <td class="p-2">{{ $worker->rfc ?? '—' }}</td>

                            <td class="p-2">
                                @switch($worker->gender)
                                    @case('H') Hombre @break
                                    @case('M') Mujer @break
                                    @case('ND') No definido @break
                                    @case('X') Pref. no decirlo @break
                                    @default —
                                @endswitch
                            </td>

                            <td class="p-2">{{ $worker->budget_key ?? '—' }}</td>

                            <td class="p-2 text-center">
                                @if ($worker->active)
                                    <span class="text-green-600 font-semibold">Activo</span>
                                @else
                                    <span class="text-red-600 font-semibold">Inactivo</span>
                                @endif
                            </td>

                            <!-- Acciones -->
                            <td class="p-2 flex flex-wrap gap-2 justify-center">
                                <a href="{{ route('union.members.edit', $worker->id) }}"
                                    class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded-md text-sm transition">
                                    Editar
                                </a>

                                <form action="{{ route('union.members.destroy', $worker->id) }}" method="POST"
                                      onsubmit="return confirm('⚠️ ¿Seguro que deseas eliminar este trabajador?')">
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
                            <td colspan="8" class="text-center py-4 text-gray-500">
                                No hay trabajadores registrados.
                            </td>
                        </tr>
                    @endforelse
                </tbody>

            </table>
        </div>

    </div>
</x-layouts.app>
