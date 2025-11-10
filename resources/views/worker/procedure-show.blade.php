{{-- ===========================================================
 Nombre de la vista: procedure-show.blade.php
 Descripción: Muestra los pasos del trámite actual y permite
 realizar cada paso en orden secuencial.
 Fecha de creación: 07/11/2025
 Elaboró: Iker Piza
 Versión: 1.3
 Tipo de mantenimiento: Mejora funcional (validación de secuencia y carga condicional).
 =========================================================== --}}

<x-layouts.app :title="__('Detalle del Trámite')">
    <div class="max-w-4xl mx-auto bg-white shadow-lg rounded-xl p-6 mt-8">

        <!-- 🧾 Encabezado -->
        <h2 class="text-2xl font-bold text-[#241178] font-[Poppins] mb-4">
            {{ $solicitud->tramite->nombre }}
        </h2>
        <p class="text-gray-700 mb-6">{{ $solicitud->tramite->descripcion }}</p>

        <!-- 👣 Pasos -->
        <h3 class="text-xl font-semibold text-[#DC6601] font-[Inter] mb-3">Pasos del trámite</h3>

        <ol class="list-decimal list-inside space-y-5">
            @foreach ($solicitud->tramite->pasos as $index => $paso)
                @php
                    $numeroPaso = $index + 1;
                    $bloqueado = $numeroPaso > $solicitud->paso_actual;
                    $esActual = $numeroPaso === $solicitud->paso_actual;
                @endphp

                <li class="border border-gray-200 rounded-lg p-4 shadow-sm {{ $bloqueado ? 'opacity-60' : '' }}">
                    <div class="flex justify-between items-start">
                        <div>
                            <h4 class="text-[#241178] font-semibold">
                                Paso {{ $numeroPaso }}: {{ $paso->nombre_paso }}
                                @if ($esActual)
                                    <span class="text-[#DC6601] text-sm font-semibold">(Actual)</span>
                                @elseif($bloqueado)
                                    <span class="text-gray-500 text-sm">(Bloqueado)</span>
                                @else
                                    <span class="text-green-600 text-sm">(Completado)</span>
                                @endif
                            </h4>
                            <p class="text-gray-600 text-sm">{{ $paso->descripcion_paso }}</p>
                        </div>

                        @if ($paso->formato_path)
                            <a href="{{ asset('storage/' . $paso->formato_path) }}"
                                class="text-[#DC6601] hover:underline text-sm font-semibold">
                                Descargar formato
                            </a>
                        @endif
                    </div>

                    <!-- 📤 Subida de archivo (solo si el paso lo pide y está habilitado) -->
                    @if ($paso->formato_path && !$bloqueado)
                        <form action="{{ route('worker.procedures.upload', [$solicitud->id, $paso->id]) }}"
                            method="POST" enctype="multipart/form-data" class="mt-3 border-t pt-3">
                            @csrf
                            <label class="text-sm font-medium text-gray-700">Subir archivo completado:</label>
                            <input type="file" name="archivo"
                                class="block w-full text-sm border rounded-md mt-1 p-2">
                            <button type="submit"
                                class="mt-2 bg-[#241178] hover:bg-[#1e0f6b] text-white px-4 py-1 rounded-lg">
                                Subir archivo
                            </button>
                        </form>
                    @elseif($paso->formato_path && $bloqueado)
                        <p class="text-gray-500 text-sm italic mt-3 border-t pt-3">
                            Este paso requiere subir un archivo, pero primero completa el paso anterior.
                        </p>
                    @endif

                    <!-- ✅ Botón "Realizar paso" -->
                    @if (!$bloqueado)
                        <form action="{{ route('worker.procedures.complete-step', [$solicitud->id, $paso->id]) }}"
                            method="POST" class="mt-4">
                            @csrf
                            <button type="submit"
                                class="bg-[#DC6601] hover:bg-[#EE0000] text-white px-4 py-2 rounded-lg font-semibold transition">
                                Marcar paso como realizado
                            </button>
                        </form>
                    @else
                        <p class="text-gray-400 text-sm mt-2">
                            Este paso se habilitará cuando completes el paso anterior.
                        </p>
                    @endif
                </li>
            @endforeach
        </ol>

    </div>
</x-layouts.app>
