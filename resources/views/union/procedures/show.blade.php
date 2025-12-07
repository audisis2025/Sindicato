{{-- 
* Nombre de la vista           : show.blade.php
* Descripción de la vista      : Detalle del trámite creado por el Sindicato, incluyendo pasos configurados.
* Fecha de creación            : 03/11/2025
* Elaboró                      : Iker Piza
* Fecha de liberación          : 03/11/2025
* Autorizó                     : Líder Técnico
* Versión                      : 1.1
* Fecha de mantenimiento       : 27/11/2025
* Folio de mantenimiento       : N/A
* Tipo de mantenimiento        : Correctivo y perfectivo
* Descripción del mantenimiento: Homologación de estructura, tipografía e iconografía según Manual PRO-Laravel V3.4.
* Responsable                  : Iker Piza
* Revisor                      : QA SINDISOFT
--}}

<x-layouts.app :title="__('Detalle del trámite')">

    <div class="w-full flex flex-col items-center min-h-[80vh] bg-white p-6">

        <div class="w-full max-w-5xl flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6">
            <div>
                <h1 class="text-3xl font-bold text-[#DE6601] mb-1">
                    {{ $procedure->name }}
                </h1>

                <p class="text-[#241178] text-sm">
                    Trámite creado el {{ $procedure->created_at->format('d/m/Y') }}
                </p>
            </div>

            <div class="flex gap-3 mt-3 sm:mt-0">
                <flux:button
                    icon-variant="outline"
                    variant="ghost"
                    :href="route('union.procedures.index')"
                    class="px-4 py-2 !bg-transparent hover:!bg-zinc-100 !text-[#241178] font-semibold rounded-lg flex items-center gap-2"
                >
                    <x-heroicon-o-arrow-long-left class="w-5 h-5" />
                    <span>Volver</span>
                </flux:button>

                <flux:button
                    icon="pencil-square"
                    icon-variant="outline"
                    variant="primary"
                    :href="route('union.procedures.edit', $procedure->id)"
                    class="px-4 py-2 !bg-gray-500 hover:!bg-gray-600 !text-white font-semibold rounded-lg"
                >
                    Editar
                </flux:button>
            </div>
        </div>

        <div class="w-full max-w-5xl bg-white border border-[#D9D9D9] rounded-2xl shadow-md p-6 mb-8">

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

        <div class="w-full max-w-5xl bg-white border border-[#D9D9D9] rounded-2xl shadow-md p-6">

            <h2 class="text-xl font-semibold text-[#241178] mb-4">Pasos definidos</h2>

            @if ($procedure->steps->isEmpty())
                <p class="text-gray-500 text-sm">Este trámite aún no tiene pasos registrados.</p>
            @else
                <div class="space-y-4">
                    @foreach ($procedure->steps->sortBy('order') as $step)
                        <div class="border border-[#D9D9D9]/60 rounded-xl p-4">

                            <h3 class="font-semibold text-[#DE6601]">
                                Paso {{ $step->order }}: {{ $step->step_name }}
                            </h3>

                            <p class="text-sm text-[#272800] mt-1">
                                {{ $step->step_description ?? 'Sin descripción.' }}
                            </p>

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

                            @if ($step->file_path)
                                <div class="mt-3">
                                    <a
                                        href="{{ asset('storage/' . $step->file_path) }}"
                                        target="_blank"
                                        class="text-[#241178] hover:text-[#DE6601] underline font-semibold text-sm"
                                    >
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
