{{-- 
* Nombre de la vista           : reports-index.blade.php
* Descripción de la vista      : Dashboard de reportes sindicales con pestañas, gráficas y exportaciones.
* Fecha de creación            : 27/11/2025
* Elaboró                      : Iker Piza
* Fecha de liberación          : 27/11/2025
* Autorizó                     : Líder Técnico
* Versión                      : 1.1
* Fecha de mantenimiento       : 27/11/2025
* Folio de mantenimiento       : N/A
* Tipo de mantenimiento        : Correctivo y perfectivo
* Descripción del mantenimiento: Homologación completa con bitácora, estilos, botones y tablas PRO-Laravel V3.4.
* Responsable                  : Iker Piza
* Revisor                      : QA SINDISOFT
--}}

<x-layouts.app :title="__('Reportes y estadísticas sindicales')">

<div x-data="{ tab: 'gender' }" class="flex flex-col gap-6 p-6 w-full max-w-6xl mx-auto">

    <!-- Encabezado -->
    <div class="flex justify-between items-center">
        <h1 class="text-3xl font-bold text-[#DE6601]">
            Reportes y estadísticas sindicales
        </h1>

        <div class="flex gap-3">

            <flux:button
                icon="arrow-down-tray"
                variant="filled"
                x-bind:href="`{{ route('union.reports.export-pdf') }}?tab=${tab}`"
                class="!bg-blue-600 hover:!bg-blue-700 !text-white h-10 px-4 rounded-lg"
            >
                Exportar PDF
            </flux:button>

            <flux:button
                icon="arrow-down-tray"
                variant="filled"
                x-bind:href="`{{ route('union.reports.export-excel') }}?tab=${tab}`"
                class="!bg-blue-600 hover:!bg-blue-700 !text-white h-10 px-4 rounded-lg"
            >
                Exportar Excel
            </flux:button>

            <flux:button
                icon="arrow-down-tray"
                variant="filled"
                x-bind:href="`{{ route('union.reports.export-word') }}?tab=${tab}`"
                class="!bg-blue-600 hover:!bg-blue-700 !text-white h-10 px-4 rounded-lg"
            >
                Exportar Word
            </flux:button>

        </div>
    </div>

    <!-- Tabs -->
    <div class="flex gap-6 border-b pb-1 text-sm font-[Inter]">
        <button @click="tab='gender'"
            :class="tab==='gender' ? 'text-[#241178] font-bold border-b-2 border-[#241178]' : 'text-gray-500'">
            Distribución por género
        </button>

        <button @click="tab='status'"
            :class="tab==='status' ? 'text-[#241178] font-bold border-b-2 border-[#241178]' : 'text-gray-500'">
            Completados vs pendientes
        </button>

        <button @click="tab='types'"
            :class="tab==='types' ? 'text-[#241178] font-bold border-b-2 border-[#241178]' : 'text-gray-500'">
            Trámites por tipo
        </button>

        <button @click="tab='table'"
            :class="tab==='table' ? 'text-[#241178] font-bold border-b-2 border-[#241178]' : 'text-gray-500'">
            Tabla de solicitudes
        </button>
    </div>

    <!-- TAB: GÉNERO -->
    <div x-show="tab==='gender'" x-transition>

        <!-- KPIs -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mt-4">
            <div class="p-4 bg-white border border-[#D9D9D9] rounded-xl shadow-sm">
                <p class="text-sm text-[#241178] font-semibold">Trabajadores atendidos</p>
                <h2 class="text-3xl font-bold">{{ $workers_attended }}</h2>
            </div>

            <div class="p-4 bg-white border border-[#D9D9D9] rounded-xl shadow-sm">
                <p class="text-sm text-[#241178] font-semibold">Promedio de tiempo (días)</p>
                <h2 class="text-3xl font-bold">{{ $avg_time }}</h2>
            </div>

            <div class="p-4 bg-white border border-[#D9D9D9] rounded-xl shadow-sm">
                <p class="text-sm text-[#241178] font-semibold">Completados</p>
                <h2 class="text-3xl font-bold">{{ $completed }}</h2>
            </div>

            <div class="p-4 bg-white border border-[#D9D9D9] rounded-xl shadow-sm">
                <p class="text-sm text-[#241178] font-semibold">Pendientes</p>
                <h2 class="text-3xl font-bold">{{ $pending }}</h2>
            </div>
        </div>

        <!-- Gráfica -->
        <div class="bg-white border border-[#D9D9D9] rounded-xl shadow-sm p-6 mt-6">
            <h2 class="text-xl text-[#241178] font-semibold mb-3">Distribución por género</h2>
            <canvas id="chartGenero"></canvas>
        </div>

        <!-- Tabla resumen -->
        <div class="bg-white border border-[#D9D9D9] rounded-xl shadow-sm p-6 mt-6">
            <h3 class="text-lg text-[#241178] font-semibold mb-3">Resumen por género</h3>

            <table class="min-w-full divide-y divide-zinc-200 text-sm">
                <thead class="bg-zinc-100">
                    <tr>
                        <th class="px-4 py-3 font-semibold text-black">Género</th>
                        <th class="px-4 py-3 font-semibold text-black">Cantidad</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-zinc-200 bg-white">
                    <tr class="hover:bg-zinc-50 transition">
                        <td class="px-4 py-3 text-black">Hombres</td>
                        <td class="px-4 py-3 text-black">{{ $hombres }}</td>
                    </tr>
                    <tr class="hover:bg-zinc-50 transition">
                        <td class="px-4 py-3 text-black">Mujeres</td>
                        <td class="px-4 py-3 text-black">{{ $mujeres }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- TAB: ESTADOS -->
    <div x-show="tab==='status'" x-transition>

        <div class="bg-white border border-[#D9D9D9] rounded-xl shadow-sm p-6 mt-6">
            <h2 class="text-xl text-[#241178] font-semibold mb-3">Trámites completados vs pendientes</h2>
            <canvas id="chartEstados"></canvas>
        </div>

        <div class="bg-white border border-[#D9D9D9] rounded-xl shadow-sm p-6 mt-6">
            <h3 class="text-lg text-[#241178] font-semibold mb-3">Resumen por estado</h3>

            <table class="min-w-full divide-y divide-zinc-200 text-sm">
                <thead class="bg-zinc-100">
                    <tr>
                        <th class="px-4 py-3 font-semibold text-black">Estado</th>
                        <th class="px-4 py-3 font-semibold text-black">Cantidad</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-zinc-200 bg-white">
                    <tr class="hover:bg-zinc-50 transition">
                        <td class="px-4 py-3 text-black">Completados</td>
                        <td class="px-4 py-3 text-black">{{ $completed }}</td>
                    </tr>
                    <tr class="hover:bg-zinc-50 transition">
                        <td class="px-4 py-3 text-black">Pendientes</td>
                        <td class="px-4 py-3 text-black">{{ $pending }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- TAB: TIPOS -->
    <div x-show="tab==='types'" x-transition>

        <div class="bg-white border border-[#D9D9D9] rounded-xl shadow-sm p-6 mt-6">
            <h2 class="text-xl text-[#241178] font-semibold mb-4">Trámites por tipo</h2>
            <canvas id="chartProcedures"></canvas>
        </div>

        <div class="bg-white border border-[#D9D9D9] rounded-xl shadow-sm p-6 mt-6">
            <h3 class="text-lg text-[#241178] font-semibold mb-3">Resumen</h3>

            <table class="min-w-full divide-y divide-zinc-200 text-sm">
                <thead class="bg-zinc-100">
                    <tr>
                        <th class="px-4 py-3 font-semibold text-black">Tipo de trámite</th>
                        <th class="px-4 py-3 font-semibold text-black">Cantidad</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-zinc-200 bg-white">
                    @foreach($statistics as $name => $total)
                        <tr class="hover:bg-zinc-50 transition">
                            <td class="px-4 py-3 text-black">{{ $name }}</td>
                            <td class="px-4 py-3 text-black">{{ $total }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- TAB: TABLA COMPLETA -->
    <div x-show="tab==='table'" x-transition>

        <div class="overflow-x-auto bg-white border border-[#D9D9D9] rounded-xl shadow-sm mt-4">

            @if ($requests->count() > 0)
                <table class="min-w-full divide-y divide-zinc-200 text-sm">

                    <thead class="bg-zinc-100">
                        <tr>
                            <th class="px-4 py-3 font-semibold text-black">#</th>
                            <th class="px-4 py-3 font-semibold text-black">Trabajador</th>
                            <th class="px-4 py-3 font-semibold text-black">Tipo</th>
                            <th class="px-4 py-3 font-semibold text-black">Descripción</th>
                            <th class="px-4 py-3 font-semibold text-black">Apertura</th>
                            <th class="px-4 py-3 font-semibold text-black">Cierre</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-zinc-200 bg-white">
                        @foreach($requests as $i => $req)
                            <tr class="hover:bg-zinc-50 transition">
                                <td class="px-4 py-3">{{ $i + 1 }}</td>
                                <td class="px-4 py-3 text-black">{{ $req->user->name ?? '—' }}</td>
                                <td class="px-4 py-3 text-black font-semibold">{{ $req->procedure->name ?? '—' }}</td>
                                <td class="px-4 py-3 text-black">{{ $req->procedure->description ?? 'Sin descripción' }}</td>

                                <td class="px-4 py-3 text-black">
                                    {{ $req->procedure->opening_date
                                        ? \Carbon\Carbon::parse($req->procedure->opening_date)->format('d/m/Y')
                                        : '—' }}
                                </td>

                                <td class="px-4 py-3 text-black">
                                    {{ $req->procedure->closing_date
                                        ? \Carbon\Carbon::parse($req->procedure->closing_date)->format('d/m/Y')
                                        : '—' }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>

                </table>
            @else
                <p class="text-center py-4 text-gray-500 text-sm">No hay registros.</p>
            @endif

        </div>

    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
document.addEventListener('DOMContentLoaded', async () => {

    new Chart(document.getElementById('chartGenero'), {
        type: 'pie',
        data: {
            labels: ['Hombres', 'Mujeres'],
            datasets: [{
                data: [{{ $hombres }}, {{ $mujeres }}],
                backgroundColor: ['#241178', '#DE6601']
            }]
        }
    });

    new Chart(document.getElementById('chartEstados'), {
        type: 'bar',
        data: {
            labels: ['Completados', 'Pendientes'],
            datasets: [{
                data: [{{ $completed }}, {{ $pending }}],
                backgroundColor: ['#16A34A', '#FACC15']
            }]
        },
        options: { plugins: { legend: { display: false } } }
    });

    const res = await fetch(`{{ route('union.reports.data') }}`);
    const data = await res.json();

    new Chart(document.getElementById('chartProcedures'), {
        type: 'bar',
        data: {
            labels: data.map(d => d.name),
            datasets: [{
                label: 'Cantidad de trámites',
                data: data.map(d => d.total),
                backgroundColor: '#DE6601'
            }]
        },
        options: { plugins: { legend: { display: false } } }
    });

});
</script>

</x-layouts.app>
