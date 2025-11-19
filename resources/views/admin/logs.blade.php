<x-layouts.app :title="__('Bitácora del sistema')">

    {{-- FLATPICKR --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://npmcdn.com/flatpickr/dist/l10n/es.js"></script>

    <div class="flex flex-col gap-6">

        {{-- TÍTULO --}}
        <div class="flex justify-between items-center">
            <h1 class="text-3xl font-bold text-[#DE6601]">Bitácora del Sistema</h1>
        </div>

        {{-- FORMULARIO DE FILTRO --}}
        <form method="GET" action="{{ route('admin.configuration.logs') }}"
            class="flex flex-wrap gap-4 items-end bg-white p-4 border border-[#D9D9D9] rounded-lg">

            {{-- Desde --}}
            <div class="flex flex-col">
                <label for="date_from" class="text-sm font-semibold text-[#241178]">Fecha inicio</label>
                <input type="text" name="date_from" id="date_from" value="{{ request('date_from') }}"
                    placeholder="Seleccionar fecha"
                    class="border border-[#D9D9D9] rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-[#DE6601] outline-none bg-white">
            </div>

            {{-- Hasta --}}
            <div class="flex flex-col">
                <label for="date_to" class="text-sm font-semibold text-[#241178]">Fecha fin</label>
                <input type="text" name="date_to" id="date_to" value="{{ request('date_to') }}"
                    placeholder="Seleccionar fecha"
                    class="border border-[#D9D9D9] rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-[#DE6601] outline-none bg-white">
            </div>

            {{-- Palabra clave --}}
            <div class="flex flex-col">
                <label for="keyword" class="text-sm font-semibold text-[#241178]">Palabra clave</label>
                <input type="text" name="keyword" id="keyword" value="{{ request('keyword') }}"
                    placeholder="Ejemplo: usuario, trámite..."
                    class="border border-[#D9D9D9] rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-[#DE6601] outline-none bg-white">
            </div>

            {{-- Botón filtrar --}}
            <button type="submit"
                class="bg-[#241178] hover:bg-[#1A0D5A] text-white font-semibold px-4 py-2 rounded-lg transition h-10">
                Filtrar
            </button>

            {{-- Botón limpiar --}}
            <a href="{{ route('admin.configuration.logs') }}"
                class="bg-[#DE6601] hover:bg-[#EE0000] text-white font-semibold px-4 py-2 rounded-lg transition h-10">
                Limpiar
            </a>
        </form>

        {{-- TABLA DE REGISTROS --}}
        <div class="bg-white border border-[#D9D9D9] rounded-lg overflow-hidden">

            @if (!empty($system_logs) && count($system_logs) > 0)

                <table class="w-full border-collapse text-sm font-[Inter]">
                    <thead class="bg-[#241178] text-white">
                        <tr>
                            <th class="p-2 text-left">Fecha / Hora</th>
                            <th class="p-2 text-left">Detalle</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($system_logs as $line)
                            @php
                                preg_match('/\[(.*?)\]/', $line, $matches);
                                $fecha = $matches[1] ?? '';
                                $detalle = trim(str_replace("[$fecha]", '', $line));
                            @endphp

                            <tr class="border-t border-[#D9D9D9] hover:bg-[#F4F1FA] transition">
                                <td class="p-2 text-black/80">{{ $fecha }}</td>
                                <td class="p-2 text-[#241178]">{{ $detalle }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

            @else
                <p class="text-center py-4 text-gray-500">
                    @if (request('date_from') || request('keyword'))
                        No se encontraron registros con los filtros aplicados.
                    @else
                        No hay registros en la bitácora.
                    @endif
                </p>
            @endif

        </div>

    </div>

    {{-- SCRIPT FLATPICKR --}}
    <script>
        flatpickr.localize(flatpickr.l10ns.es);

        const baseConfig = {
            dateFormat: "Y-m-d",
            allowInput: true
        };

        const fp_to = flatpickr("#date_to", { ...baseConfig });

        const fp_from = flatpickr("#date_from", {
            ...baseConfig,
            onChange: function(selectedDates, dateStr) {
                fp_to.set("minDate", dateStr);
            }
        });
    </script>

</x-layouts.app>
