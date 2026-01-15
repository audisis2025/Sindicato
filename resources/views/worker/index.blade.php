{{-- 
* Nombre de la vista          : index.blade.php
* Descripción de la vista     : Vista principal del panel del trabajador, donde se consultan trámites activos,
*                               historial de trámites finalizados y trámites disponibles para iniciar, con acceso
*                               al detalle de cada solicitud y acciones correspondientes.
* Fecha de creación           : 14/01/2026
* Elaboró                     : Iker Piza
* Fecha de liberación         : 14/01/2026
* Autorizó                    :
* Versión                     : 1.0
* Fecha de mantenimiento      :
* Folio de mantenimiento      :
* Tipo de mantenimiento       :
* Descripción del mantenimiento:
* Responsable                 :
* Revisor                     :
--}}

<x-layouts.app :title="__('Panel del Trabajador')">

    <div class="flex flex-col gap-8 p-6 w-full max-w-6xl mx-auto">

        <div class="flex justify-between items-center">
            <h1 class="text-3xl font-bold text-[#DE6601]">
                Mis Trámites
            </h1>
        </div>

        <div class="flex flex-wrap gap-3">

            <flux:button
                type="button"
                id="btn-active"
                icon="check-circle"
                icon-variant="outline"
                variant="primary"
                class="tab-btn !bg-blue-600 hover:!bg-blue-700 !text-white"
                onclick="showSection('active')"
            >
                Trámites activos
            </flux:button>

            <flux:button
                type="button"
                id="btn-history"
                icon="clock"
                icon-variant="outline"
                variant="primary"
                class="tab-btn !bg-gray-500 hover:!bg-gray-600 !text-white"
                onclick="showSection('history')"
            >
                Historial
            </flux:button>

            <flux:button
                type="button"
                id="btn-available"
                icon="plus"
                icon-variant="outline"
                variant="primary"
                class="tab-btn !bg-gray-500 hover:!bg-gray-600 !text-white"
                onclick="showSection('available')"
            >
                Trámites disponibles
            </flux:button>

        </div>

        <section id="section-active" class="section-card">
            <div class="bg-white border border-zinc-200 rounded-2xl shadow-md p-5">
                <h2 class="text-xl font-semibold text-[#241178] mb-4">
                    Trámites activos
                </h2>

                <div class="overflow-x-auto border border-zinc-200 rounded-xl">
                    <table class="min-w-full divide-y divide-zinc-200 text-sm">
                        <thead class="bg-zinc-100">
                            <tr>
                                <th class="px-4 py-3 text-left font-semibold text-black">#</th>
                                <th class="px-4 py-3 text-left font-semibold text-black">Nombre</th>
                                <th class="px-4 py-3 text-left font-semibold text-black">Estado</th>
                                <th class="px-4 py-3 text-left font-semibold text-black">Fecha</th>
                                <th class="px-4 py-3 text-center font-semibold text-black">Acciones</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-zinc-200 bg-white">
                            @forelse ($active_requests as $i => $req)
                                @php
                                    $colors = [
                                        'initiated' => 'text-blue-600',
                                        'in_progress' => 'text-[#DE6601]',
                                        'pending_worker' => 'text-amber-600',
                                        'pending_union' => 'text-purple-600',
                                        'completed' => 'text-green-600',
                                        'cancelled' => 'text-gray-600',
                                        'rejected' => 'text-red-600',
                                    ];

                                    $labels = [
                                        'initiated' => 'Iniciado',
                                        'in_progress' => 'En proceso',
                                        'pending_worker' => 'Pendiente de acción del trabajador',
                                        'pending_union' => 'Pendiente del sindicato',
                                        'completed' => 'Finalizado',
                                        'cancelled' => 'Cancelado',
                                        'rejected' => 'Rechazado',
                                    ];
                                @endphp

                                <tr class="hover:bg-zinc-50 transition">
                                    <td class="px-4 py-3">{{ $i + 1 }}</td>
                                    <td class="px-4 py-3">{{ $req->procedure->name ?? '—' }}</td>

                                    <td class="px-4 py-3 font-semibold">
                                        <span class="{{ $colors[$req->status] ?? 'text-gray-600' }}">
                                            {{ $labels[$req->status] ?? '—' }}
                                        </span>
                                    </td>

                                    <td class="px-4 py-3">{{ optional($req->created_at)->format('d/m/Y') }}</td>

                                    <td class="px-4 py-3 text-center">
                                        <flux:button
                                            size="xs"
                                            variant="filled"
                                            icon="eye"
                                            :href="route('worker.requests.show', $req->id)"
                                            class="!bg-gray-500 hover:!bg-gray-600 !text-white"
                                        >
                                            Ver
                                        </flux:button>
                                    </td>
                                </tr>

                            @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4 text-gray-500">
                                        No hay trámites activos.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </section>

        <section id="section-history" class="section-card hidden">
            <div class="bg-white border border-zinc-200 rounded-2xl shadow-md p-5">
                <h2 class="text-xl font-semibold text-[#241178] mb-4">
                    Historial de trámites finalizados
                </h2>

                <div class="overflow-x-auto border border-zinc-200 rounded-xl">
                    <table class="min-w-full divide-y divide-zinc-200 text-sm">
                        <thead class="bg-zinc-100">
                            <tr>
                                <th class="px-4 py-3 text-left font-semibold text-black">#</th>
                                <th class="px-4 py-3 text-left font-semibold text-black">Nombre</th>
                                <th class="px-4 py-3 text-left font-semibold text-black">Estado</th>
                                <th class="px-4 py-3 text-left font-semibold text-black">Fecha</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-zinc-200 bg-white">
                            @forelse ($finished_requests as $i => $req)
                                @php
                                    $colors = [
                                        'completed' => 'text-green-600',
                                        'cancelled' => 'text-gray-600',
                                        'rejected' => 'text-red-600',
                                    ];

                                    $labels = [
                                        'completed' => 'Finalizado',
                                        'cancelled' => 'Cancelado',
                                        'rejected' => 'Rechazado',
                                    ];
                                @endphp

                                <tr class="hover:bg-zinc-50 transition">
                                    <td class="px-4 py-3">{{ $i + 1 }}</td>
                                    <td class="px-4 py-3">{{ $req->procedure->name ?? '—' }}</td>

                                    <td class="px-4 py-3 font-semibold">
                                        <span class="{{ $colors[$req->status] ?? 'text-gray-600' }}">
                                            {{ $labels[$req->status] ?? '—' }}
                                        </span>
                                    </td>

                                    <td class="px-4 py-3">{{ optional($req->updated_at)->format('d/m/Y') }}</td>
                                </tr>

                            @empty
                                <tr>
                                    <td colspan="4" class="text-center py-4 text-gray-500">
                                        No hay historial.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </section>

        <section id="section-available" class="section-card hidden">
            <div class="bg-white border border-zinc-200 rounded-2xl shadow-md p-5">
                <h2 class="text-xl font-semibold text-[#241178] mb-4">
                    Trámites disponibles para iniciar
                </h2>

                <div class="overflow-x-auto border border-zinc-200 rounded-xl">
                    <table class="min-w-full divide-y divide-zinc-200 text-sm">
                        <thead class="bg-zinc-100">
                            <tr>
                                <th class="px-4 py-3 text-left font-semibold text-black">#</th>
                                <th class="px-4 py-3 text-left font-semibold text-black">Nombre</th>
                                <th class="px-4 py-3 text-left font-semibold text-black">Descripción</th>
                                <th class="px-4 py-3 text-center font-semibold text-black">Acciones</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-zinc-200 bg-white">
                            @forelse ($available_procedures as $i => $proc)
                                <tr class="hover:bg-zinc-50 transition">
                                    <td class="px-4 py-3">{{ $i + 1 }}</td>
                                    <td class="px-4 py-3 font-semibold">{{ $proc->name ?? '—' }}</td>
                                    <td class="px-4 py-3">{{ $proc->description ?? 'Sin descripción' }}</td>

                                    <td class="px-4 py-3 text-center">
                                        <form method="POST" action="{{ route('worker.procedures.start', $proc->id) }}">
                                            @csrf
                                            <flux:button
                                                size="xs"
                                                type="submit"
                                                variant="primary"
                                                icon="plus"
                                                icon-variant="outline"
                                                class="!bg-blue-600 hover:!bg-blue-700 !text-white"
                                            >
                                                Iniciar
                                            </flux:button>
                                        </form>
                                    </td>
                                </tr>

                            @empty
                                <tr>
                                    <td colspan="4" class="text-center py-4 text-gray-500">
                                        No hay trámites disponibles.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </section>

    </div>

    <script>
        function showSection(section) {
            document.querySelectorAll(".section-card").forEach(function (s) {
                s.classList.add("hidden");
            });

            document.getElementById("section-" + section).classList.remove("hidden");

            document.getElementById("btn-active").classList.remove("!bg-blue-600", "!bg-gray-500");
            document.getElementById("btn-history").classList.remove("!bg-blue-600", "!bg-gray-500");
            document.getElementById("btn-available").classList.remove("!bg-blue-600", "!bg-gray-500");

            document.getElementById("btn-active").classList.add("!bg-gray-500");
            document.getElementById("btn-history").classList.add("!bg-gray-500");
            document.getElementById("btn-available").classList.add("!bg-gray-500");

            document.getElementById("btn-" + section).classList.remove("!bg-gray-500");
            document.getElementById("btn-" + section).classList.add("!bg-blue-600");
        }
    </script>

</x-layouts.app>
