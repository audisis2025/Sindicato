{{-- ===========================================================
 Nombre de la clase: procedures-show.blade.php
 Descripción: Vista detallada de un trámite creado por el Sindicato.
 Fecha: 03/11/2025 | Versión: 1.0
 Tipo de mantenimiento: Creación.
 Descripción del mantenimiento: Muestra la información completa del trámite,
 incluyendo pasos, flujos alternos y formatos asociados.
 Responsable: Iker Piza
 Revisor: QA SINDISOFT
=========================================================== --}}

<x-layouts.app :title="__('Detalle del trámite')">
    <div class="w-full flex flex-col items-center justify-start min-h-[80vh] bg-[#FFFFFF] text-[#000000] p-6">

        <!-- 🔸 Encabezado -->
        <div class="w-full max-w-5xl flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6">
            <div>
                <h1 class="text-3xl font-[Poppins] font-bold text-[#DC6601] mb-1">
                    {{ $procedure->nombre }}
                </h1>
                <p class="text-[#241178] font-[Inter] text-sm">
                    Trámite creado el {{ $procedure->created_at->format('d/m/Y') }}
                </p>
            </div>

            <div class="flex gap-3 mt-3 sm:mt-0">
                <a href="{{ route('union.procedures.index') }}"
                    class="px-4 py-2 bg-[#241178]/10 hover:bg-[#241178]/20 text-[#241178] font-semibold rounded-lg transition">
                    ⬅️ Volver
                </a>
                <a href="{{ route('union.procedures.edit', $procedure->id) }}"
                    class="px-4 py-2 bg-[#DC6601] hover:bg-[#EE0000] text-white font-semibold rounded-lg transition flex items-center gap-2">
                    <x-heroicon-o-pencil-square class="w-5 h-5" />
                    Editar
                </a>
            </div>
        </div>

        <!-- 📋 Información general del trámite -->
        <div class="w-full max-w-5xl bg-white border border-[#D9D9D9] rounded-2xl shadow-md p-6 mb-8 font-[Inter]">
            <h2 class="text-xl font-semibold text-[#241178] mb-4">Información general</h2>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
                <p><strong class="text-[#272800]">Descripción:</strong> {{ $procedure->descripcion ?? '—' }}</p>
                <p><strong class="text-[#272800]">Total de pasos:</strong> {{ $procedure->numero_pasos }}</p>
                <p><strong class="text-[#272800]">Fecha de apertura:</strong>
                    {{ $procedure->fecha_apertura ? date('d/m/Y', strtotime($procedure->fecha_apertura)) : '—' }}</p>
                <p><strong class="text-[#272800]">Fecha de cierre:</strong>
                    {{ $procedure->fecha_cierre ? date('d/m/Y', strtotime($procedure->fecha_cierre)) : '—' }}</p>
                <p><strong class="text-[#272800]">Tiempo estimado global:</strong>
                    {{ $procedure->tiempo_estimado_dias ? $procedure->tiempo_estimado_dias . ' días' : '—' }}</p>
            </div>
        </div>

        <!-- 🔹 Listado de pasos -->
        <div class="w-full max-w-5xl bg-white border border-[#D9D9D9] rounded-2xl shadow-md p-6 font-[Inter]">
            <h2 class="text-xl font-semibold text-[#241178] mb-4">Pasos definidos</h2>

            @if ($procedure->pasos->isEmpty())
                <p class="text-gray-500 text-sm">Este trámite aún no tiene pasos registrados.</p>
            @else
                <div class="space-y-4">
                    @foreach ($procedure->pasos as $paso)
                        <div class="border border-[#D9D9D9]/60 rounded-xl p-4">
                            <h3 class="font-[Poppins] font-semibold text-[#DC6601]">
                                Paso {{ $paso->orden }}: {{ $paso->nombre_paso }}
                            </h3>
                            <p class="text-sm text-[#272800] mt-1">
                                {{ $paso->descripcion_paso ?? 'Sin descripción.' }}
                            </p>

                            <div class="grid grid-cols-1 sm:grid-cols-2 mt-3 text-sm">
                                <p><strong class="text-[#241178]">Tiempo estimado:</strong>
                                    {{ $paso->tiempo_estimado_dias ? $paso->tiempo_estimado_dias . ' días' : '—' }}
                                </p>
                                <p><strong class="text-[#241178]">Flujo alterno:</strong>
                                    @if ($paso->next_step_if_fail)
                                        Si falla → ir al paso {{ $paso->next_step_if_fail }}
                                    @else
                                        No aplica
                                    @endif
                                </p>
                            </div>

                            @if ($paso->formato_path)
                                <div class="mt-3">
                                    <a href="{{ asset('storage/' . $paso->formato_path) }}" target="_blank"
                                        class="text-[#241178] hover:text-[#DC6601] font-semibold text-sm underline">
                                        📄 Ver formato asociado
                                    </a>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</x-layouts.app>
