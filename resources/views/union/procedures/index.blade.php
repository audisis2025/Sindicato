{{-- ===========================================================
 Nombre de la clase: procedures-index.blade.php
 Descripción: Vista para listar y administrar los trámites creados por el Sindicato.
 Fecha de creación: 03/11/2025
 Elaboró: Iker Piza
 Fecha de liberación: 03/11/2025
 Autorizó: Líder Técnico
 Versión: 1.0
 Tipo de mantenimiento: Creación.
 Descripción del mantenimiento: Implementa tabla institucional para gestión de trámites (RF06–RF09, RF14) conforme al Manual PRO-Laravel V3.2.
 Responsable: Iker Piza
 Revisor: QA SINDISOFT
=========================================================== --}}

<x-layouts.app :title="__('Gestión de trámites')">
    <div class="flex flex-col gap-6 p-6 w-full">

        <!-- 🔸 Título y botón de alta -->
        <div class="flex justify-between items-center">
            <h1 class="text-3xl font-[Poppins] font-bold text-[#DC6601]">
                Gestión de Trámites
            </h1>

            <a href="{{ route('union.procedures.create') }}"
                class="bg-[#DC6601] hover:bg-[#EE0000] text-white font-semibold py-2 px-4 rounded-lg transition">
                + Crear nuevo trámite
            </a>
        </div>

        <!-- 📋 Tabla de trámites -->
        <div class="overflow-x-auto bg-white border border-[#D9D9D9] rounded-2xl shadow-md">
            <table class="w-full border-collapse text-sm font-[Inter]">
                <thead class="bg-[#241178] text-white">
                    <tr>
                        <th class="p-2 text-left">Nombre del trámite</th>
                        <th class="p-2 text-left">Número de pasos</th>
                        <th class="p-2 text-left">Tiempo estimado</th>
                        <th class="p-2 text-left">Flujo alterno</th>
                        <th class="p-2 text-left">Fechas</th>
                        <th class="p-2 text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($procedures as $procedure)
                        <tr class="border-t border-[#272800]/10 hover:bg-[#F9F9F9] transition">
                            <td class="p-2 max-w-[200px] truncate">{{ $procedure->nombre }}</td>
                            <td class="p-2">{{ $procedure->num_pasos ?? '—' }}</td>
                            <td class="p-2">{{ $procedure->tiempo_estimado ?? '—' }} días</td>
                            <td class="p-2">{{ $procedure->flujo_alterno ?? 'N/A' }}</td>
                            <td class="p-2 text-sm">
                                Apertura: <b>{{ $procedure->fecha_apertura ?? '—' }}</b><br>
                                Cierre: <b>{{ $procedure->fecha_cierre ?? '—' }}</b>
                            </td>
                            <td class="p-2 flex flex-wrap gap-2 justify-center">
                                <!-- Ver -->
                                <a href="{{ route('union.procedures.show', $procedure->id) }}"
                                    class="bg-[#241178] hover:bg-[#3828a8] text-white px-3 py-1 rounded-md text-sm transition">
                                    👁️ Ver
                                </a>

                                <!-- Editar -->
                                <a href="{{ route('union.procedures.edit', $procedure->id ?? 0) }}"
                                    class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded-md text-sm transition">
                                    ✏️ Editar
                                </a>

                                <!-- Eliminar -->
                                <form action="{{ route('union.procedures.destroy', $procedure->id) }}" method="POST"
                                    onsubmit="return confirm('⚠️ ¿Seguro que deseas eliminar este trámite?')">
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
                            <td colspan="6" class="text-center py-4 text-gray-500">
                                No hay trámites registrados.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-layouts.app>
