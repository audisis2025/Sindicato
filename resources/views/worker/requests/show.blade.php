{{-- 
* Nombre de la vista         : show.blade.php
* Descripción                : Detalle completo de una solicitud iniciada por el trabajador.
* Módulo                     : Worker / Solicitudes
* Fecha de creación          : 27/11/2025
* Elaboró                    : Iker Piza
* Versión                    : 1.0
* Tipo de mantenimiento      : Creación
* Responsable                : Iker Piza
* Revisor                    : QA SINDISOFT
--}}

<x-layouts.app :title="__('Detalle del Trámite')">

    <div class="w-full flex flex-col items-center min-h-[80vh] bg-white p-6">

        {{-- HEADER --}}
        <div class="w-full max-w-5xl flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6">
            <div>
                <h1 class="text-3xl font-bold text-[#DE6601] mb-1 font-[Poppins]">
                    {{ $procedure_request->procedure->name }}
                </h1>

                <p class="text-[#241178] text-sm">
                    Estado actual:
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
                            'pending_worker' => 'Pendiente del trabajador',
                            'pending_union' => 'Pendiente del sindicato',
                            'completed' => 'Finalizado',
                            'cancelled' => 'Cancelado',
                            'rejected' => 'Rechazado',
                        ];
                    @endphp

                    <strong class="{{ $colors[$procedure_request->status] ?? 'text-gray-600' }}">
                        {{ $labels[$procedure_request->status] ?? '—' }}
                    </strong>
                </p>
            </div>

            <flux:button
                icon="arrow-long-left"
                icon-variant="outline"
                variant="ghost"
                :href="route('worker.index')"
                class="px-4 py-2 !bg-transparent hover:!bg-zinc-100 !text-[#241178] font-semibold rounded-lg flex items-center gap-2 mt-4 sm:mt-0"
            >
                Volver
            </flux:button>
        </div>

        {{-- CUERPO --}}
        <div class="w-full max-w-5xl bg-white border border-zinc-200 rounded-2xl shadow-sm p-6">

            {{-- ENCABEZADO DEL TRÁMITE --}}
            <div class="mb-6">
                <h2 class="text-xl font-semibold text-[#241178] font-[Poppins] mb-2">
                    Información del trámite
                </h2>

                <p class="text-gray-700 text-sm">
                    {{ $procedure_request->procedure->description ?? 'Este trámite no tiene descripción registrada.' }}
                </p>

                <p class="text-sm mt-3">
                    <strong class="text-[#272800]">Fecha de inicio:</strong>
                    {{ $procedure_request->created_at->format('d/m/Y') }}
                </p>
            </div>

            {{-- LISTA DE PASOS --}}
            <h2 class="text-xl font-semibold text-[#241178] font-[Poppins] mb-4">
                Pasos del trámite
            </h2>

            <ol class="list-decimal list-inside space-y-5">

                @foreach ($procedure_request->procedure->steps as $i => $step)

                    @php
                        $number = $i + 1;
                        $locked = $number > $procedure_request->current_step;
                        $isCurrent = $number === $procedure_request->current_step;

                        $uploaded = $procedure_request->documents
                            ->where('procedure_step_id', $step->id)
                            ->first();
                    @endphp

                    <li class="border border-zinc-200 rounded-xl p-5 shadow-sm {{ $locked ? 'opacity-60' : '' }}">

                        <div class="flex justify-between items-start">

                            {{-- DESCRIPCIÓN --}}
                            <div class="max-w-xl">
                                <h3 class="font-semibold text-[#241178]">
                                    Paso {{ $number }}: {{ $step->step_name }}

                                    @if ($isCurrent)
                                        <span class="text-[#DE6601] text-sm font-semibold">(Actual)</span>
                                    @elseif ($locked)
                                        <span class="text-gray-500 text-sm">(Bloqueado)</span>
                                    @else
                                        <span class="text-green-600 text-sm">(Completado)</span>
                                    @endif
                                </h3>

                                <p class="text-sm text-[#272800] mt-1">
                                    {{ $step->step_description ?? 'Sin descripción.' }}
                                </p>

                                {{-- Requiere archivo --}}
                                <p class="mt-2 text-sm">
                                    <strong>Requiere archivo:</strong>
                                    @if ($step->requires_file)
                                        <span class="text-red-600 font-semibold">Sí</span>
                                    @else
                                        <span class="text-gray-700">No</span>
                                    @endif
                                </p>

                                {{-- Archivo subido --}}
                                @if ($uploaded)
                                    <p class="text-sm mt-1">
                                        <strong>Archivo subido:</strong>
                                        <a href="{{ asset('storage/' . $uploaded->file_path) }}" target="_blank"
                                            class="text-blue-600 underline">
                                            {{ $uploaded->file_name }}
                                        </a>
                                    </p>
                                @endif
                            </div>

                            {{-- DESCARGAR FORMATO --}}
                            @if ($step->file_path)
                                <flux:button
                                    icon="arrow-down-tray"
                                    icon-variant="outline"
                                    variant="filled"
                                    size="xs"
                                    :href="asset('storage/' . $step->file_path)"
                                >
                                    Descargar formato
                                </flux:button>
                            @endif
                        </div>


                        {{-- SUBIDA DE ARCHIVO --}}
                        @if (!$locked)

                            {{-- Requerido --}}
                            @if ($step->requires_file && !$uploaded)
                                <form
                                    action="{{ route('worker.procedures.upload', [$procedure_request->id, $step->id]) }}"
                                    method="POST" enctype="multipart/form-data"
                                    class="mt-4 border-t border-zinc-200 pt-4"
                                >
                                    @csrf

                                    <label class="text-sm font-medium text-gray-700">Subir archivo requerido:</label>
                                    <input type="file" name="file"
                                           class="block w-full text-sm border rounded-md mt-1 p-2" required>

                                    <button
                                        class="mt-3 bg-[#241178] hover:bg-[#1e0f6b] text-white px-4 py-1 rounded-lg text-sm"
                                    >
                                        Subir archivo
                                    </button>
                                </form>
                            @endif

                            {{-- Opcional --}}
                            @if (!$step->requires_file && !$uploaded)
                                <form
                                    action="{{ route('worker.procedures.upload', [$procedure_request->id, $step->id]) }}"
                                    method="POST" enctype="multipart/form-data"
                                    class="mt-4 border-t border-zinc-200 pt-4"
                                >
                                    @csrf
                                    <label class="text-sm font-medium text-gray-700">Subir archivo (opcional):</label>
                                    <input type="file" name="file"
                                           class="block w-full text-sm border rounded-md mt-1 p-2">

                                    <button
                                        class="mt-3 bg-[#241178] hover:bg-[#1e0f6b] text-white px-4 py-1 rounded-lg text-sm"
                                    >
                                        Subir archivo
                                    </button>
                                </form>
                            @endif

                            {{-- COMPLETAR PASO --}}
                            @if ($uploaded || !$step->requires_file)
                                <form
                                    action="{{ route('worker.procedures.complete-step', [$procedure_request->id, $step->id]) }}"
                                    method="POST"
                                    class="mt-4"
                                >
                                    @csrf

                                    <flux:button
                                        icon="check"
                                        icon-variant="outline"
                                        variant="primary"
                                        class="!bg-blue-600 hover:!bg-blue-700 !text-white text-sm"
                                    >
                                        Marcar paso como realizado
                                    </flux:button>
                                </form>
                            @endif

                        @else
                            <p class="text-gray-400 text-sm mt-3">
                                Este paso se habilitará cuando completes el anterior.
                            </p>
                        @endif

                    </li>

                @endforeach

            </ol>

        </div>

    </div>

</x-layouts.app>
