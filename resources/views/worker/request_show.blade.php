{{-- ===========================================================
 Nombre de la vista: request-show.blade.php
 Descripción: Detalle completo de una solicitud de trámite.
 Versión: 2.0 (Compatibilidad RF-04)
=========================================================== --}}

<x-layouts.app :title="__('Detalle del trámite solicitado')">

    <div class="w-full flex flex-col items-center justify-center min-h-[80vh] bg-[#FFFFFF] p-6">

        <!-- Encabezado -->
        <h1 class="text-3xl font-[Poppins] font-bold text-[#DC6601] mb-2">
            Detalle del Trámite Solicitado
        </h1>
        <p class="text-[#241178] font-[Inter] mb-8 text-center">
            Consulta la información completa del trámite y su progreso.
        </p>

        <!-- Contenedor principal -->
        <div class="w-full max-w-3xl bg-white border border-[#D9D9D9] shadow-md rounded-2xl p-8 font-[Inter] space-y-6">

            <!-- Información general -->
            <div class="border-b border-[#EAEAEA] pb-4 mb-4">
                <h2 class="text-2xl font-[Poppins] font-bold text-[#241178] mb-2">
                    {{ $solicitud->procedure->name }}
                </h2>

                <p class="text-gray-700 text-sm mb-3">
                    {{ $solicitud->procedure->description }}
                </p>

                <p class="text-sm text-[#272800]">
                    <strong>Fecha de solicitud:</strong>
                    {{ $solicitud->created_at->format('d/m/Y H:i') }}
                </p>

                @php
                    // Mapper RF-04 → estilos
                    $statusColors = [
                        'initiated'        => 'text-blue-600',
                        'in_progress'      => 'text-orange-600',
                        'pending_worker'   => 'text-yellow-600',
                        'pending_union'    => 'text-purple-600',
                        'completed'        => 'text-green-600',
                        'cancelled'        => 'text-gray-500',
                        'rejected'         => 'text-red-600',
                    ];

                    $statusLabels = [
                        'initiated'        => 'Iniciado',
                        'in_progress'      => 'En proceso',
                        'pending_worker'   => 'Pendiente de acción del trabajador',
                        'pending_union'    => 'Pendiente sindicato',
                        'completed'        => 'Finalizado',
                        'cancelled'        => 'Cancelado',
                        'rejected'         => 'Rechazado',
                    ];

                    $color = $statusColors[$solicitud->status] ?? 'text-[#DC6601]';
                    $label = $statusLabels[$solicitud->status] ?? 'Pendiente';
                @endphp

                <p class="text-sm text-[#272800]">
                    <strong>Estado actual:</strong>
                    <span class="{{ $color }} font-semibold">
                        {{ $label }}
                    </span>
                </p>

                <p class="text-sm text-[#272800]">
                    <strong>Paso actual:</strong>
                    {{ $solicitud->current_step }} de {{ $solicitud->procedure->steps_count }}
                </p>
            </div>

            <!-- Barra de progreso -->
            @php
                $total = $solicitud->procedure->steps_count;
                $progress = $total > 0 ? ($solicitud->current_step / $total) * 100 : 0;
            @endphp

            <div class="w-full bg-[#EAEAEA] rounded-full h-3 overflow-hidden mb-6">
                <div class="bg-[#DC6601] h-3 rounded-full transition-all duration-500"
                     style="width: {{ $progress }}%">
                </div>
            </div>

            <!-- Pasos -->
            <h3 class="text-xl font-semibold text-[#DC6601] mb-4 font-[Poppins]">
                Pasos del trámite
            </h3>

            @foreach ($solicitud->procedure->steps as $index => $paso)
                @php
                    $num = $index + 1;
                    $completed = $num < $solicitud->current_step || $solicitud->status === 'completed';
                @endphp

                <div class="border border-[#D9D9D9] rounded-lg p-5 mb-5 shadow-sm bg-white">
                    <h4 class="text-[#241178] font-semibold text-lg mb-1">
                        Paso {{ $num }}: {{ $paso->step_name }}
                        @if ($completed)
                            <span class="text-green-600 text-sm font-semibold">(Completado)</span>
                        @elseif($num == $solicitud->current_step)
                            <span class="text-[#DC6601] text-sm font-semibold">(En progreso)</span>
                        @endif
                    </h4>

                    <p class="text-gray-600 text-sm mb-2">
                        {{ $paso->step_description }}
                    </p>

                    @if ($paso->file_path)
                        <a href="{{ asset('storage/' . $paso->file_path) }}"
                           class="text-[#DC6601] hover:underline text-sm font-semibold">
                            Descargar formato
                        </a>
                    @endif
                </div>
            @endforeach

            <!-- Regresar -->
            <div class="flex justify-end pt-4 border-t border-[#EAEAEA]">
                <a href="{{ route('worker.index') }}"
                   class="px-6 py-2 bg-[#241178]/10 text-[#241178] hover:bg-[#241178]/20 font-semibold rounded-lg transition">
                    ← Volver al panel
                </a>
            </div>
        </div>
    </div>

</x-layouts.app>
