{{-- ===========================================================
 Nombre de la clase: reports-index.blade.php
 Descripción: Listado general de solicitudes de trámite enviadas por los trabajadores.
 Fecha de creación: 04/11/2025
 Elaboró: Iker Piza
 Fecha de liberación: 04/11/2025
 Autorizó: Líder Técnico
 Versión: 1.0
 Tipo de mantenimiento: Creación.
 Descripción del mantenimiento: Implementa RF13 y RF14 mostrando el estado
 de los trámites de trabajadores con acceso a revisión detallada.
 Responsable: Iker Piza
 Revisor: QA SINDISOFT
=========================================================== --}}

<x-layouts.app :title="__('Solicitudes de trabajadores')">

    <div class="flex flex-col gap-6 p-6 w-full">

        <!-- 🔸 Encabezado -->
        <div class="flex justify-between items-center">
            <h1 class="text-3xl font-[Poppins] font-bold text-[#DC6601]">
                Solicitudes de trabajadores
            </h1>
            <span class="text-[#241178] font-[Inter]">
                Revisa, aprueba o notifica errores en los trámites enviados.
            </span>
        </div>

        <!-- 📊 Tabla principal -->
        <div class="overflow-x-auto bg-white border border-[#D9D9D9] rounded-2xl shadow-md">
            <table class="w-full border-collapse text-sm font-[Inter]">
                <thead class="bg-[#241178] text-white">
                    <tr>
                        <th class="p-2 text-left">Trabajador</th>
                        <th class="p-2 text-left">Trámite</th>
                        <th class="p-2 text-center">Fecha de solicitud</th>
                        <th class="p-2 text-center">Estado</th>
                        <th class="p-2 text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($solicitudes as $solicitud)
                        <tr class="border-t border-[#272800]/10 hover:bg-[#F9F9F9] transition">
                            <td class="p-2">{{ $solicitud->trabajador->name }}</td>
                            <td class="p-2">{{ $solicitud->tramite->nombre }}</td>
                            <td class="p-2 text-center">
                                {{ $solicitud->created_at->format('d/m/Y') }}
                            </td>
                            <td class="p-2 text-center">
                                @if ($solicitud->estado === 'Pendiente')
                                    <span class="text-[#DC6601] font-semibold">Pendiente</span>
                                @elseif ($solicitud->estado === 'Completado')
                                    <span class="text-green-600 font-semibold">Completado</span>
                                @elseif ($solicitud->estado === 'Rechazado')
                                    <span class="text-red-600 font-semibold">Rechazado</span>
                                @else
                                    <span class="text-gray-600">—</span>
                                @endif
                            </td>
                            <td class="p-2 text-center">
                                <a href="{{ route('union.procedures.requests.show', $solicitud->id) }}"
                                    class="bg-[#DC6601] hover:bg-[#EE0000] text-white px-3 py-1 rounded-md text-sm transition">
                                    Revisar
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-4 text-gray-500">
                                No hay solicitudes registradas en este momento.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</x-layouts.app>
