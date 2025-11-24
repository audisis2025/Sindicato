{{-- ===========================================================
 Vista: union/requests/index.blade.php
 Adaptada a RF-04 (Estados completos)
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
                        <th class="p-2">#</th>
                        <th class="p-2">Trabajador</th>
                        <th class="p-2">Trámite</th>
                        <th class="p-2">Estado</th>
                        <th class="p-2">Fecha</th>
                        <th class="p-2 text-center">Acciones</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($requests as $i => $req)

                        @php
                            $mapping = [
                                'started'          => ['Iniciado', 'text-blue-600'],
                                'pending_worker'   => ['Pendiente trabajador', 'text-amber-600'],
                                'pending_union'    => ['Pendiente sindicato', 'text-purple-600'],
                                'in_progress'      => ['En proceso', 'text-sky-600'],
                                'completed'        => ['Finalizado', 'text-green-600'],
                                'cancelled'        => ['Cancelado', 'text-gray-600'],
                                'rejected'         => ['Rechazado', 'text-red-600'],
                                'pending'          => ['Pendiente', 'text-amber-600'], 
                            ];

                            [$estadoTexto, $color] = $mapping[$req->status] ?? ['Desconocido', 'text-gray-500'];
                        @endphp

                        <tr class="border-t border-[#272800]/10 hover:bg-[#F9F9F9] transition">

                            <td class="p-2">{{ $i + 1 }}</td>
                            <td class="p-2">{{ $req->user->name }}</td>
                            <td class="p-2">{{ $req->procedure->name }}</td>

                            <td class="p-2">
                                <span class="{{ $color }} font-semibold">
                                    {{ $estadoTexto }}
                                </span>
                            </td>

                            <td class="p-2">
                                {{ optional($req->created_at)->format('d/m/Y') }}
                            </td>

                            <td class="p-2 text-center">
                                <a href="{{ route('union.procedures.requests.show', $req->id) }}"
                                   class="bg-[#241178] hover:bg-[#1e0f6b] text-white px-3 py-1 rounded-md text-sm transition">
                                    Ver detalle
                                </a>
                            </td>

                        </tr>

                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-4 text-gray-500">
                                No hay solicitudes registradas.
                            </td>
                        </tr>
                    @endforelse
                </tbody>

            </table>
        </div>
    </div>

</x-layouts.app>
