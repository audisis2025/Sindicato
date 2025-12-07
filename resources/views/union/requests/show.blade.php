{{-- 
* Nombre de la vista           : requests_show.blade.php
* Descripción de la vista      : Revisión de una solicitud de trámite realizada por un trabajador.
* Fecha de creación            : 04/11/2025
* Elaboró                      : Iker Piza
* Fecha de liberación          : 04/11/2025
* Autorizó                     : Líder Técnico
* Versión                      : 1.7
* Fecha de mantenimiento       : 03/12/2025
* Folio de mantenimiento       : N/A
* Tipo de mantenimiento        : Correctivo y perfectivo
* Descripción del mantenimiento: Vista corregida completamente para lógica RF-13 / RF-14
* Responsable                  : Iker Piza
* Revisor                      : QA SINDISOFT
--}}

<x-layouts.app :title="__('Revisión de solicitud de trámite')">

    {{-- ==========================================
         ESTILO DE DEPURACIÓN (BORRAR DESPUÉS)
    =========================================== --}}
    <p style="background:#241178;color:white;padding:10px">VISTA: UNION.REQUESTS.SHOW</p>


    <div class="w-full flex flex-col items-center min-h-[80vh] bg-white p-6">

        {{-- HEADER --}}
        <div class="w-full max-w-6xl flex flex-col sm:flex-row justify-between mb-6">
            <div>
                <h1 class="text-3xl font-bold text-[#DE6601] mb-1">
                    {{ $requestData->procedure->name }}
                </h1>

                <p class="text-[#241178] text-sm">
                    Solicitado por:
                    <strong>{{ $requestData->user->name }}</strong>
                    ({{ $requestData->user->email ?? 'sin correo' }})
                </p>
            </div>

            <flux:button icon-variant="outline" variant="ghost" :href="route('union.requests.index')"
                class="px-4 py-2 !bg-transparent hover:!bg-zinc-100 !text-[#241178] font-semibold rounded-lg flex items-center gap-2 mt-3 sm:mt-0">
                <x-heroicon-o-arrow-long-left class="w-5 h-5" />
                Volver
            </flux:button>
        </div>


        {{-- ===========================
             INFORMACIÓN GENERAL
        ============================ --}}
        @php
            $labels = [
                'initiated' => 'Iniciado',
                'in_progress' => 'En proceso',
                'pending_worker' => 'Corrección requerida',
                'pending_union' => 'Pendiente de revisión del sindicato',
                'completed' => 'Finalizado',
                'cancelled' => 'Cancelado',
                'rejected' => 'Rechazado',
            ];
        @endphp

        <div class="w-full max-w-6xl bg-white border border-[#D9D9D9] rounded-2xl shadow-md p-6 mb-8">

            <h2 class="text-xl font-semibold text-[#241178] mb-4">Información del trámite</h2>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">

                <p>
                    <strong class="text-[#272800]">Descripción:</strong><br>
                    {{ $requestData->procedure->description ?? '—' }}
                </p>

                <p>
                    <strong class="text-[#272800]">Fecha de solicitud:</strong><br>
                    {{ $requestData->created_at->format('d/m/Y') }}
                </p>

                <p>
                    <strong class="text-[#272800]">Estatus actual:</strong><br>
                    <span class="font-semibold">
                        {{ $labels[$requestData->status] ?? '—' }}
                    </span>
                </p>

            </div>
        </div>



        {{-- ===========================
             LISTA DE PASOS
        ============================ --}}
        <div class="w-full max-w-6xl bg-white border border-[#D9D9D9] rounded-2xl shadow-md p-6">

            <h2 class="text-xl font-semibold text-[#241178] mb-4">Revisión de pasos</h2>

            @forelse ($requestData->procedure->steps as $step)
                @php
                    $document = $requestData->documents->where('procedure_step_id', $step->id)->first();
                    $isCurrent = $step->order === $requestData->current_step;
                @endphp

                <div class="border border-[#D9D9D9]/60 rounded-xl p-5 mb-5">

                    <h3 class="font-semibold text-[#DE6601]">
                        Paso {{ $step->order }}: {{ $step->step_name }}

                        @if ($step->order < $requestData->current_step)
                            <span class="text-green-600 text-sm">(Aprobado)</span>
                        @elseif ($isCurrent && $requestData->status === 'pending_worker')
                            <span class="text-red-600 text-sm">(Corrección requerida)</span>
                        @elseif ($isCurrent)
                            <span class="text-[#DE6601] text-sm">(Actual)</span>
                        @else
                            <span class="text-gray-500 text-sm">(Pendiente)</span>
                        @endif
                    </h3>

                    <p class="text-sm text-[#272800] mt-1">
                        {{ $step->step_description ?? 'Sin descripción.' }}
                    </p>


                    {{-- DOCUMENTO SUBIDO --}}
                    @if ($document)
                        <div class="mt-3">
                            <a href="{{ asset('storage/' . $document->file_path) }}" target="_blank"
                                class="text-[#241178] hover:text-[#DE6601] underline text-sm font-semibold">
                                Ver documento enviado por el trabajador
                            </a>
                        </div>
                    @else
                        <p class="text-sm text-gray-500 mt-3">
                            El trabajador aún no ha subido archivo.
                        </p>
                    @endif


                    {{-- =============================================
                         BOTONES (SÓLO SI ESTE ES EL PASO ACTUAL 
                         Y EL TRABAJADOR YA LO ENVIÓ AL SINDICATO)
                    ============================================== --}}
                    @if ($isCurrent && $requestData->status === 'pending_union')
                        <div class="flex flex-wrap gap-3 mt-4">

                            {{-- APROBAR --}}
                            <flux:button icon="check" icon-variant="outline" type="button"
                                class="!bg-green-600 hover:!bg-green-700 !text-white text-sm px-4 py-2 rounded-lg"
                                onclick="approveStep({{ $step->order }})">
                                Aprobar paso
                            </flux:button>

                            {{-- NOTIFICAR ERROR --}}
                            <flux:button icon="x-circle" icon-variant="outline" type="button"
                                class="!bg-red-600 hover:!bg-red-700 !text-white text-sm px-4 py-2 rounded-lg"
                                onclick="notifyError({{ $step->order }})">
                                Notificar error
                            </flux:button>
                            <flux:button icon="x-mark" icon-variant="outline" type="button"
                                class="!bg-orange-600 hover:!bg-orange-700 !text-white text-sm px-4 py-2 rounded-lg"
                                onclick="rejectStep({{ $step->order }})">
                                Rechazar paso
                            </flux:button>

                        </div>
                    @endif

                </div>

            @empty
                <p class="text-sm text-gray-500">No hay pasos.</p>
            @endforelse

        </div>

    </div>


    {{-- ===========================================
         SWEETALERT2 + SCRIPTS
    ============================================ --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        /* =======================================================
                       APROBAR PASO
                    ======================================================= */
        function approveStep(order) {
            Swal.fire({
                title: '¿Aprobar paso?',
                text: 'Esta acción aprobará este paso del trámite.',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Aprobar',
                cancelButtonText: 'Cancelar',
                confirmButtonColor: '#16a34a',
                cancelButtonColor: '#6b7280'
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = document.createElement('form');
                    form.method = "POST";
                    form.action =
                        "{{ url('/union/requests/' . $requestData->id . '/steps') }}/" + order + "/review";

                    const csrf = document.createElement('input');
                    csrf.type = "hidden";
                    csrf.name = "_token";
                    csrf.value = "{{ csrf_token() }}";

                    const resultInput = document.createElement('input');
                    resultInput.type = "hidden";
                    resultInput.name = "result";
                    resultInput.value = "approve";

                    form.appendChild(csrf);
                    form.appendChild(resultInput);

                    document.body.appendChild(form);
                    form.submit();
                }
            });
        }



        /* =======================================================
           NOTIFICAR ERROR
        ======================================================= */
        async function notifyError(stepOrder) {

            const {
                value: msg
            } = await Swal.fire({
                title: 'Notificar error al trabajador',
                input: 'textarea',
                inputLabel: 'Describe el error encontrado',
                inputValidator: (value) => {
                    if (!value) return 'Debes escribir un mensaje';
                },
                showCancelButton: true,
                confirmButtonText: 'Enviar',
                cancelButtonText: 'Cancelar',
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#6b7280'
            });

            if (!msg) return;

            const url =
                "{{ url('/union/requests/' . $requestData->id . '/steps') }}/" +
                stepOrder + "/notify-error";

            const response = await fetch(url, {
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": "{{ csrf_token() }}",
                    "Content-Type": "application/json",
                },
                body: JSON.stringify({
                    error_message: msg
                })
            });

            Swal.fire({
                icon: response.ok ? 'success' : 'error',
                title: response.ok ? 'Notificación enviada' : 'Error',
                text: response.ok ?
                    'El trabajador ha sido notificado.' : 'Hubo un problema al enviar la notificación.'
            }).then(() => response.ok && location.reload());
        }

        function rejectStep(order) {
            Swal.fire({
                title: 'Rechazar paso',
                text: 'El paso será rechazado. Si el trámite tiene flujo alterno, será enviado automáticamente al paso correspondiente.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Rechazar',
                cancelButtonText: 'Cancelar',
                confirmButtonColor: '#ea580c',
                cancelButtonColor: '#6b7280'
            }).then((result) => {
                if (result.isConfirmed) {

                    const form = document.createElement('form');
                    form.method = "POST";
                    form.action =
                        "{{ url('/union/requests/' . $requestData->id . '/steps') }}/" + order + "/review";

                    const csrf = document.createElement('input');
                    csrf.type = "hidden";
                    csrf.name = "_token";
                    csrf.value = "{{ csrf_token() }}";

                    const resultInput = document.createElement('input');
                    resultInput.type = "hidden";
                    resultInput.name = "result";
                    resultInput.value = "reject";

                    form.appendChild(csrf);
                    form.appendChild(resultInput);

                    document.body.appendChild(form);
                    form.submit();
                }
            });
        }
    </script>

</x-layouts.app>
