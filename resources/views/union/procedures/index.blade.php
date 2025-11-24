{{-- ===========================================================
 Nombre de la clase: procedures-index.blade.php
 Descripción: Vista para listar y administrar los trámites creados por el Sindicato.
 Fecha de creación: 03/11/2025
 Elaboró: Iker Piza
 Fecha de liberación: 03/11/2025
 Autorizó: Líder Técnico
 Versión: 1.1
 Tipo de mantenimiento: Correctivo.
 Descripción del mantenimiento: Se actualizan campos conforme al modelo Procedure (name, steps_count, opening_date, etc.) y al estándar PRO-Laravel V3.4.
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
               class="inline-flex items-center gap-2 bg-[#DC6601] hover:bg-[#EE0000] text-white font-semibold px-4 py-2 rounded-lg transition">
               <x-heroicon-o-plus class="w-5 h-5" />
                Crear nuevo trámite
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

                            <!-- Nombre -->
                            <td class="p-2 max-w-[200px] truncate">
                                {{ $procedure->name }}
                            </td>

                            <!-- Número de pasos -->
                            <td class="p-2">
                                {{ $procedure->steps_count ?? '—' }}
                            </td>

                            <!-- Tiempo estimado -->
                            <td class="p-2">
                                {{ $procedure->estimated_days ? $procedure->estimated_days . ' días' : '—' }}
                            </td>

                            <!-- Flujo alterno -->
                            <td class="p-2">
                                {{ $procedure->has_alternate_flow ? 'Sí' : 'No' }}
                            </td>

                            <!-- Fechas -->
                            <td class="p-2 text-sm">
                                Apertura: <b>{{ $procedure->opening_date ?? '—' }}</b><br>
                                Cierre: <b>{{ $procedure->closing_date ?? '—' }}</b>
                            </td>

                            <!-- Acciones -->
                            <td class="p-2 flex flex-wrap gap-2 justify-center">

                                <!-- Ver -->
                                <a href="{{ route('union.procedures.show', $procedure->id) }}"
                                   class="bg-[#241178] hover:bg-[#3828a8] text-white px-3 py-1 rounded-md text-sm transition">
                                    Ver
                                </a>

                                <!-- Editar -->
                                <a href="{{ route('union.procedures.edit', $procedure->id) }}"
                                   class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded-md text-sm transition">
                                    Editar
                                </a>

                                <!-- Eliminar -->
                                <form action="{{ route('union.procedures.destroy', $procedure->id) }}"
                                      method="POST"
                                      onsubmit="return confirm('⚠️ ¿Seguro que deseas eliminar este trámite?')">

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
