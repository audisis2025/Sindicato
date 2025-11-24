<x-layouts.app :title="__('Solicitudes de trabajadores')">

    <div class="flex flex-col gap-6 p-6 w-full">

        <!-- Encabezado -->
        <div class="flex justify-between items-center">
            <h1 class="text-3xl font-[Poppins] font-bold text-[#DC6601]">
                Solicitudes de trabajadores
            </h1>
            <span class="text-[#241178] font-[Inter]">
                Revisa, aprueba o notifica errores en los trámites enviados.
            </span>
        </div>

        <!-- Tabla principal -->
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
                    @forelse ($requests as $req)
                        <tr class="border-t border-[#272800]/10 hover:bg-[#F9F9F9] transition">

                            <!-- Trabajador -->
                            <td class="p-2">
                                {{ $req->user->name ?? '—' }}
                            </td>

                            <!-- Trámite -->
                            <td class="p-2">
                                {{ $req->procedure->name ?? '—' }}
                            </td>

                            <!-- Fecha -->
                            <td class="p-2 text-center">
                                {{ $req->created_at->format('d/m/Y') }}
                            </td>

                            <!-- Estado -->
                            <td class="p-2 text-center">
                                @if ($req->status === 'pending')
                                    <span class="text-[#DC6601] font-semibold">Pendiente</span>
                                @elseif ($req->status === 'in_progress')
                                    <span class="text-blue-600 font-semibold">En proceso</span>
                                @elseif ($req->status === 'completed')
                                    <span class="text-green-600 font-semibold">Completado</span>
                                @elseif ($req->status === 'rejected')
                                    <span class="text-red-600 font-semibold">Rechazado</span>
                                @else
                                    <span class="text-gray-600">—</span>
                                @endif
                            </td>

                            <!-- Acciones -->
                            <td class="p-2 text-center">
                                <a href="{{ route('union.procedures.requests.show', $req->id) }}"
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
