<x-layouts.app :title="__('Detalle del trámite')">

    <div class="w-full flex flex-col items-center min-h-[80vh] bg-white p-6">

        {{-- Encabezado --}}
        <div class="w-full max-w-5xl flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6">
            <div>
                <h1 class="text-3xl font-[Poppins] font-bold text-[#DC6601] mb-1">
                    {{ $procedure->name }}
                </h1>

                <p class="text-[#241178] font-[Inter] text-sm">
                    Trámite creado el {{ $procedure->created_at->format('d/m/Y') }}
                </p>
            </div>

            <div class="flex gap-3 mt-3 sm:mt-0">
                <a href="{{ route('union.procedures.index') }}"
                    class="px-4 py-2 bg-[#241178]/10 hover:bg-[#241178]/20 text-[#241178] font-semibold rounded-lg">
                    Volver
                </a>

                <a href="{{ route('union.procedures.edit', $procedure->id) }}"
                    class="px-4 py-2 bg-[#DC6601] hover:bg-[#EE0000] text-white font-semibold rounded-lg flex items-center gap-2">
                    <x-heroicon-o-pencil-square class="w-5 h-5" />
                    Editar
                </a>
            </div>
        </div>

        {{-- Información general --}}
        <div class="w-full max-w-5xl bg-white border border-[#D9D9D9] rounded-2xl shadow-md p-6 mb-8 font-[Inter]">

            <h2 class="text-xl font-semibold text-[#241178] mb-4">Información general</h2>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">

                <p>
                    <strong class="text-[#272800]">Descripción:</strong>
                    {{ $procedure->description ?? '—' }}
                </p>

                <p>
                    <strong class="text-[#272800]">Total de pasos:</strong>
                    {{ $procedure->steps_count }}
                </p>

                <p>
                    <strong class="text-[#272800]">Fecha de apertura:</strong>
                    {{ $procedure->opening_date ? \Carbon\Carbon::parse($procedure->opening_date)->format('d/m/Y') : '—' }}
                </p>

                <p>
                    <strong class="text-[#272800]">Fecha de cierre:</strong>
                    {{ $procedure->closing_date ? \Carbon\Carbon::parse($procedure->closing_date)->format('d/m/Y') : '—' }}
                </p>

                <p>
                    <strong class="text-[#272800]">Tiempo estimado global:</strong>
                    {{ $procedure->estimated_days ? $procedure->estimated_days . ' días' : '—' }}
                </p>

            </div>

        </div>

        {{-- Pasos del trámite --}}
        <div class="w-full max-w-5xl bg-white border border-[#D9D9D9] rounded-2xl shadow-md p-6 font-[Inter]">

            <h2 class="text-xl font-semibold text-[#241178] mb-4">Pasos definidos</h2>

            @if ($procedure->steps->isEmpty())

                <p class="text-gray-500 text-sm">Este trámite aún no tiene pasos registrados.</p>

            @else

                <div class="space-y-4">

                    @foreach ($procedure->steps->sortBy('order') as $step)
                        <div class="border border-[#D9D9D9]/60 rounded-xl p-4">

                            {{-- Título --}}
                            <h3 class="font-[Poppins] font-semibold text-[#DC6601]">
                                Paso {{ $step->order }}: {{ $step->step_name }}
                            </h3>

                            {{-- Descripción --}}
                            <p class="text-sm text-[#272800] mt-1">
                                {{ $step->step_description ?? 'Sin descripción.' }}
                            </p>

                            {{-- Datos del paso --}}
                            <div class="grid grid-cols-1 sm:grid-cols-2 mt-3 text-sm">

                                <p>
                                    <strong class="text-[#241178]">Tiempo estimado:</strong>
                                    {{ $step->estimated_days ? $step->estimated_days . ' días' : '—' }}
                                </p>

                                <p>
                                    <strong class="text-[#241178]">Flujo alterno:</strong>
                                    @if ($step->next_step_if_fail)
                                        Si falla → ir al paso {{ $step->next_step_if_fail }}
                                    @else
                                        No aplica
                                    @endif
                                </p>

                                <p>
                                    <strong class="text-[#241178]">¿Requiere archivo?</strong>
                                    {{ $step->requires_file ? 'Sí, obligatorio' : 'No obligatorio' }}
                                </p>

                            </div>

                            {{-- Archivo asociado --}}
                            @if ($step->file_path)
                                <div class="mt-3">
                                    <a href="{{ asset('storage/' . $step->file_path) }}" target="_blank"
                                        class="text-[#241178] hover:text-[#DC6601] underline font-semibold text-sm">
                                        Ver formato asociado
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
