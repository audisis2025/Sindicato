{{-- ===========================================================
 Nombre de la clase: procedures-requests-show.blade.php
 Descripción: Vista de revisión de una solicitud de trámite realizada por un trabajador.
 Fecha: 04/11/2025 | Versión: 1.0
 Tipo de mantenimiento: Creación.
 Descripción del mantenimiento: Implementa RF13 (Notificar errores al trabajador)
 y RF14 (Finalizar trámite como Completado o Rechazado) con estilo institucional.
 Responsable: Iker Piza
 Revisor: QA SINDISOFT
=========================================================== --}}

<x-layouts.app :title="__('Revisión de solicitud de trámite')">
    <div class="w-full flex flex-col items-center justify-start min-h-[80vh] bg-[#FFFFFF] text-[#000000] p-6">

        <!-- 🔹 Encabezado -->
        <div class="w-full max-w-6xl flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6">
            <div>
                <h1 class="text-3xl font-[Poppins] font-bold text-[#DC6601] mb-1">
                    {{ $requestData->tramite->nombre }}
                </h1>
                <p class="text-[#241178] font-[Inter] text-sm">
                    Solicitado por: <strong>{{ $requestData->trabajador->name }}</strong> 
                    ({{ $requestData->trabajador->email ?? 'sin correo' }})
                </p>
            </div>

            <a href="{{ route('union.reports.index') }}"
                class="px-4 py-2 bg-[#241178]/10 hover:bg-[#241178]/20 text-[#241178] font-semibold rounded-lg transition mt-3 sm:mt-0">
                ⬅️ Volver
            </a>
        </div>

        <!-- 🧾 Datos generales del trámite -->
        <div class="w-full max-w-6xl bg-white border border-[#D9D9D9] rounded-2xl shadow-md p-6 mb-8 font-[Inter]">
            <h2 class="text-xl font-semibold text-[#241178] mb-4">Información del trámite</h2>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
                <p><strong class="text-[#272800]">Descripción:</strong> {{ $requestData->tramite->descripcion ?? '—' }}</p>
                <p><strong class="text-[#272800]">Fecha de solicitud:</strong>
                    {{ $requestData->created_at->format('d/m/Y') }}</p>
                <p><strong class="text-[#272800]">Estatus actual:</strong>
                    <span class="{{ $requestData->estado === 'Completado' ? 'text-green-600' : ($requestData->estado === 'Rechazado' ? 'text-red-600' : 'text-[#DC6601]') }} font-semibold">
                        {{ $requestData->estado }}
                    </span>
                </p>
            </div>
        </div>

        <!-- 📋 Pasos del trámite con archivos del trabajador -->
        <div class="w-full max-w-6xl bg-white border border-[#D9D9D9] rounded-2xl shadow-md p-6 font-[Inter]">
            <h2 class="text-xl font-semibold text-[#241178] mb-4">Revisión de pasos</h2>

            @forelse ($requestData->pasos as $paso)
                <div class="border border-[#D9D9D9]/60 rounded-xl p-5 mb-5">
                    <h3 class="font-[Poppins] font-semibold text-[#DC6601]">
                        Paso {{ $paso->orden }}: {{ $paso->nombre_paso }}
                    </h3>
                    <p class="text-sm text-[#272800] mt-1">
                        {{ $paso->descripcion_paso ?? 'Sin descripción.' }}
                    </p>

                    <!-- Documento subido por el trabajador -->
                    @if ($paso->archivo_trabajador)
                        <div class="mt-3">
                            <a href="{{ asset('storage/' . $paso->archivo_trabajador) }}" target="_blank"
                                class="text-[#241178] hover:text-[#DC6601] font-semibold text-sm underline">
                                📎 Ver documento enviado por el trabajador
                            </a>
                        </div>
                    @else
                        <p class="text-sm text-gray-500 mt-3">El trabajador aún no ha subido el archivo.</p>
                    @endif

                    <!-- 🔸 Acciones del sindicato sobre el paso -->
                    <div class="flex flex-wrap gap-3 mt-4">
                        <form action="{{ route('union.procedures.approve-step', [$requestData->id, $paso->id]) }}" method="POST">
                            @csrf
                            <button type="submit"
                                class="bg-green-600 hover:bg-green-700 text-white text-sm px-4 py-2 rounded-lg transition">
                                ✅ Aprobar paso
                            </button>
                        </form>

                        <button type="button"
                            onclick="openErrorModal({{ $paso->id }})"
                            class="bg-yellow-500 hover:bg-yellow-600 text-white text-sm px-4 py-2 rounded-lg transition">
                            ⚠️ Notificar error
                        </button>
                    </div>
                </div>
            @empty
                <p class="text-gray-500 text-sm">No hay pasos disponibles para revisión.</p>
            @endforelse

            <!-- 🔸 Finalizar trámite -->
            <div class="flex flex-wrap justify-end gap-3 mt-8">
                <form action="{{ route('union.procedures.finalize', [$requestData->id, 'Completado']) }}" method="POST">
                    @csrf
                    <button type="submit"
                        class="bg-[#241178] hover:bg-[#1e0f6b] text-white font-semibold px-5 py-2 rounded-lg transition">
                        🏁 Marcar como completado
                    </button>
                </form>

                <form action="{{ route('union.procedures.finalize', [$requestData->id, 'Rechazado']) }}" method="POST">
                    @csrf
                    <button type="submit"
                        class="bg-[#EE0000] hover:bg-[#DC6601] text-white font-semibold px-5 py-2 rounded-lg transition">
                        ❌ Rechazar trámite
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- 🪟 Modal para notificar error -->
    <div id="errorModal"
        class="fixed inset-0 bg-black/40 hidden items-center justify-center z-50">
        <div class="bg-white w-full max-w-md p-6 rounded-2xl shadow-xl">
            <h3 class="text-lg font-[Poppins] font-semibold text-[#DC6601] mb-3">
                Notificar error al trabajador
            </h3>
            <form id="errorForm" method="POST">
                @csrf
                <textarea name="mensaje_error" rows="3" required
                    class="w-full border border-[#D9D9D9] rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-[#DC6601]"
                    placeholder="Describe el error detectado..."></textarea>
                <div class="flex justify-end gap-3 mt-4">
                    <button type="button" onclick="closeErrorModal()"
                        class="px-4 py-2 bg-[#241178]/10 hover:bg-[#241178]/20 text-[#241178] font-semibold rounded-lg transition">
                        Cancelar
                    </button>
                    <button type="submit"
                        class="px-4 py-2 bg-[#EE0000] hover:bg-[#DC6601] text-white font-semibold rounded-lg transition">
                        Enviar notificación
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- ⚙️ JS Modal -->
    <script>
        function openErrorModal(pasoId) {
            const modal = document.getElementById('errorModal');
            const form = document.getElementById('errorForm');
            form.action = `/union/procedures/${{{ $requestData->id }}}/steps/${pasoId}/notify-error`;
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function closeErrorModal() {
