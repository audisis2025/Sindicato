{{-- ===========================================================
 Nombre de la vista: logs.blade.php
 Descripción: Bitácora del sistema SINDISOFT.
 Fecha de creación: 02/11/2025
 Versión: 1.0
 Tipo de mantenimiento: Creación.
 Descripción: Muestra los registros de cambios y respaldos realizados
 por los administradores del sistema.
=========================================================== --}}

<x-layouts.app :title="__('Bitácora del sistema')">
    <div class="w-full min-h-[80vh] bg-[#FFFFFF] text-[#000000] p-8 font-[Inter]">

        <!-- 🏷️ Encabezado -->
        <div class="mb-8 text-center">
            <h1 class="text-3xl font-[Poppins] font-bold text-[#DC6601]">
                Bitácora del Sistema
            </h1>
            <p class="text-[#241178] mt-2 text-base">
                Registro histórico de acciones administrativas en SINDISOFT
            </p>
            <div class="w-20 h-[3px] bg-[#DC6601] mx-auto mt-3 rounded-full"></div>
        </div>

        <!-- 📋 Tabla de registros -->
        <div class="max-w-5xl mx-auto bg-[#FFFFFF] border border-[#D9D9D9] rounded-2xl shadow-md p-6">
            @if (count($logs) > 0)
                <div class="overflow-y-auto max-h-[500px] border border-[#D9D9D9]/50 rounded-lg">
                    <table class="w-full text-left text-sm border-collapse">
                        <thead class="bg-[#DC6601]/10 text-[#241178] font-semibold uppercase">
                            <tr>
                                <th class="px-4 py-3 border-b border-[#D9D9D9]">Fecha / Hora</th>
                                <th class="px-4 py-3 border-b border-[#D9D9D9]">Detalle</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($logs as $line)
                                @php
                                    preg_match('/\[(.*?)\]/', $line, $matches);
                                    $fecha = $matches[1] ?? '';
                                    $detalle = trim(str_replace("[$fecha]", '', $line));
                                @endphp
                                <tr class="hover:bg-[#DC6601]/5">
                                    <td class="px-4 py-2 border-b border-[#D9D9D9]/50 text-[#000000]/80">
                                        {{ $fecha }}</td>
                                    <td class="px-4 py-2 border-b border-[#D9D9D9]/50 text-[#241178]">
                                        {{ $detalle }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-center text-[#241178] mt-4">No hay registros en la bitácora.</p>
            @endif
        </div>

        <!-- 📘 Pie -->
        <footer class="mt-10 text-center text-sm text-[#272800] leading-tight">
            © {{ date('Y') }} SNTE – Sistema SINDISOFT<br>
            <span class="text-[#241178]">Bitácora del sistema</span> | Módulo Administrativo v1.0
        </footer>
    </div>
</x-layouts.app>
