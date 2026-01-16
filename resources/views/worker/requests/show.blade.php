{{-- 
* Nombre de la vista          : show.blade.php
* Descripción de la vista     : Vista de detalle de una solicitud de trámite del trabajador, mostrando el estado
*                               actual, fecha de solicitud y el seguimiento de pasos, incluyendo carga de archivos,
*                               envío de pasos al sindicato y visualización de documentos asociados.
* Fecha de creación           : 01/12/2025
* Elaboró                     : Iker Piza
* Fecha de liberación         : 14/01/2026
* Autorizó                    : Salvador Monroy
* Versión                     : 1.0
* Fecha de mantenimiento      :
* Folio de mantenimiento      :
* Tipo de mantenimiento       :
* Descripción del mantenimiento:
* Responsable                 :
* Revisor                     :
--}}

<x-layouts.app :title="__('Detalle del trámite')">

    <div class="w-full flex flex-col items-center min-h-[80vh] bg-white p-6">

        <div class="w-full max-w-4xl flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6">
            <div>
                <h1 class="text-3xl font-bold text-[#DE6601] mb-1">
                    {{ $procedure_request->procedure->name }}
                </h1>

                <p class="text-[#272800] text-sm max-w-xl">
                    {{ $procedure_request->procedure->description ?? 'Sin descripción registrada.' }}
                </p>
            </div>

            <flux:button icon="arrow-long-left" icon-variant="outline" variant="ghost" :href="route('worker.index')"
                class="mt-3 sm:mt-0">
                Regresar
            </flux:button>
        </div>

        <div class="w-full max-w-4xl bg-white border border-zinc-200 rounded-2xl shadow-md p-6">

            @php
                $colors = [
                    'initiated' => 'text-blue-600',
                    'in_progress' => 'text-[#DE6601]',
                    'pending_worker' => 'text-amber-600',
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
            @php
                $isFinished = in_array($procedure_request->status, ['completed', 'rejected', 'cancelled']);
            @endphp


            <h2 class="text-xl font-semibold text-[#241178] mb-4">
                Información general
            </h2>
            @if ($isFinished)
                <div class="mb-6 w-full rounded-xl border border-zinc-200 bg-zinc-50 p-4 text-sm">
                    <p class="font-semibold text-[#241178]">
                        Este trámite ya fue finalizado y no requiere más acciones.
                    </p>
                </div>
            @endif

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm mb-8">
                <p>
                    <strong class="text-[#272800]">Estado:</strong>
                    <span class="font-semibold {{ $colors[$procedure_request->status] ?? 'text-gray-600' }}">
                        {{ $labels[$procedure_request->status] ?? '—' }}
                    </span>
                </p>

                <p>
                    <strong class="text-[#272800]">Fecha de solicitud:</strong>
                    {{ $procedure_request->created_at->format('d/m/Y') }}
                </p>
            </div>

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

                        <h3 class="text-[#241178] font-semibold">
                            Paso {{ $number }}: {{ $step->step_name }}

                            @if ($isCurrent && !$isFinished)
                                <span class="text-[#DE6601] text-sm font-semibold">(Actual)</span>
                            @elseif ($locked)
                                <span class="text-gray-500 text-sm">(Bloqueado)</span>
                            @else
                                <span class="text-green-600 text-sm">(Aprobado)</span>
                            @endif
                        </h3>

                        <p class="text-[#272800] text-sm mt-1">
                            {{ $step->step_description ?? 'Sin descripción del paso.' }}
                        </p>

                        @if ($step->file_path)
                            <flux:button icon="arrow-down-tray" variant="filled" size="xs"
                                :href="asset('storage/' . $step->file_path)"
                                class="mt-3 !bg-gray-500 hover:!bg-gray-600 !text-white">
                                Descargar formato
                            </flux:button>
                        @endif

                        @if ($uploaded)
                            <p class="text-sm mt-3">
                                <strong class="text-[#272800]">Archivo enviado:</strong>
                                <a href="{{ asset('storage/' . $uploaded->file_path) }}"
                                    class="text-[#241178] hover:text-[#DE6601] underline font-semibold" target="_blank">
                                    {{ $uploaded->file_name }}
                                </a>
                            </p>
                        @endif

                        @if ($isCurrent && !$isFinished)

                            @if ($procedure_request->status === 'pending_union')
                                <div class="mt-4 border-t pt-4">
                                    <p class="text-sm text-[#241178] font-semibold">
                                        Este paso ya fue enviado al sindicato y está en revisión.
                                    </p>
                                </div>
                            @else
                                @if ($step->requires_file)
                                    @if ($procedure_request->status === 'pending_worker')
                                        <p class="text-amber-600 text-sm mt-3 font-semibold">
                                            El sindicato solicitó una corrección. Sube nuevamente el archivo para
                                            continuar.
                                        </p>
                                    @endif

                                    @if (!$uploaded || $procedure_request->status === 'pending_worker')
                                        <form
                                            action="{{ route('worker.procedures.upload', [$procedure_request->id, $step->id]) }}"
                                            method="POST" enctype="multipart/form-data" class="mt-4 border-t pt-4">
                                            @csrf

                                            <label class="text-sm font-semibold text-[#272800]">
                                                Subir archivo requerido
                                            </label>

                                            <input type="file" name="file" required
                                                class="block w-full border border-zinc-300 rounded-lg p-2 mt-1 text-sm">

                                            <flux:button type="submit" icon="arrow-up-tray" icon-variant="outline"
                                                variant="primary"
                                                class="mt-3 !bg-blue-600 hover:!bg-blue-700 !text-white">
                                                Enviar archivo al sindicato
                                            </flux:button>
                                        </form>
                                    @else
                                        <div class="mt-4 border-t pt-4">
                                            <p class="text-sm text-[#241178] font-semibold">
                                                Archivo enviado. Esperando revisión del sindicato.
                                            </p>
                                        </div>
                                    @endif
                                @else
                                    <form
                                        action="{{ route('worker.procedures.send-step', [$procedure_request->id, $step->id]) }}"
                                        method="POST" class="mt-4 border-t pt-4">
                                        @csrf

                                        <p class="text-sm text-gray-600 mb-2">
                                            Este paso no requiere archivo. Puedes enviarlo al sindicato para revisión.
                                        </p>

                                        <flux:button type="submit" icon="paper-airplane" icon-variant="outline"
                                            variant="primary" class="!bg-blue-600 hover:!bg-blue-700 !text-white">
                                            Enviar paso al sindicato
                                        </flux:button>
                                    </form>
                                @endif
                            @endif

                        @endif

                    </li>

                @endforeach
            </ol>

        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    @if (session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Enviado',
                text: @json(session('success')),
                confirmButtonText: 'Aceptar',
                confirmButtonColor: '#16a34a'
            });
        </script>
    @endif

    @if (session('error'))
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: @json(session('error')),
                confirmButtonText: 'Aceptar',
                confirmButtonColor: '#dc2626'
            });
        </script>
    @endif

</x-layouts.app>
