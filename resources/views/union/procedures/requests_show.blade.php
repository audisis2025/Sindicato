{{-- ===========================================================
 Nombre de la clase: procedures-requests-show.blade.php
 Descripción: Revisión de una solicitud de trámite realizada por un trabajador.
 Fecha: 04/11/2025 | Versión: 1.4
 Tipo de mantenimiento: Correctivo-Perfectivo
 Descripción del mantenimiento: Homogeneización de claves a inglés (procedure, steps),
 validación de archivos, actualización de rutas y corrección del modal.
 Responsable: Iker Piza
 Revisor: QA SINDISOFT
=========================================================== --}}

<x-layouts.app :title="__('Revisión de solicitud de trámite')">
    <div class="w-full flex flex-col items-center min-h-[80vh] bg-white p-6">

        {{-- Encabezado --}}
        <div class="w-full max-w-6xl flex flex-col sm:flex-row justify-between mb-6">
            <div>
                <h1 class="text-3xl font-[Poppins] font-bold text-[#DC6601] mb-1">
                    {{ $requestData->procedure->name }}
                </h1>

                <p class="text-[#241178] font-[Inter] text-sm">
                    Solicitado por: 
                    <strong>{{ $requestData->user->name }}</strong>
                    ({{ $requestData->user->email ?? 'sin correo' }})
                </p>
            </div>

            <a href="{{ route('union.requests.index') }}"
                class="px-4 py-2 bg-[#241178]/10 hover:bg-[#241178]/20 text-[#241178] font-semibold rounded-lg transition mt-3 sm:mt-0">
                Volver
            </a>
        </div>

        {{-- Información del trámite --}}
        <div class="w-full max-w-6xl bg-white border border-[#D9D9D9] rounded-2xl shadow-md p-6 mb-8">
            <h2 class="text-xl font-semibold text-[#241178] mb-4">Información del trámite</h2>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
                <p><strong class="text-[#272800]">Descripción:</strong> {{ $requestData->procedure->description ?? '—' }}</p>

                <p>
                    <strong class="text-[#272800]">Fecha de solicitud:</strong>
                    {{ $requestData->created_at->format('d/m/Y') }}
                </p>

                <p>
                    <strong class="text-[#272800]">Estatus actual:</strong>
                    <span class="
                        {{
                            $requestData->status === 'completed'
                                ? 'text-green-600'
                                : ($requestData->status === 'rejected'
                                    ? 'text-red-600'
                                    : 'text-[#DC6601]')
                        }} font-semibold">
                        {{ ucfirst($requestData->status) }}
                    </span>
                </p>
            </div>
        </div>

        {{-- Pasos del trámite --}}
        <div class="w-full max-w-6xl bg-white border border-[#D9D9D9] rounded-2xl shadow-md p-6">
            <h2 class="text-xl font-semibold text-[#241178] mb-4">Revisión de pasos</h2>

            @forelse ($requestData->steps as $step)
                <div class="border border-[#D9D9D9]/60 rounded-xl p-5 mb-5">

                    <h3 class="font-[Poppins] font-semibold text-[#DC6601]">
                        Paso {{ $step->order }}: {{ $step->step_name }}
                    </h3>

                    <p class="text-sm text-[#272800] mt-1">
                        {{ $step->step_description ?? 'Sin descripción.' }}
                    </p>

                    {{-- Documento subido por el trabajador --}}
                    @if ($step->worker_file)
                        <div class="mt-3">
                            <a href="{{ asset('storage/' . $step->worker_file) }}" target="_blank"
                                class="text-[#241178] hover:text-[#DC6601] underline text-sm font-semibold">
                                Ver documento enviado por el trabajador
                            </a>
                        </div>
                    @else
                        <p class="text-sm text-gray-500 mt-3">
                            El trabajador aún no ha subido un archivo para este paso.
                        </p>
                    @endif

                    {{-- Acciones sobre el paso --}}
                    <div class="flex flex-wrap gap-3 mt-4">

                        {{-- Aprobar paso --}}
                        <form action="{{ route('union.procedure-requests.approve-step', [$requestData->id, $step->id]) }}"
                              method="POST">
                            @csrf
                            <button type="submit"
                                class="bg-green-600 hover:bg-green-700 text-white text-sm px-4 py-2 rounded-lg transition">
                                Aprobar paso
                            </button>
                        </form>

                        {{-- Notificar error --}}
                        <button type="button" onclick="openErrorModal({{ $step->id }})"
                            class="bg-yellow-500 hover:bg-yellow-600 text-white text-sm px-4 py-2 rounded-lg transition">
                            Notificar error
                        </button>

                    </div>
                </div>

            @empty
                <p class="text-sm text-gray-500">No hay pasos disponibles para revisión.</p>
            @endforelse

            {{-- Finalizar trámite --}}
            <div class="flex flex-wrap justify-end gap-3 mt-8">

                <form action="{{ route('union.procedure-requests.finalize', [$requestData->id, 'completed']) }}" method="POST">
                    @csrf
                    <button type="submit"
                        class="bg-[#241178] hover:bg-[#1e0f6b] text-white px-5 py-2 rounded-lg font-semibold transition">
                        Marcar como completado
                    </button>
                </form>

                <form action="{{ route('union.procedure-requests.finalize', [$requestData->id, 'rejected']) }}" method="POST">
                    @csrf
                    <button type="submit"
                        class="bg-[#EE0000] hover:bg-[#DC6601] text-white px-5 py-2 rounded-lg font-semibold transition">
                        Rechazar trámite
                    </button>
                </form>

            </div>
        </div>

    </div>

    {{-- ========================= MODAL ========================= --}}
    <div id="errorModal"
        class="fixed inset-0 bg-black/40 hidden items-center justify-center z-50">

        <div class="bg-white w-full max-w-md p-6 rounded-2xl shadow-xl">
            <h3 class="text-lg font-[Poppins] font-semibold text-[#DC6601] mb-3">
                Notificar error al trabajador
            </h3>

            <form id="errorForm" method="POST">
                @csrf

                <textarea name="error_message" rows="3" required
                    class="w-full border border-[#D9D9D9] rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-[#DC6601]"
                    placeholder="Describe el error detectado..."></textarea>

                <div class="flex justify-end gap-3 mt-4">

                    <button type="button" onclick="closeErrorModal()"
                        class="px-4 py-2 bg-[#241178]/10 hover:bg-[#241178]/20 text-[#241178] rounded-lg font-semibold">
                        Cancelar
                    </button>

                    <button type="submit"
                        class="px-4 py-2 bg-[#EE0000] hover:bg-[#DC6601] text-white rounded-lg font-semibold">
                        Enviar notificación
                    </button>

                </div>

            </form>
        </div>

    </div>

    {{-- ========================= JS ========================= --}}
    <script>
        function openErrorModal(stepId) {
            const modal = document.getElementById('errorModal');
            const form = document.getElementById('errorForm');

            // Construcción correcta de la ruta
            form.action = `/union/procedure-requests/{{ $requestData->id }}/steps/${stepId}/notify-error`;

            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function closeErrorModal() {
            const modal = document.getElementById('errorModal');
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }
    </script>

</x-layouts.app>
