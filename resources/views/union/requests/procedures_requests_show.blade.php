{{-- ===========================================================
 Nombre de la vista: procedures-requests-show.blade.php
 Descripción: Muestra el detalle completo de una solicitud de trámite (RF13–RF14)
 Fecha de creación: 06/11/2025
 Elaboró: Iker Piza
 Autorizó: Líder Técnico
 Versión: 1.0
 =========================================================== --}}

<x-layouts.app :title="__('Detalle de la solicitud de trámite')">
    <div class="max-w-5xl mx-auto bg-white shadow-lg rounded-2xl p-6 mt-8 font-[Inter]">

        <!-- 🔹 Encabezado -->
        <h2 class="text-2xl font-[Poppins] font-bold text-[#DC6601] mb-4">
            Detalle de la solicitud
        </h2>

        <!-- 🔸 Información general -->
        <div class="mb-6 border-b border-gray-200 pb-4">
            <p><strong>Trabajador:</strong> {{ $requestData->trabajador->name ?? '—' }}</p>
            <p><strong>Trámite:</strong> {{ $requestData->tramite->nombre ?? '—' }}</p>
            <p><strong>Estado:</strong>
                <span
                    class="{{ $requestData->estado === 'Completado'
                        ? 'text-green-600'
                        : ($requestData->estado === 'Rechazado'
                            ? 'text-red-600'
                            : 'text-[#DC6601]') }} font-semibold">
                    {{ $requestData->estado ?? '—' }}
                </span>
            </p>
            <p><strong>Fecha de solicitud:</strong> {{ optional($requestData->created_at)->format('d/m/Y H:i') }}</p>
        </div>

        <!-- 📋 Pasos del trámite -->
        <h3 class="text-xl font-semibold text-[#241178] mb-3">Pasos del trámite</h3>
        <ol class="list-decimal list-inside space-y-4">
            @foreach ($requestData->tramite->pasos as $paso)
                <li class="border border-gray-200 rounded-xl p-4 shadow-sm">
                    <div class="flex justify-between items-start">
                        <div>
                            <h4 class="font-semibold text-[#241178]">{{ $paso->nombre_paso }}</h4>
                            <p class="text-gray-700 text-sm">{{ $paso->descripcion_paso }}</p>
                        </div>

                        @if ($paso->formato_path)
                            <a href="{{ asset('storage/' . $paso->formato_path) }}"
                                target="_blank"
                                class="text-[#DC6601] hover:underline text-sm">
                                Descargar formato
                            </a>
                        @endif
                    </div>

                    <!-- Estado del paso -->
                    <p class="mt-2 text-sm">
                        <strong>Estado:</strong>
                        <span
                            class="{{ $paso->estado === 'Aprobado' ? 'text-green-600' : 'text-[#DC6601]' }}">
                            {{ $paso->estado ?? 'Pendiente' }}
                        </span>
                    </p>
                </li>
            @endforeach
        </ol>

        <!-- 🛠️ Acciones del sindicato -->
        <div class="mt-6 flex gap-3">
            <form method="POST"
                action="{{ route('union.procedures.finalize', [$requestData->id, 'Completado']) }}">
                @csrf
                <button type="submit"
                    class="px-6 py-2 bg-[#DC6601] hover:bg-[#EE0000] text-white font-semibold rounded-lg transition flex items-center gap-2 justify-center">
                    Marcar como completado
                </button>
            </form>

            <form method="POST"
                action="{{ route('union.procedures.finalize', [$requestData->id, 'Rechazado']) }}">
                @csrf
                <button type="submit"
                    class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg">
                    Marcar como rechazado
                </button>
            </form>
        </div>

        <!-- 🔙 Botón regresar -->
        <div class="mt-8">
            <a href="{{ route('union.workers.requests.index') }}"
                class="text-[#241178] hover:underline font-semibold">
                ← Volver a solicitudes
            </a>
        </div>
    </div>
</x-layouts.app>
