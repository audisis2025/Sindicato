{{-- 
* Nombre de la vista           : requests_show.blade.php
* Descripción de la vista      : Revisión de una solicitud de trámite realizada por un trabajador.
* Fecha de creación            : 04/11/2025
* Elaboró                      : Iker Piza
* Fecha de liberación          : 04/11/2025
* Autorizó                     : Líder Técnico
* Versión                      : 1.5
* Fecha de mantenimiento       : 27/11/2025
* Folio de mantenimiento       : N/A
* Tipo de mantenimiento        : Correctivo y perfectivo
* Descripción del mantenimiento: Corrección definitiva de rutas, botones y envío de notificaciones.
* Responsable                  : Iker Piza
* Revisor                      : QA SINDISOFT
--}}

<x-layouts.app :title="__('Revisión de solicitud de trámite')">

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

            <flux:button
                icon-variant="outline"
                variant="ghost"
                :href="route('union.requests.index')"
                class="px-4 py-2 !bg-transparent hover:!bg-zinc-100 !text-[#241178] font-semibold rounded-lg flex items-center gap-2 mt-3 sm:mt-0"
            >
                <x-heroicon-o-arrow-long-left class="w-5 h-5" />
                Volver
            </flux:button>
        </div>

        {{-- INFO --}}
        <div class="w-full max-w-6xl bg-white border border-[#D9D9D9] rounded-2xl shadow-md p-6 mb-8">
            <h2 class="text-xl font-semibold text-[#241178] mb-4">Información del trámite</h2>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">

                <p>
                    <strong class="text-[#272800]">Descripción:</strong>
                    {{ $requestData->procedure->description ?? '—' }}
                </p>

                <p>
                    <strong class="text-[#272800]">Fecha de solicitud:</strong>
                    {{ $requestData->created_at->format('d/m/Y') }}
                </p>

                <p>
                    <strong class="text-[#272800]">Estatus actual:</strong>
                    <span class="{{ 
                        $requestData->status === 'completed' ? 'text-green-600' :
                        ($requestData->status === 'rejected' ? 'text-red-600' : 'text-[#DE6601]')
                    }} font-semibold">
                        {{ ucfirst(str_replace('_',' ',$requestData->status)) }}
                    </span>
                </p>

            </div>
        </div>

        {{-- PASOS --}}
        <div class="w-full max-w-6xl bg-white border border-[#D9D9D9] rounded-2xl shadow-md p-6">

            <h2 class="text-xl font-semibold text-[#241178] mb-4">Revisión de pasos</h2>

            @forelse ($requestData->steps as $step)

                <div class="border border-[#D9D9D9]/60 rounded-xl p-5 mb-5">

                    <h3 class="font-semibold text-[#DE6601]">
                        Paso {{ $step->order }}: {{ $step->step_name }}
                    </h3>

                    <p class="text-sm text-[#272800] mt-1">
                        {{ $step->step_description ?? 'Sin descripción.' }}
                    </p>

                    {{-- DOCUMENTO --}}
                    @if ($step->worker_file)
                        <div class="mt-3">
                            <a href="{{ asset('storage/' . $step->worker_file) }}" target="_blank"
                                class="text-[#241178] hover:text-[#DE6601] underline text-sm font-semibold">
                                Ver documento enviado por el trabajador
                            </a>
                        </div>
                    @else
                        <p class="text-sm text-gray-500 mt-3">
                            El trabajador aún no ha subido un archivo para este paso.
                        </p>
                    @endif

                    {{-- BOTONES --}}
                    <div class="flex flex-wrap gap-3 mt-4">

                        {{-- APROBAR --}}
                        <form action="{{ route('union.requests.approve-step', [$requestData->id, $step->order]) }}" method="POST">
                            @csrf
                            <flux:button
                                icon="check"
                                icon-variant="outline"
                                variant="primary"
                                type="submit"
                                class="!bg-green-600 hover:!bg-green-700 !text-white text-sm px-4 py-2 rounded-lg"
                            >
                                Aprobar paso
                            </flux:button>
                        </form>

                        {{-- NOTIFICAR --}}
                        <flux:button
                            icon="x-circle"
                            icon-variant="outline"
                            variant="danger"
                            type="button"
                            onclick="openErrorModal({{ $step->order }})"
                            class="!bg-red-600 hover:!bg-red-700 !text-white text-sm px-4 py-2 rounded-lg"
                        >
                            Notificar error
                        </flux:button>

                    </div>

                </div>

            @empty
                <p class="text-sm text-gray-500">No hay pasos disponibles.</p>
            @endforelse

            {{-- FINAL --}}
            <div class="flex flex-wrap justify-end gap-3 mt-8">

                <form action="{{ route('union.requests.finalize', [$requestData->id, 'completed']) }}" method="POST">
                    @csrf
                    <flux:button class="!bg-green-600 hover:!bg-green-700 !text-white px-5 py-2 rounded-lg font-semibold">
                        Marcar como completado
                    </flux:button>
                </form>

                <form action="{{ route('union.requests.finalize', [$requestData->id, 'rejected']) }}" method="POST">
                    @csrf
                    <flux:button class="!bg-red-600 hover:!bg-red-700 !text-white px-5 py-2 rounded-lg font-semibold">
                        Rechazar trámite
                    </flux:button>
                </form>

            </div>

        </div>

    </div>

    {{-- MODAL ERROR --}}
    <div id="errorModal" class="fixed inset-0 bg-black/40 hidden items-center justify-center z-50">

        <div class="bg-white w-full max-w-md p-6 rounded-2xl shadow-xl">

            <h3 class="text-lg font-semibold text-[#DE6601] mb-3">Notificar error al trabajador</h3>

            <form id="errorForm" method="POST">
                @csrf

                <textarea name="error_message" rows="3" required
                    class="w-full border border-[#D9D9D9] rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-600"
                    placeholder="Describe el error detectado..."></textarea>

                <div class="flex justify-end gap-3 mt-4">

                    <flux:button
                        type="button"
                        variant="ghost"
                        onclick="closeErrorModal()"
                        class="px-4 py-2 !bg-transparent hover:!bg-zinc-100 !text-[#241178] rounded-lg font-semibold"
                    >
                        Cancelar
                    </flux:button>

                    <flux:button
                        type="submit"
                        class="px-4 py-2 !bg-blue-600 hover:!bg-blue-700 !text-white rounded-lg font-semibold"
                    >
                        Enviar notificación
                    </flux:button>

                </div>

            </form>

        </div>
    </div>

    <script>
        function openErrorModal(stepOrder) {
            const modal = document.getElementById('errorModal');
            const form = document.getElementById('errorForm');

            const baseUrl = "{{ url('/union/requests/' . $requestData->id . '/steps') }}";
            form.action = `${baseUrl}/${stepOrder}/notify-error`;

            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function closeErrorModal() {
            document.getElementById('errorModal').classList.add('hidden');
        }
    </script>

</x-layouts.app>
