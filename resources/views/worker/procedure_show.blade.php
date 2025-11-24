{{-- ===========================================================
 Vista: procedure-show.blade.php
 Versión: 3.0 (Compatibilidad con RF-04 + requires_file)
=========================================================== --}}

<x-layouts.app :title="__('Detalle del Trámite')">
    <div class="max-w-4xl mx-auto bg-white shadow-lg rounded-xl p-6 mt-8">

        <h2 class="text-2xl font-bold text-[#241178] font-[Poppins] mb-4">
            {{ $procedure_request->procedure->name }}
        </h2>

        <p class="text-gray-700 mb-4">{{ $procedure_request->procedure->description }}</p>

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

        <p class="mb-6 text-sm">
            <strong>Estado actual:</strong>
            <span class="{{ $colors[$procedure_request->status] ?? 'text-gray-600' }} font-semibold">
                {{ $labels[$procedure_request->status] ?? '—' }}
            </span>
        </p>

        <h3 class="text-xl font-semibold text-[#DC6601] font-[Inter] mb-3">
            Pasos del trámite
        </h3>

        <ol class="list-decimal list-inside space-y-5">
            @foreach ($procedure_request->procedure->steps as $index => $step)
                @php
                    $number = $index + 1;
                    $locked = $number > $procedure_request->current_step;
                    $isCurrent = $number === $procedure_request->current_step;

                    $uploaded = $procedure_request->documents
                        ->where('procedure_step_id', $step->id)
                        ->first();
                @endphp

                <li class="border border-gray-200 rounded-lg p-4 shadow-sm {{ $locked ? 'opacity-60' : '' }}">
                    <div class="flex justify-between items-start">
                        <div>
                            <h4 class="text-[#241178] font-semibold">
                                Paso {{ $number }}: {{ $step->step_name }}

                                @if ($isCurrent)
                                    <span class="text-[#DC6601] text-sm font-semibold">(Actual)</span>
                                @elseif($locked)
                                    <span class="text-gray-500 text-sm">(Bloqueado)</span>
                                @else
                                    <span class="text-green-600 text-sm">(Completado)</span>
                                @endif
                            </h4>

                            <p class="text-gray-600 text-sm">{{ $step->step_description }}</p>

                            <p class="mt-2 text-sm">
                                <strong>Requiere archivo:</strong>
                                @if ($step->requires_file)
                                    <span class="text-red-600 font-semibold">Sí</span>
                                @else
                                    <span class="text-gray-700">No</span>
                                @endif
                            </p>

                            @if ($uploaded)
                                <p class="text-sm mt-1">
                                    <strong>Archivo subido:</strong>
                                    <a href="{{ asset('storage/' . $uploaded->file_path) }}" target="_blank" class="text-blue-600 underline">
                                        {{ $uploaded->file_name }}
                                    </a>
                                </p>
                            @endif
                        </div>

                        @if ($step->file_path)
                            <a href="{{ asset('storage/' . $step->file_path) }}"
                               class="text-[#DC6601] hover:underline text-sm font-semibold" target="_blank">
                                Descargar formato
                            </a>
                        @endif
                    </div>

                    {{-- SUBIDA OBLIGATORIA --}}
                    @if ($step->requires_file && !$locked && !$uploaded)
                        <form action="{{ route('worker.procedures.upload', [$procedure_request->id, $step->id]) }}"
                              method="POST" enctype="multipart/form-data" class="mt-3 border-t pt-3">
                            @csrf
                            <label class="text-sm font-medium text-gray-700">Subir archivo requerido:</label>
                            <input type="file" name="file" class="block w-full text-sm border rounded-md mt-1 p-2" required>

                            <button type="submit"
                                class="mt-2 bg-[#241178] hover:bg-[#1e0f6b] text-white px-4 py-1 rounded-lg">
                                Subir archivo
                            </button>
                        </form>
                    @endif

                    {{-- SUBIDA OPCIONAL --}}
                    @if (!$step->requires_file && $step->file_path && !$locked && !$uploaded)
                        <form action="{{ route('worker.procedures.upload', [$procedure_request->id, $step->id]) }}"
                              method="POST" enctype="multipart/form-data" class="mt-3 border-t pt-3">
                            @csrf
                            <label class="text-sm font-medium text-gray-700">Subir archivo (opcional):</label>
                            <input type="file" name="file" class="block w-full text-sm border rounded-md mt-1 p-2">

                            <button type="submit"
                                class="mt-2 bg-[#241178] hover:bg-[#1e0f6b] text-white px-4 py-1 rounded-lg">
                                Subir archivo
                            </button>
                        </form>
                    @endif

                    {{-- COMPLETAR PASO --}}
                    @if (!$locked)
                        <form action="{{ route('worker.procedures.complete-step', [$procedure_request->id, $step->id]) }}"
                              method="POST" class="mt-4">
                            @csrf

                            @if ($step->requires_file && !$uploaded)
                                <p class="text-red-600 text-sm mb-1">
                                    Debes subir el archivo requerido antes de continuar.
                                </p>
                            @else
                                <button type="submit"
                                    class="bg-[#DC6601] hover:bg-[#EE0000] text-white px-4 py-2 rounded-lg font-semibold transition">
                                    Marcar paso como realizado
                                </button>
                            @endif
                        </form>
                    @endif

                    @if ($locked)
                        <p class="text-gray-400 text-sm mt-2">
                            Este paso se habilitará cuando completes el paso anterior.
                        </p>
                    @endif
                </li>
            @endforeach
        </ol>

    </div>
</x-layouts.app>
