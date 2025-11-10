{{-- ===========================================================
 Nombre de la vista: index.blade.php
 Módulo: Panel del Trabajador
 Descripción: Vista principal donde el trabajador puede ver sus trámites activos,
 finalizados y disponibles para iniciar.
 Fecha de creación: 07/11/2025
 Elaboró: Iker Piza
 Versión: 1.0
 Tipo de mantenimiento: Creación.
 Descripción del mantenimiento: Implementa diseño institucional PRO-Laravel V3.2
 con paleta oficial (#241178, #DC6601) y estructura unificada.
=========================================================== --}}

<x-layouts.app :title="__('Panel del Trabajador')">
    <div class="flex flex-col gap-6 p-6">

        <!-- 🔸 Encabezado -->
        <div class="flex justify-between items-center">
            <h1 class="text-3xl font-[Poppins] font-bold text-[#DC6601]">
                Mis Trámites
            </h1>
        </div>

        <!-- 🟠 Trámites Activos -->
        <div class="bg-white border border-[#D9D9D9] rounded-2xl shadow-md p-5">
            <h2 class="text-xl font-semibold text-[#241178] font-[Poppins] mb-3">Trámites activos</h2>

            <table class="w-full border-collapse border border-[#272800]/30 text-sm font-[Inter]">
                <thead class="bg-[#241178] text-white">
                    <tr>
                        <th class="p-2 text-left">#</th>
                        <th class="p-2 text-left">Nombre del Trámite</th>
                        <th class="p-2 text-left">Estado</th>
                        <th class="p-2 text-left">Fecha de Solicitud</th>
                        <th class="p-2 text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($tramitesActivos as $i => $t)
                        <tr class="border-t border-[#272800]/20 hover:bg-[#F9F9F9] transition">
                            <td class="p-2">{{ $i + 1 }}</td>
                            <td class="p-2">{{ $t->tramite->nombre ?? '—' }}</td>
                            <td class="p-2">
                                @php
                                    $color = match ($t->estado) {
                                        'Completado' => 'text-green-600',
                                        'Rechazado' => 'text-red-600',
                                        default => 'text-[#DC6601]',
                                    };
                                @endphp
                                <span class="{{ $color }} font-semibold">
                                    {{ $t->estado ?? '—' }}
                                </span>
                            </td>
                            <td class="p-2">{{ $t->created_at?->format('d/m/Y') ?? '—' }}</td>
                            <td class="p-2 text-center space-x-2">
                                <!-- 👁️ Ver detalle -->
                                <a href="{{ route('worker.requests.show', $t->id) }}"
                                    class="bg-[#241178] hover:bg-[#1e0f6b] text-white px-3 py-1 rounded-md text-sm transition">
                                    Ver detalle
                                </a>


                                <!-- ❌ Cancelar trámite -->
                                <form action="{{ route('worker.procedures.cancel', $t->id) }}" method="POST"
                                    class="inline-block"
                                    onsubmit="return confirm('¿Seguro que deseas cancelar este trámite?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded-md text-sm transition">
                                        Cancelar
                                    </button>
                                </form>
                            </td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-4 text-gray-500">No tienes trámites activos.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- 🟢 Trámites Finalizados -->
        <div class="bg-white border border-[#D9D9D9] rounded-2xl shadow-md p-5">
            <h2 class="text-xl font-semibold text-[#241178] font-[Poppins] mb-3">Historial de trámites finalizados</h2>

            <table class="w-full border-collapse border border-[#272800]/30 text-sm font-[Inter]">
                <thead class="bg-[#241178] text-white">
                    <tr>
                        <th class="p-2 text-left">#</th>
                        <th class="p-2 text-left">Nombre del Trámite</th>
                        <th class="p-2 text-left">Estado</th>
                        <th class="p-2 text-left">Fecha de Finalización</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($tramitesFinalizados as $i => $t)
                        <tr class="border-t border-[#272800]/20 hover:bg-[#F9F9F9] transition">
                            <td class="p-2">{{ $i + 1 }}</td>
                            <td class="p-2">{{ $t->tramite->nombre ?? '—' }}</td>
                            <td class="p-2">
                                <span
                                    class="{{ $t->estado === 'Completado' ? 'text-green-600' : 'text-red-600' }} font-semibold">
                                    {{ $t->estado }}
                                </span>
                            </td>
                            <td class="p-2">{{ $t->updated_at?->format('d/m/Y') ?? '—' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center py-4 text-gray-500">No hay trámites finalizados.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- 🔵 Trámites Disponibles -->
        <div class="bg-white border border-[#D9D9D9] rounded-2xl shadow-md p-5">
            <h2 class="text-xl font-semibold text-[#241178] font-[Poppins] mb-3">Trámites disponibles para iniciar</h2>

            <table class="w-full border-collapse border border-[#272800]/30 text-sm font-[Inter]">
                <thead class="bg-[#241178] text-white">
                    <tr>
                        <th class="p-2 text-left">#</th>
                        <th class="p-2 text-left">Nombre del Trámite</th>
                        <th class="p-2 text-left">Descripción</th>
                        <th class="p-2 text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($tramitesDisponibles as $i => $t)
                        <tr class="border-t border-[#272800]/20 hover:bg-[#F9F9F9] transition">
                            <td class="p-2">{{ $i + 1 }}</td>
                            <td class="p-2 font-semibold text-[#000000]">{{ $t->nombre }}</td>
                            <td class="p-2 text-[#272800]">{{ $t->descripcion ?? 'Sin descripción' }}</td>
                            <td class="p-2 text-center">
                                <form action="{{ route('worker.procedures.start', $t->id) }}" method="POST">
                                    @csrf
                                    <button type="submit"
                                        class="bg-[#DC6601] hover:bg-[#EE0000] text-white px-3 py-1 rounded-md text-sm transition">
                                        Iniciar trámite
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center py-4 text-gray-500">No hay trámites disponibles.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-layouts.app>
