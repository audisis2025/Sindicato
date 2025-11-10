{{-- ===========================================================
 Nombre de la vista: request-show.blade.php
 Descripción: Muestra los detalles completos de una solicitud
 de trámite realizada por el trabajador.
 Fecha de creación: 07/11/2025
 Elaboró: Iker Piza
 Versión: 1.0
 Tipo de mantenimiento: Creación.
 =========================================================== --}}

<x-layouts.app :title="__('Detalle del trámite solicitado')">
    <div class="w-full flex flex-col items-center justify-center min-h-[80vh] bg-[#FFFFFF] text-[#000000] p-6">

        <!-- 🔸 Encabezado -->
        <h1 class="text-3xl font-[Poppins] font-bold text-[#DC6601] mb-2">
            Detalle del Trámite Solicitado
        </h1>
        <p class="text-[#241178] font-[Inter] mb-8 text-center">
            Consulta la información completa del trámite y su progreso.
        </p>

        <!-- 📋 Contenedor principal -->
        <div
            class="w-full max-w-3xl bg-[#FFFFFF] border border-[#D9D9D9] shadow-md rounded-2xl p-8 font-[Inter] space-y-6">

            <!-- 🧾 Información general -->
            <div class="border-b border-[#EAEAEA] pb-4 mb-4">
                <h2 class="text-2xl font-[Poppins] font-bold text-[#241178] mb-2">
                    {{ $solicitud->tramite->nombre }}
                </h2>
                <p class="text-gray-700 text-sm sm:text-base mb-3">
                    {{ $solicitud->tramite->descripcion }}
                </p>
                <p class="text-sm text-[#272800]">
                    <strong>Fecha de solicitud:</strong> {{ $solicitud->created_at->format('d/m/Y H:i') }}
                </p>
                @php
                    $colorClass = match ($solicitud->estado) {
                        'Completado' => 'text-green-600',
                        'Rechazado' => 'text-red-600',
                        'Cancelado' => 'text-gray-500',
                        default => 'text-[#DC6601]',
                    };
                @endphp

                <p class="text-sm text-[#272800]">
                    <strong>Estado actual:</strong>
                    <span class="{{ $colorClass }} font-semibold">
                        {{ $solicitud->estado }}
                    </span>
                </p>

                <p class="text-sm text-[#272800]">
                    <strong>Paso actual:</strong> {{ $solicitud->paso_actual }} de
                    {{ $solicitud->tramite->numero_pasos }}
                </p>
            </div>

            <!-- 📈 Barra de progreso -->
            @php
                $totalPasos = $solicitud->tramite->pasos->count();
                $progreso = $totalPasos > 0 ? ($solicitud->paso_actual / $totalPasos) * 100 : 0;
            @endphp
            <div class="w-full bg-[#EAEAEA] rounded-full h-3 overflow-hidden mb-6">
                <div class="bg-[#DC6601] h-3 rounded-full transition-all duration-500"
                    style="width: {{ $progreso }}%"></div>
            </div>

            <!-- 👣 Lista de pasos -->
            <div>
                <h3 class="text-xl font-semibold text-[#DC6601] mb-4 font-[Poppins]">
                    Pasos del trámite
                </h3>

                @foreach ($solicitud->tramite->pasos as $index => $paso)
                    @php
                        $numeroPaso = $index + 1;
                        $completado = $numeroPaso < $solicitud->paso_actual || $solicitud->estado === 'Completado';
                    @endphp

                    <div class="border border-[#D9D9D9] rounded-lg p-5 mb-5 shadow-sm bg-white">
                        <h4 class="text-[#241178] font-semibold text-lg mb-1">
                            Paso {{ $numeroPaso }}: {{ $paso->nombre_paso }}
                            @if ($completado)
                                <span class="text-green-600 text-sm font-semibold">(Completado)</span>
                            @elseif($numeroPaso == $solicitud->paso_actual)
                                <span class="text-[#DC6601] text-sm font-semibold">(En progreso)</span>
                            @endif
                        </h4>
                        <p class="text-gray-600 text-sm mb-2">{{ $paso->descripcion_paso }}</p>

                        @if ($paso->formato_path)
                            <a href="{{ asset('storage/' . $paso->formato_path) }}"
                                class="text-[#DC6601] hover:underline text-sm font-semibold">
                                Descargar formato
                            </a>
                        @endif
                    </div>
                @endforeach
            </div>

            <!-- 🔙 Botón de regreso -->
            <div class="flex justify-end pt-4 border-t border-[#EAEAEA]">
                <a href="{{ route('worker.index') }}"
                    class="px-6 py-2 bg-[#241178]/10 text-[#241178] hover:bg-[#241178]/20 font-semibold rounded-lg transition text-center">
                    ← Volver al panel
                </a>
            </div>
        </div>
    </div>
</x-layouts.app>
