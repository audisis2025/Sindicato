{{-- 
* Nombre de la vista           : show.blade.php
* Descripción de la vista      : Detalle del trámite disponible para el trabajador, incluyendo pasos y descarga de formatos.
* Fecha de creación            : 13/01/2026
* Elaboró                      : Iker Piza
* Fecha de liberación          : 13/01/2026
* Autorizó                     : Líder Técnico
* Versión                      : 1.0
* Fecha de mantenimiento       : N/A
* Folio de mantenimiento       : N/A
* Tipo de mantenimiento        : N/A
* Descripción del mantenimiento: Implementación inicial conforme a Manual PRO-Laravel (Regresar, botones y estilos).
* Responsable                  : Iker Piza
* Revisor                      : QA SINDISOFT
--}}

<x-layouts.app :title="__('Detalle del trámite')">

    <div class="w-full flex flex-col items-center min-h-[80vh] bg-white text-black p-6">

        @php
            $activeStates = ['initiated', 'in_progress', 'pending_union', 'pending_worker'];

            $alreadyStarted = \App\Models\ProcedureRequest::where('user_id', Auth::id())
                ->where('procedure_id', $procedure->id)
                ->whereIn('status', $activeStates)
                ->exists();
        @endphp

        <div class="w-full max-w-3xl flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6">
            <div>
                <h1 class="text-3xl font-bold text-[#DE6601] mb-1">
                    {{ $procedure->name }}
                </h1>

                <p class="text-[#272800] text-base max-w-3xl">
                    {{ $procedure->description ?? 'Este trámite no tiene descripción registrada.' }}
                </p>
            </div>

            <flux:button icon="arrow-long-left" icon-variant="outline" variant="ghost"
                :href="route('worker.catalog.index')" class="mt-3 sm:mt-0">
                Regresar
            </flux:button>
        </div>

        <div class="w-full max-w-3xl bg-white border border-zinc-200 rounded-xl shadow-sm p-6">

            <h2 class="text-xl font-semibold text-[#241178] mb-3">
                Requisitos y pasos
            </h2>

            <ol class="list-decimal list-inside space-y-3 text-black">
                @foreach ($procedure->steps as $step)
                    <li class="p-3 border-b border-zinc-200">
                        <strong class="text-[#241178]">{{ $step->step_name }}</strong>

                        <p class="text-sm mt-1">
                            {{ $step->step_description ?? 'Sin descripción del paso.' }}
                        </p>

                        @if ($step->file_path)
                            <flux:button icon="arrow-down-tray" variant="filled" size="xs"
                                :href="asset('storage/' . $step->file_path)"
                                class="mt-2 !bg-gray-500 hover:!bg-gray-600 !text-white">
                                Descargar archivo
                            </flux:button>
                        @endif
                    </li>
                @endforeach
            </ol>

            @unless ($alreadyStarted)
                <form class="mt-6 text-center" action="{{ route('worker.procedures.start', $procedure->id) }}"
                    method="POST">
                    @csrf

                    <flux:button type="submit" icon="plus" icon-variant="outline" variant="primary"
                        class="px-6 py-3 !bg-blue-600 hover:!bg-blue-700 !text-white">
                        Iniciar trámite
                    </flux:button>
                </form>
            @else
                <div class="mt-6 text-center">
                    <span class="text-[#241178] font-semibold text-sm">
                        Ya tienes este trámite en proceso.
                    </span>

                    <flux:button size="sm" icon="eye" variant="filled" :href="route('worker.index')"
                        class="mt-3 !bg-gray-500 hover:!bg-gray-600 !text-white">
                        Ver mis trámites
                    </flux:button>
                </div>
            @endunless

        </div>

    </div>

</x-layouts.app>
