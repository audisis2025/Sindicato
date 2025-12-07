<x-layouts.app :title="__('Panel del Trabajador')">
    <div class="flex flex-col gap-8 p-6">

        <div class="flex justify-between items-center">
            <h1 class="text-3xl font-[Poppins] font-bold text-[#DC6601]">
                Mis Trámites
            </h1>
        </div>

        <div class="flex gap-3">
            <button
                onclick="showSection('active')"
                class="tab-btn bg-[#241178] text-white px-4 py-2 rounded-lg font-semibold"
                id="btn-active"
            >
                Trámites activos
            </button>

            <button
                onclick="showSection('history')"
                class="tab-btn bg-[#DE6601]/20 text-[#DE6601] px-4 py-2 rounded-lg font-semibold"
                id="btn-history"
            >
                Historial
            </button>

            <button
                onclick="showSection('available')"
                class="tab-btn bg-[#DE6601]/20 text-[#DE6601] px-4 py-2 rounded-lg font-semibold"
                id="btn-available"
            >
                Trámites disponibles
            </button>
        </div>

        <section id="section-active" class="section-card">
            <div class="bg-white border border-zinc-200 rounded-2xl shadow-md p-5">
                <h2 class="text-xl font-semibold text-[#241178] font-[Poppins] mb-4">
                    Trámites activos
                </h2>

                <table class="w-full border-collapse border border-zinc-200 text-sm font-[Inter]">
                    <thead class="bg-[#241178] text-white">
                        <tr>
                            <th class="p-2 text-left">#</th>
                            <th class="p-2 text-left">Nombre</th>
                            <th class="p-2 text-left">Estado</th>
                            <th class="p-2 text-left">Fecha</th>
                            <th class="p-2 text-center">Acciones</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($active_requests as $i => $req)
                            @php
                                $colors = [
                                    'initiated' => 'text-blue-600',
                                    'in_progress' => 'text-[#DC6601]',
                                    'pending_worker' => 'text-yellow-600',
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

                            <tr class="border-t border-zinc-200 hover:bg-zinc-50 transition">
                                <td class="p-2">{{ $i + 1 }}</td>
                                <td class="p-2">{{ $req->procedure->name }}</td>

                                <td class="p-2 font-semibold">
                                    <span class="{{ $colors[$req->status] ?? 'text-gray-600' }}">
                                        {{ $labels[$req->status] ?? '—' }}
                                    </span>
                                </td>

                                <td class="p-2">{{ $req->created_at->format('d/m/Y') }}</td>

                                <td class="p-2 text-center">
                                    <flux:button
                                        size="xs"
                                        variant="filled"
                                        icon="eye"
                                        icon-variant="outline"
                                        :href="route('worker.requests.show', $req->id)"
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
        </section>

        <section id="section-history" class="section-card hidden">
            <div class="bg-white border border-zinc-200 rounded-2xl shadow-md p-5">
                <h2 class="text-xl font-semibold text-[#241178] font-[Poppins] mb-4">
                    Historial de trámites finalizados
                </h2>

                <table class="w-full border-collapse border border-zinc-200 text-sm font-[Inter]">
                    <thead class="bg-[#241178] text-white">
                        <tr>
                            <th class="p-2">#</th>
                            <th class="p-2">Nombre</th>
                            <th class="p-2">Estado</th>
                            <th class="p-2">Fecha</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($finished_requests as $i => $req)
                            @php
                                $colors = [
                                    'initiated' => 'text-blue-600',
                                    'in_progress' => 'text-[#DC6601]',
                                    'pending_worker' => 'text-yellow-600',
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

                            <tr class="border-t border-zinc-200 hover:bg-zinc-50 transition">
                                <td class="p-2">{{ $i + 1 }}</td>
                                <td class="p-2">{{ $req->procedure->name }}</td>

                                <td class="p-2">
                                    <span class="{{ $colors[$req->status] ?? 'text-gray-600' }}">
                                        {{ $labels[$req->status] ?? '—' }}
                                    </span>
                                </td>

                                <td class="p-2">{{ $req->updated_at->format('d/m/Y') }}</td>
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
        </section>

        <section id="section-available" class="section-card hidden">
            <div class="bg-white border border-zinc-200 rounded-2xl shadow-md p-5">
                <h2 class="text-xl font-semibold text-[#241178] font-[Poppins] mb-4">
                    Trámites disponibles para iniciar
                </h2>

                <table class="w-full border-collapse border border-zinc-200 text-sm font-[Inter]">
                    <thead class="bg-[#241178] text-white">
                        <tr>
                            <th class="p-2">#</th>
                            <th class="p-2">Nombre</th>
                            <th class="p-2">Descripción</th>
                            <th class="p-2 text-center">Acciones</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($available_procedures as $i => $proc)
                            <tr class="border-t border-zinc-200 hover:bg-zinc-50 transition">
                                <td class="p-2">{{ $i + 1 }}</td>
                                <td class="p-2 font-semibold">{{ $proc->name }}</td>
                                <td class="p-2">
                                    {{ $proc->description ?? 'Sin descripción' }}
                                </td>

                                <td class="p-2 text-center">
                                    <form method="POST" action="{{ route('worker.procedures.start', $proc->id) }}">
                                        @csrf
                                        <flux:button
                                            size="xs"
                                            type="submit"
                                            variant="primary"
                                            icon="plus"
                                            icon-variant="outline"
                                        >
                                            Iniciar trámite
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
        </section>

    </div>

    <script>
        function showSection(section) {
            document.querySelectorAll(".section-card").forEach(function (s) {
                s.classList.add("hidden");
            });
            document.getElementById("section-" + section).classList.remove("hidden");

            document.querySelectorAll(".tab-btn").forEach(function (b) {
                b.classList.remove("bg-[#241178]", "text-white");
                b.classList.add("bg-[#DE6601]/20", "text-[#DE6601]");
            });

            var activeBtn = document.getElementById("btn-" + section);
            activeBtn.classList.remove("bg-[#DE6601]/20", "text-[#DE6601]");
            activeBtn.classList.add("bg-[#241178]", "text-white");
        }
    </script>
</x-layouts.app>
