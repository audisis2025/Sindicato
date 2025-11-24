{{-- ===========================================================
 Nombre de la vista: index.blade.php
 Descripción: Dashboard de reportes con pestañas (UX mejorada, sin filtros)
=========================================================== --}}

<x-layouts.app :title="__('Reportes y estadísticas sindicales')">

<div x-data="{ tab: 'gender' }" class="flex flex-col gap-6 p-6 w-full">

    <!-- Encabezado -->
    <div class="flex justify-between items-center">
        <h1 class="text-3xl font-[Poppins] font-bold text-[#DC6601]">
            Reportes y estadísticas sindicales
        </h1>

        <div class="flex gap-3">
            {{-- Nota: el controlador debe leer ?tab=... --}}
            <a :href="`{{ route('union.reports.export-pdf') }}?tab=${tab}`"
               class="bg-[#DC6601] hover:bg-[#EE0000] text-white font-semibold py-2 px-4 rounded-lg transition">
                Exportar PDF
            </a>

            <a :href="`{{ route('union.reports.export-excel') }}?tab=${tab}`"
               class="bg-[#241178] hover:bg-[#1e0f6b] text-white font-semibold py-2 px-4 rounded-lg transition">
                Exportar Excel
            </a>

            <a :href="`{{ route('union.reports.export-word') }}?tab=${tab}`"
               class="bg-[#0A84FF] hover:bg-[#0066CC] text-white font-semibold py-2 px-4 rounded-lg transition">
                Exportar Word
            </a>
        </div>
    </div>

    <!-- Tabs -->
    <div class="flex gap-4 border-b pb-1 text-sm font-[Inter]">
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

    <!-- ============================= -->
    <!-- TAB 1: GÉNERO -->
    <!-- ============================= -->
    <div x-show="tab==='gender'" x-transition>

        <!-- KPIs principales -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mt-4">
            <div class="p-4 bg-white border rounded-xl shadow-md">
                <p class="text-sm text-[#241178] font-semibold">Trabajadores atendidos</p>
                <h2 class="text-3xl font-bold">{{ $workers_attended }}</h2>
            </div>

            <div class="p-4 bg-white border rounded-xl shadow-md">
                <p class="text-sm text-[#241178] font-semibold">Promedio de tiempo (días)</p>
                <h2 class="text-3xl font-bold">{{ $avg_time }}</h2>
            </div>

            <div class="p-4 bg-white border rounded-xl shadow-md">
                <p class="text-sm text-[#241178] font-semibold">Completados</p>
                <h2 class="text-3xl font-bold">{{ $completed }}</h2>
            </div>

            <div class="p-4 bg-white border rounded-xl shadow-md">
                <p class="text-sm text-[#241178] font-semibold">Pendientes</p>
                <h2 class="text-3xl font-bold">{{ $pending }}</h2>
            </div>
        </div>

        <!-- Gráfica de género -->
        <div class="bg-white border rounded-2xl shadow-md p-6 mt-6">
            <h2 class="text-xl text-[#241178] font-semibold mb-3">Distribución por género</h2>
            <canvas id="chartGenero"></canvas>
        </div>

        <!-- Tabla de género (para exportar) -->
        <div class="bg-white border rounded-2xl shadow-md p-6 mt-6">
            <h3 class="text-lg text-[#241178] font-semibold mb-3">Resumen por género</h3>
            <table class="w-full text-sm font-[Inter] border border-[#D9D9D9] rounded-xl overflow-hidden">
                <thead class="bg-[#241178] text-white">
                    <tr>
                        <th class="p-2 text-left">Género</th>
                        <th class="p-2 text-left">Cantidad</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="border-t hover:bg-gray-50">
                        <td class="p-2">Hombres</td>
                        <td class="p-2">{{ $hombres }}</td>
                    </tr>
                    <tr class="border-t hover:bg-gray-50">
                        <td class="p-2">Mujeres</td>
                        <td class="p-2">{{ $mujeres }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- ============================= -->
    <!-- TAB 2: ESTADOS -->
    <!-- ============================= -->
    <div x-show="tab==='status'" x-transition>

        <div class="bg-white border rounded-2xl shadow-md p-6 mt-6">
            <h2 class="text-xl text-[#241178] font-semibold mb-3">Trámites completados vs pendientes</h2>
            <canvas id="chartEstados"></canvas>
        </div>

        <div class="bg-white border rounded-2xl shadow-md p-6 mt-6">
            <h3 class="text-lg text-[#241178] font-semibold mb-3">Resumen por estado</h3>
            <table class="w-full text-sm font-[Inter] border border-[#D9D9D9] rounded-xl overflow-hidden">
                <thead class="bg-[#241178] text-white">
                    <tr>
                        <th class="p-2 text-left">Estado</th>
                        <th class="p-2 text-left">Cantidad</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="border-t hover:bg-gray-50">
                        <td class="p-2">Completados</td>
                        <td class="p-2">{{ $completed }}</td>
                    </tr>
                    <tr class="border-t hover:bg-gray-50">
                        <td class="p-2">Pendientes</td>
                        <td class="p-2">{{ $pending }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- ============================= -->
    <!-- TAB 3: TRÁMITES POR TIPO -->
    <!-- ============================= -->
    <div x-show="tab==='types'" x-transition>
        <div class="bg-white border rounded-2xl shadow-md p-6 mt-6">
            <h2 class="text-xl text-[#241178] font-semibold mb-4">Trámites por tipo</h2>
            <canvas id="chartProcedures"></canvas>
        </div>

        <div class="bg-white border rounded-2xl shadow-md p-6 mt-6">
            <h3 class="text-lg text-[#241178] font-semibold mb-3">Resumen por tipo de trámite</h3>
            <table class="w-full text-sm font-[Inter] border border-[#D9D9D9] rounded-xl overflow-hidden">
                <thead class="bg-[#241178] text-white">
                    <tr>
                        <th class="p-2 text-left">Tipo de trámite</th>
                        <th class="p-2 text-left">Cantidad</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($statistics as $name => $total)
                        <tr class="border-t hover:bg-gray-50">
                            <td class="p-2">{{ $name }}</td>
                            <td class="p-2">{{ $total }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- ============================= -->
    <!-- TAB 4: TABLA COMPLETA -->
    <!-- ============================= -->
    <div x-show="tab==='table'" x-transition>
        @if ($requests->count() > 0)
            <div class="overflow-x-auto bg-white border rounded-2xl shadow-md mt-4">
                <table class="w-full text-sm font-[Inter] border-collapse">
                    <thead class="bg-[#241178] text-white">
                        <tr>
                            <th class="p-2">#</th>
                            <th class="p-2">Trabajador</th>
                            <th class="p-2">Tipo</th>
                            <th class="p-2">Descripción</th>
                            <th class="p-2">Apertura</th>
                            <th class="p-2">Cierre</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($requests as $i => $req)
                            <tr class="border-t hover:bg-gray-50">
                                <td class="p-2">{{ $i + 1 }}</td>
                                <td class="p-2">{{ $req->user->name ?? '—' }}</td>
                                <td class="p-2 font-semibold">{{ $req->procedure->name ?? '—' }}</td>
                                <td class="p-2">{{ $req->procedure->description ?? 'Sin descripción' }}</td>
                                <td class="p-2">
                                    {{ $req->procedure->opening_date
                                        ? \Carbon\Carbon::parse($req->procedure->opening_date)->format('d/m/Y') : '—' }}
                                </td>
                                <td class="p-2">
                                    {{ $req->procedure->closing_date
                                        ? \Carbon\Carbon::parse($req->procedure->closing_date)->format('d/m/Y') : '—' }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p class="text-center text-gray-500 mt-4">No hay registros.</p>
        @endif
    </div>
</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
document.addEventListener('DOMContentLoaded', async () => {
    /* GÉNERO */
    new Chart(document.getElementById('chartGenero'), {
        type: 'pie',
        data: {
            labels: ['Hombres', 'Mujeres'],
            datasets: [{
                data: [{{ $hombres }}, {{ $mujeres }}],
                backgroundColor: ['#241178', '#DC6601']
            }]
        }
    });

    /* ESTADOS */
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

    /* TRÁMITES POR TIPO (datos desde backend) */
    const ctxProcedures = document.getElementById('chartProcedures');
    const res = await fetch(`{{ route('union.reports.data') }}`);
    const data = await res.json();

    new Chart(ctxProcedures, {
        type: 'bar',
        data: {
            labels: data.map(d => d.name),
            datasets: [{
                label: 'Cantidad de trámites',
                data: data.map(d => d.total),
                backgroundColor: '#DC6601'
            }]
        },
        options: { plugins: { legend: { display: false } } }
    });
});
</script>

</x-layouts.app>
