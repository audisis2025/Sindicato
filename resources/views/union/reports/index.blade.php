{{-- ===========================================================
 Nombre de la vista: index.blade.php
 Descripción: Panel de visualización de reportes sindicales con gráficas, filtros y exportaciones.
 Versión: 1.4 (Agrupación por nombre de trámite)
=========================================================== --}}

<x-layouts.app :title="__('Reportes y estadísticas sindicales')">
    <div class="flex flex-col gap-6 p-6 w-full">

        <!-- 🔸 Encabezado -->
        <div class="flex justify-between items-center">
            <h1 class="text-3xl font-[Poppins] font-bold text-[#DC6601]">
                Reportes y estadísticas sindicales
            </h1>

            <div class="flex gap-3">
                <a href="{{ route('union.reports.export-pdf', request()->all()) }}"
                   class="bg-[#DC6601] hover:bg-[#EE0000] text-white font-semibold py-2 px-4 rounded-lg transition">
                    Exportar PDF
                </a>

                <a href="{{ route('union.reports.export-excel', request()->all()) }}"
                   class="bg-[#241178] hover:bg-[#1e0f6b] text-white font-semibold py-2 px-4 rounded-lg transition">
                    Exportar Excel
                </a>

                <a href="{{ route('union.reports.export-csv', request()->all()) }}"
                   class="bg-[#D9D9D9] hover:bg-[#B8B8B8] text-[#241178] font-semibold py-2 px-4 rounded-lg transition">
                    Exportar CSV
                </a>
            </div>
        </div>

        <!-- 🔹 Filtros -->
        <form method="GET"
              class="grid grid-cols-1 md:grid-cols-3 gap-4 bg-white border border-[#D9D9D9] rounded-2xl shadow-md p-4">
            <div class="flex flex-col gap-1">
                <label class="text-sm font-[Inter] text-[#241178]">Desde</label>
                <input type="date" name="from" class="border border-[#D9D9D9] rounded-lg px-3 py-2 text-sm"
                       value="{{ $filters['from'] ?? '' }}">
            </div>

            <div class="flex flex-col gap-1">
                <label class="text-sm font-[Inter] text-[#241178]">Hasta</label>
                <input type="date" name="to" class="border border-[#D9D9D9] rounded-lg px-3 py-2 text-sm"
                       value="{{ $filters['to'] ?? '' }}">
            </div>

            <div class="flex flex-col gap-1 md:col-span-1">
                <label class="text-sm font-[Inter] text-[#241178]">Tipo de trámite (por nombre)</label>
                <select name="type" class="border border-[#D9D9D9] rounded-lg px-3 py-2 text-sm">
                    <option value="">Todos</option>
                    @foreach($tipos as $nombreTramite)
                        <option value="{{ $nombreTramite }}" {{ ($filters['type'] ?? '') === $nombreTramite ? 'selected' : '' }}>
                            {{ $nombreTramite }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="md:col-span-3 flex items-center justify-end">
                <button type="submit"
                        class="bg-[#241178] hover:bg-[#1e0f6b] text-white font-semibold px-4 py-2 rounded-lg transition">
                    Filtrar
                </button>
            </div>
        </form>

        <!-- 📊 Gráfica principal -->
        <div class="bg-white border border-[#D9D9D9] rounded-2xl shadow-md p-6">
            <h2 class="text-xl font-[Poppins] text-[#241178] mb-4 font-semibold">
                Trámites por tipo (nombre)
            </h2>
            <canvas id="graficaTramites"></canvas>
        </div>

        <!-- 📋 Tabla resumen -->
        @if ($tramites->count() > 0)
            <div class="overflow-x-auto bg-white border border-[#D9D9D9] rounded-2xl shadow-md">
                <table class="w-full border-collapse text-sm font-[Inter]">
                    <thead class="bg-[#241178] text-white">
                    <tr>
                        <th class="p-2 text-left">#</th>
                        <th class="p-2 text-left">Sindicato</th>
                        <th class="p-2 text-left">Tipo de trámite</th>
                        <th class="p-2 text-left">Descripción</th>
                        <th class="p-2 text-left">Fecha apertura</th>
                        <th class="p-2 text-left">Fecha cierre</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($tramites as $index => $t)
                        <tr class="border-t border-[#272800]/10 hover:bg-[#F9F9F9] transition">
                            <td class="p-2">{{ $index + 1 }}</td>
                            <td class="p-2">{{ $t->user->name ?? '—' }}</td>
                            <!-- 🔸 Tipo de trámite = nombre con el que fue creado -->
                            <td class="p-2 text-[#000000] font-semibold">{{ $t->nombre ?? '—' }}</td>
                            <td class="p-2">{{ $t->descripcion ?? 'Sin descripción' }}</td>
                            <td class="p-2">
                                {{ $t->fecha_apertura ? \Carbon\Carbon::parse($t->fecha_apertura)->format('d/m/Y') : '—' }}
                            </td>
                            <td class="p-2">
                                {{ $t->fecha_cierre ? \Carbon\Carbon::parse($t->fecha_cierre)->format('d/m/Y') : '—' }}
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p class="text-center text-gray-500 text-sm mt-4">No hay registros disponibles.</p>
        @endif
    </div>

    {{-- 📈 Chart.js (agrupación por nombre) --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', async () => {
            const ctx = document.getElementById('graficaTramites');
            const params = new URLSearchParams(@json(request()->only(['from','to','type'])));
            const res = await fetch(`{{ route('union.reports.data') }}?${params.toString()}`);
            const data = await res.json();

            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: data.map(d => d.nombre),
                    datasets: [{
                        label: 'Cantidad de trámites',
                        data: data.map(d => d.total),
                        backgroundColor: '#DC6601',
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: { display: false },
                        title: {
                            display: true,
                            text: 'Trámites por tipo (nombre)',
                            color: '#241178',
                            font: { size: 16, family: 'Poppins', weight: 'bold' }
                        }
                    },
                    scales: {
                        x: { ticks: { color: '#241178' } },
                        y: { ticks: { color: '#241178' }, beginAtZero: true, precision: 0 }
                    }
                }
            });
        });
    </script>
</x-layouts.app>
