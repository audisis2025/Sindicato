{{-- 
* Nombre de la vista          : show.blade.php
* Descripción de la vista     : Vista de revisión y detalle de una solicitud de trámite por parte del sindicato,
*                               donde se muestra información general del trámite, el estatus actual y el listado
*                               de pasos para aprobar, rechazar o notificar correcciones al trabajador.
* Fecha de creación           : 24/11/2025
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

<x-layouts.app :title="__('Revisión de solicitud de trámite')">

    <div class="w-full flex flex-col items-center min-h-[80vh] bg-white p-6">

        <div class="w-full max-w-6xl flex flex-col sm:flex-row justify-between mb-6">
            <div>
                <h1 class="text-3xl font-bold text-[#DE6601] mb-1">
                    {{ $requestData->procedure->name }}
                </h1>

                <p class="text-[#272800] text-sm">
                    Solicitado por:
                    <strong>{{ $requestData->user->name }}</strong>
                    ({{ $requestData->user->email ?? 'sin correo' }})
                </p>
            </div>

            <flux:button
                icon="arrow-long-left"
                icon-variant="outline"
                variant="ghost"
                :href="route('union.requests.index')"
                class="mt-3 sm:mt-0"
            >
                Regresar
            </flux:button>
        </div>

        @php
            $labels = [
                'initiated' => 'Iniciado',
                'started' => 'Iniciado',
                'in_progress' => 'En proceso',
                'pending_worker' => 'Corrección requerida',
                'pending_union' => 'Pendiente de revisión del sindicato',
                'completed' => 'Finalizado',
                'cancelled' => 'Cancelado',
                'rejected' => 'Rechazado',
                'pending' => 'Pendiente',
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

                    @if ($document)
                        <div class="mt-3">
                            <a
                                href="{{ asset('storage/' . $document->file_path) }}"
                                target="_blank"
                                class="text-[#241178] hover:text-[#DE6601] underline text-sm font-semibold"
                            >
                                Ver documento enviado por el trabajador
                            </a>
                        </div>
                    @else
                        <p class="text-sm text-gray-500 mt-3">
                            El trabajador aún no ha subido archivo.
                        </p>
                    @endif

                    @if ($isCurrent && $requestData->status === 'pending_union')
                        <div class="flex flex-wrap gap-3 mt-4">

                            <flux:button
                                icon="check-circle"
                                icon-variant="outline"
                                type="button"
                                variant="primary"
                                class="!bg-green-600 hover:!bg-green-700 !text-white"
                                onclick="approveStep({{ $step->order }})"
                            >
                                Aprobar paso
                            </flux:button>

                            <flux:button
                                icon="x-circle"
                                icon-variant="outline"
                                type="button"
                                variant="primary"
                                class="!bg-red-600 hover:!bg-red-700 !text-white"
                                onclick="notifyError({{ $step->order }})"
                            >
                                Notificar error
                            </flux:button>

                            <flux:button
                                icon="x-mark"
                                icon-variant="outline"
                                type="button"
                                variant="primary"
                                class="!bg-orange-600 hover:!bg-orange-700 !text-white"
                                onclick="rejectStep({{ $step->order }})"
                            >
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

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        const csrfToken = "{{ csrf_token() }}";

        function approveUrl(order) {
            const template = @json(route('union.requests.approve-step', ['id' => $requestData->id, 'order' => '__ORDER__']));
            return template.replace('__ORDER__', order);
        }

        function notifyUrl(order) {
            const template = @json(route('union.requests.notify-error', ['id' => $requestData->id, 'order' => '__ORDER__']));
            return template.replace('__ORDER__', order);
        }

        function postForm(action, extraInputs = {}) {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = action;

            const csrf = document.createElement('input');
            csrf.type = 'hidden';
            csrf.name = '_token';
            csrf.value = csrfToken;
            form.appendChild(csrf);

            Object.keys(extraInputs).forEach((key) => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = key;
                input.value = extraInputs[key];
                form.appendChild(input);
            });

            document.body.appendChild(form);
            form.submit();
        }

        function approveStep(order) {
            Swal.fire({
                title: 'Aprobar paso',
                text: 'Se aprobará el paso actual.',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Confirmar',
                cancelButtonText: 'Cancelar',
                confirmButtonColor: '#16a34a',
                cancelButtonColor: '#6b7280'
            }).then((result) => {
                if (result.isConfirmed) {
                    postForm(approveUrl(order), { result: 'approve' });
                }
            });
        }

        function rejectStep(order) {
            Swal.fire({
                title: 'Rechazar paso',
                text: 'El paso será rechazado.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Confirmar',
                cancelButtonText: 'Cancelar',
                confirmButtonColor: '#ea580c',
                cancelButtonColor: '#6b7280'
            }).then((result) => {
                if (result.isConfirmed) {
                    postForm(approveUrl(order), { result: 'reject' });
                }
            });
        }

        async function notifyError(stepOrder) {
            const result = await Swal.fire({
                title: 'Notificar error',
                input: 'textarea',
                inputPlaceholder: 'Describe el error encontrado...',
                showCancelButton: true,
                confirmButtonText: 'Enviar',
                cancelButtonText: 'Cancelar',
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#6b7280',
                inputValidator: (value) => {
                    if (!value) return 'Debes escribir un mensaje.';
                    if (value.length > 500) return 'El mensaje no debe exceder 500 caracteres.';
                }
            });

            if (!result.isConfirmed) return;

            const response = await fetch(notifyUrl(stepOrder), {
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": csrfToken,
                    "Content-Type": "application/json",
                    "Accept": "application/json",
                },
                body: JSON.stringify({ error_message: result.value })
            });

            Swal.fire({
                icon: response.ok ? 'success' : 'error',
                title: response.ok ? 'Enviado' : 'Error',
                text: response.ok ? 'El trabajador fue notificado.' : 'No fue posible enviar la notificación.',
                confirmButtonText: 'Aceptar',
                confirmButtonColor: response.ok ? '#16a34a' : '#dc2626'
            }).then(() => {
                if (response.ok) location.reload();
            });
        }
    </script>

</x-layouts.app>
