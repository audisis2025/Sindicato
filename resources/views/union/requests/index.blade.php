{{-- 
* Nombre de la vista           : requests-index.blade.php
* Descripción de la vista      : Listado de solicitudes de trabajadores para revisión y gestión.
* Fecha de creación            : 27/11/2025
* Elaboró                      : Iker Piza
* Fecha de liberación          : 27/11/2025
* Autorizó                     : Líder Técnico
* Versión                      : 1.1
* Fecha de mantenimiento       : 27/11/2025
* Folio de mantenimiento       : N/A
* Tipo de mantenimiento        : Correctivo y perfectivo
* Descripción del mantenimiento: Homologación completa de tabla, botones y colores según Manual PRO-Laravel V3.4.
* Responsable                  : Iker Piza
* Revisor                      : QA SINDISOFT
--}}

<x-layouts.app :title="__('Solicitudes de trabajadores')">

    <div class="flex flex-col gap-6 p-6 w-full max-w-6xl mx-auto">

        <div class="flex justify-between items-center">
            <h1 class="text-3xl font-bold text-[#DE6601]">
                Solicitudes de trabajadores
            </h1>
        </div>

        <div class="overflow-x-auto bg-white border border-[#D9D9D9] rounded-xl shadow-sm">

            @if ($requests->count() > 0)
                <table class="min-w-full divide-y divide-zinc-200 text-sm font-[Inter]">

                    <thead class="bg-zinc-100">
                        <tr>
                            <th class="px-4 py-3 text-left font-semibold text-black">#</th>
                            <th class="px-4 py-3 text-left font-semibold text-black">Trabajador</th>
                            <th class="px-4 py-3 text-left font-semibold text-black">Trámite</th>
                            <th class="px-4 py-3 text-left font-semibold text-black">Estado</th>
                            <th class="px-4 py-3 text-left font-semibold text-black">Fecha</th>
                            <th class="px-4 py-3 text-center font-semibold text-black">Acciones</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-zinc-200 bg-white">

                        @foreach ($requests as $i => $req)

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

                            <tr class="hover:bg-zinc-50 transition">

                                <td class="px-4 py-3">{{ $i + 1 }}</td>

                                <td class="px-4 py-3 text-black">
                                    {{ $req->user->name ?? '—' }}
                                </td>

                                <td class="px-4 py-3 text-black">
                                    {{ $req->procedure->name ?? '—' }}
                                </td>

                                <td class="px-4 py-3 text-black">
                                    <span class="{{ $color }} font-semibold">
                                        {{ $estadoTexto }}
                                    </span>
                                </td>

                                <td class="px-4 py-3 text-black">
                                    {{ optional($req->created_at)->format('d/m/Y') }}
                                </td>

                                <td class="px-4 py-3 text-center">

                                    <flux:button
                                        size="xs"
                                        icon="eye"
                                        variant="filled"
                                        :href="route('union.requests.show', $req->id)"
                                        class="!bg-blue-600 hover:!bg-blue-700 !text-white"
                                    >
                                        Ver detalle
                                    </flux:button>

                                </td>

                            </tr>

                        @endforeach

                    </tbody>

                </table>
            @else

                <p class="text-center py-4 text-gray-500 text-sm">
                    No hay solicitudes registradas.
                </p>

            @endif

        </div>

    </div>

</x-layouts.app>
