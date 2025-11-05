{{-- ===========================================================
 Nombre de la vista: index.blade.php
 Módulo: Solicitudes de trabajadores (RF13–RF14)
=========================================================== --}}
<x-layouts.app :title="__('Solicitudes de trabajadores')">
    <div class="flex flex-col gap-6 p-6 w-full">
        <div class="flex justify-between items-center">
            <h1 class="text-3xl font-[Poppins] font-bold text-[#DC6601]">
                Solicitudes de trabajadores
            </h1>
        </div>

        <div class="overflow-x-auto bg-white border border-[#D9D9D9] rounded-2xl shadow-md">
            <table class="w-full border-collapse text-sm font-[Inter]">
                <thead class="bg-[#241178] text-white">
                    <tr>
                        <th class="p-2 text-left">#</th>
                        <th class="p-2 text-left">Trabajador</th>
                        <th class="p-2 text-left">Trámite</th>
                        <th class="p-2 text-left">Estado</th>
                        <th class="p-2 text-left">Fecha</th>
                        <th class="p-2 text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($solicitudes as $i => $s)
                        <tr class="border-t border-[#272800]/10 hover:bg-[#F9F9F9] transition">
                            <td class="p-2">{{ $i + 1 }}</td>
                            <td class="p-2">{{ $s->trabajador->name ?? '—' }}</td>
                            <td class="p-2">{{ $s->tramite->nombre ?? ucfirst($s->tipo_tramite ?? '—') }}</td>
                            <td class="p-2">
                                <span
                                    class="{{ $s->estado === 'Completado'
                                        ? 'text-green-600'
                                        : ($s->estado === 'Rechazado'
                                            ? 'text-red-600'
                                            : 'text-[#DC6601]') }} font-semibold">
                                    {{ $s->estado ?? '—' }}
                                </span>
                            </td>
                            <td class="p-2">{{ optional($s->created_at)->format('d/m/Y') }}</td>
                            <td class="p-2 text-center">
                                <a href="{{ route('union.procedures.requests.show', $s->id) }}"
                                    class="bg-[#241178] hover:bg-[#1e0f6b] text-white px-3 py-1 rounded-md text-sm transition">
                                    👁️ Ver detalle
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-4 text-gray-500">No hay solicitudes registradas.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-layouts.app>
