{{-- 
* Nombre de la vista           : logs.blade.php
* Descripción de la vista      : Bitácora del sistema con filtros, exportación y eliminación.
* Fecha de creación            : 25/11/2025
* Elaboró                      : Iker Piza
* Fecha de liberación          : 25/11/2025
* Autorizó                     : Líder Técnico
* Versión                      : 1.3
* Fecha de mantenimiento       : 27/11/2025
* Folio de mantenimiento       : N/A
* Tipo de mantenimiento        : Correctivo y perfectivo
* Descripción del mantenimiento: Homologación de estructura, filtros, botones e iconografía según Manual PRO-Laravel V3.4.
* Responsable                  : Iker Piza
* Revisor                      : QA SINDISOFT
--}}

<x-layouts.app :title="__('Bitácora del sistema')">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://npmcdn.com/flatpickr/dist/l10n/es.js"></script>

    <div class="flex flex-col gap-6 p-6">

        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <h1 class="text-3xl font-bold text-[#DE6601]">Bitácora del sistema</h1>

            <div class="flex flex-wrap gap-3">

                <flux:button
                    icon="arrow-down-tray"
                    variant="filled"
                    :href="route('admin.configuration.logs.exportWord')"
                    class="!bg-blue-600 hover:!bg-blue-700 !text-white h-10 px-4 rounded-lg"
                >
                    Exportar Word
                </flux:button>

                <form action="{{ route('admin.configuration.logs.clear') }}" method="POST"
                      onsubmit="return confirm('¿Deseas eliminar toda la bitácora? Esta acción no se puede deshacer.')">
                    @csrf
                    @method('DELETE')

                    <flux:button
                        icon="trash"
                        variant="danger"
                        type="submit"
                        class="!bg-red-600 hover:!bg-red-700 !text-white h-10 px-4 rounded-lg"
                    >
                        Eliminar Bitácora
                    </flux:button>
                </form>

            </div>
        </div>

        <form method="GET" action="{{ route('admin.configuration.logs') }}"
            class="bg-white p-4 border border-[#D9D9D9] rounded-xl shadow-sm flex flex-wrap gap-4 items-end">

            <div class="flex flex-col">
                <label class="text-sm font-semibold text-[#241178]">Fecha inicio</label>
                <input type="text" name="date_from" id="date_from"
                    value="{{ request('date_from') }}"
                    class="border border-[#D9D9D9] rounded-lg px-3 py-2 text-sm outline-none
                    focus:ring-2 focus:ring-blue-600">
            </div>

            <div class="flex flex-col">
                <label class="text-sm font-semibold text-[#241178]">Fecha fin</label>
                <input type="text" name="date_to" id="date_to"
                    value="{{ request('date_to') }}"
                    class="border border-[#D9D9D9] rounded-lg px-3 py-2 text-sm outline-none
                    focus:ring-2 focus:ring-blue-600">
            </div>

            <div class="flex flex-col">
                <label class="text-sm font-semibold text-[#241178]">Palabra clave</label>
                <input type="text" name="keyword" id="keyword" placeholder="usuario, módulo…"
                    value="{{ request('keyword') }}"
                    class="border border-[#D9D9D9] rounded-lg px-3 py-2 text-sm outline-none
                    focus:ring-2 focus:ring-blue-600">
            </div>

            <flux:button
                icon="magnifying-glass"
                variant="primary"
                type="submit"
                class="h-10 px-4 !bg-gray-500 hover:!bg-gray-600 !text-white rounded-lg"
            >
                Filtrar
            </flux:button>

            <flux:button
                icon="arrow-path"
                variant="primary"
                :href="route('admin.configuration.logs')"
                class="h-10 px-4 !bg-blue-500 hover:!bg-blue-600 !text-white rounded-lg"
            >
                Limpiar
            </flux:button>

        </form>

        <div class="overflow-x-auto bg-white border border-[#D9D9D9] rounded-xl shadow-sm">

            @if ($logs->count() > 0)
                <table class="min-w-full divide-y divide-zinc-200 text-sm">
                    <thead class="bg-zinc-100">
                        <tr>
                            <th class="px-4 py-3 text-left font-semibold text-black">Fecha / hora</th>
                            <th class="px-4 py-3 text-left font-semibold text-black">Módulo</th>
                            <th class="px-4 py-3 text-left font-semibold text-black">Acción</th>
                            <th class="px-4 py-3 text-left font-semibold text-black">Usuario</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-zinc-200 bg-white">
                        @foreach ($logs as $log)
                            <tr class="hover:bg-zinc-50 transition">
                                <td class="px-4 py-3 text-sm text-black">
                                    {{ $log->created_at->timezone('America/Mexico_City')->format('d/m/Y H:i:s') }}
                                </td>
                                <td class="px-4 py-3 text-sm text-black">{{ $log->module }}</td>
                                <td class="px-4 py-3 text-sm text-black/80">{{ $log->action }}</td>
                                <td class="px-4 py-3 text-sm text-black">{{ $log->user->name ?? 'Sistema' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <p class="text-center py-4 text-gray-500 text-sm">No hay registros para los filtros aplicados.</p>
            @endif

        </div>

    </div>

    <script>
        flatpickr.localize(flatpickr.l10ns.es);

        const convertToBackendFormat = (value) => {
            if (!value) return "";
            const [day, month, year] = value.split('/');
            return `${year}-${month}-${day}`;
        };

        document.querySelector('form').addEventListener('submit', function() {
            const df = document.querySelector('#date_from');
            const dt = document.querySelector('#date_to');
            df.value = convertToBackendFormat(df.value);
            dt.value = convertToBackendFormat(dt.value);
        });

        flatpickr('#date_from', { dateFormat: 'd/m/Y', allowInput: true });
        flatpickr('#date_to', { dateFormat: 'd/m/Y', allowInput: true });
    </script>

</x-layouts.app>
