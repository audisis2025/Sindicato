<x-layouts.app :title="__('Detalle del Trámite')">

    <div class="w-full flex flex-col items-center min-h-[80vh] bg-white p-6">

        {{-- HEADER --}}
        <div class="w-full max-w-4xl flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6">
            <div>
                <h1 class="text-3xl font-bold text-[#DE6601] font-[Poppins] mb-1">
                    {{ $procedure_request->procedure->name }}
                </h1>

                <p class="text-gray-700 text-sm max-w-xl">
                    {{ $procedure_request->procedure->description ?? 'Sin descripción registrada.' }}
                </p>
            </div>

            <flux:button icon="arrow-long-left" icon-variant="outline" variant="ghost" :href="route('worker.index')"
                class="px-4 py-2 !bg-transparent hover:!bg-zinc-100 !text-[#241178] font-semibold rounded-lg mt-3 sm:mt-0">
                Volver
            </flux:button>
        </div>

        {{-- CARD --}}
        <div class="w-full max-w-4xl bg-white border border-zinc-200 rounded-2xl shadow-md p-6">

            {{-- ESTADO --}}
            @php
                $colors = [
                    'initiated' => 'text-blue-600',
                    'in_progress' => 'text-[#DE6601]',
                    'pending_worker' => 'text-yellow-600',
                    'pending_union' => 'text-purple-600',
                    'completed' => 'text-green-600',
                    'cancelled' => 'text-gray-600',
                    'rejected' => 'text-red-600',
                ];

                $labels = [
                    'initiated' => 'Iniciado',
                    'in_progress' => 'En proceso',
                    'pending_worker' => 'Corrección requerida',
                    'pending_union' => 'Revisión del sindicato',
                    'completed' => 'Finalizado',
                    'cancelled' => 'Cancelado',
                    'rejected' => 'Rechazado',
                ];
            @endphp

            <h2 class="text-xl font-semibold text-[#241178] mb-4">
                Información general
            </h2>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm mb-8">
                <p>
                    <strong>Estado:</strong>
                    <span class="font-semibold {{ $colors[$procedure_request->status] }}">
                        {{ $labels[$procedure_request->status] }}
                    </span>
                </p>

                <p>
                    <strong>Fecha de solicitud:</strong>
                    {{ $procedure_request->created_at->format('d/m/Y') }}
                </p>
            </div>

            {{-- PASOS --}}
            <h2 class="text-xl font-semibold text-[#241178] mb-4">Pasos del trámite</h2>

            <ol class="list-decimal list-inside space-y-6">
                @foreach ($procedure_request->procedure->steps as $index => $step)

                    @php
                        $number = $index + 1;
                        $locked = $number > $procedure_request->current_step;
                        $isCurrent = $number === $procedure_request->current_step;

                        $uploaded = $procedure_request->documents->where('procedure_step_id', $step->id)->first();
                    @endphp

                    <li class="border border-zinc-200 rounded-xl p-5 shadow-sm {{ $locked ? 'opacity-60' : '' }}">

                        {{-- TÍTULO --}}
                        <h3 class="text-[#241178] font-semibold">
                            Paso {{ $number }}: {{ $step->step_name }}

                            @if ($isCurrent)
                                <span class="text-[#DE6601] text-sm font-semibold">(Actual)</span>
                            @elseif ($locked)
                                <span class="text-gray-500 text-sm">(Bloqueado)</span>
                            @else
                                <span class="text-green-600 text-sm">(Aprobado)</span>
                            @endif
                        </h3>

                        <p class="text-gray-700 text-sm mt-1">
                            {{ $step->step_description }}
                        </p>

                        {{-- FORMATOS --}}
                        @if ($step->file_path)
                            <a href="{{ asset('storage/' . $step->file_path) }}" target="_blank"
                                class="text-[#241178] underline text-sm font-semibold mt-3 block">
                                Descargar formato
                            </a>
                        @endif

                        {{-- ARCHIVO SUBIDO --}}
                        @if ($uploaded)
                            <p class="text-sm mt-2">
                                <strong>Archivo enviado:</strong>
                                <a href="{{ asset('storage/' . $uploaded->file_path) }}"
                                    class="text-blue-600 underline" target="_blank">
                                    {{ $uploaded->file_name }}
                                </a>
                            </p>
                        @endif

                        {{-- SI EL PASO ESTÁ ACTIVO --}}
                        {{-- ACCIONES DEL PASO (SOLO SI ES EL ACTUAL) --}}
                        @if ($isCurrent)
                            {{-- SI EL PASO REQUIERE ARCHIVO --}}
                            @if ($step->requires_file)
                                {{-- MENSAJE POR CORRECCIÓN --}}
                                @if ($procedure_request->status === 'pending_worker')
                                    <p class="text-yellow-600 text-sm mt-3">
                                        ⚠ El sindicato solicitó una corrección.
                                        Sube nuevamente el archivo para continuar.
                                    </p>
                                @endif

                                {{-- FORMULARIO DE SUBIDA --}}
                                @if (!$uploaded)
                                    <form
                                        action="{{ route('worker.procedures.upload', [$procedure_request->id, $step->id]) }}"
                                        method="POST" enctype="multipart/form-data" class="mt-4 border-t pt-4">
                                        @csrf

                                        <label class="text-sm font-medium text-[#272800]">
                                            Subir archivo requerido:
                                        </label>

                                        <input type="file" name="file"
                                            class="block w-full border rounded-lg p-2 mt-1 text-sm" required>

                                        <flux:button type="submit" icon="arrow-up-tray" variant="primary"
                                            class="mt-3">
                                            Enviar archivo al sindicato
                                        </flux:button>
                                    </form>
                                @endif
                            @else
                                {{-- SI NO REQUIERE ARCHIVO --}}
                                <form
                                    action="{{ route('worker.procedures.send-step', [$procedure_request->id, $step->id]) }}"
                                    method="POST" class="mt-4 border-t pt-4">
                                    @csrf

                                    <p class="text-sm text-gray-600 mb-2">
                                        Este paso no requiere archivo. Puedes enviarlo al sindicato para revisión.
                                    </p>

                                    <flux:button type="submit" icon="paper-airplane" variant="primary"
                                        class="!bg-blue-600 hover:!bg-blue-700 !text-white">
                                        Enviar paso al sindicato
                                    </flux:button>
                                </form>
                            @endif
                        @endif

                    </li>

                @endforeach
            </ol>

        </div>

    </div>

</x-layouts.app>
