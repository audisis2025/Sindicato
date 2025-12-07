<x-layouts.app :title="__('Detalle del Trámite')">

    <div class="w-full flex flex-col items-center min-h-[80vh] bg-white text-black p-6">

        @php
            $activeStates = ['initiated','in_progress','pending_union','pending_worker'];

            $alreadyStarted = \App\Models\ProcedureRequest::where('user_id', Auth::id())
                ->where('procedure_id', $procedure->id)
                ->whereIn('status', $activeStates)
                ->exists();
        @endphp

        <div class="w-full max-w-3xl flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6">
            <div>
                <h1 class="text-3xl font-[Poppins] font-bold text-[#DE6601] mb-1">
                    {{ $procedure->name }}
                </h1>

                <p class="text-gray-700 text-base max-w-3xl">
                    {{ $procedure->description ?? 'Este trámite no tiene descripción registrada.' }}
                </p>
            </div>

            <flux:button
                icon="arrow-long-left"
                icon-variant="outline"
                variant="ghost"
                :href="route('worker.catalog.index')"
                class="px-4 py-2 !bg-transparent hover:!bg-zinc-100 !text-[#241178] font-semibold rounded-lg flex items-center gap-2 mt-3 sm:mt-0"
            >
                Volver
            </flux:button>
        </div>

        <div class="w-full max-w-3xl bg-white border border-zinc-200 rounded-xl shadow-sm p-6">

            <h2 class="text-xl font-semibold font-[Poppins] text-[#241178] mb-3">
                Requisitos y Pasos
            </h2>

            <ol class="list-decimal list-inside space-y-3 text-black">
                @foreach ($procedure->steps as $step)
                    <li class="p-3 border-b border-zinc-200">
                        <strong class="text-[#241178]">{{ $step->step_name }}</strong>

                        <p class="text-sm mt-1">
                            {{ $step->step_description ?? 'Sin descripción del paso.' }}
                        </p>

                        @if ($step->file_path)
                            <flux:button
                                icon="arrow-down-tray"
                                icon-variant="outline"
                                variant="filled"
                                size="xs"
                                :href="asset('storage/' . $step->file_path)"
                                class="mt-2"
                            >
                                Descargar archivo
                            </flux:button>
                        @endif
                    </li>
                @endforeach
            </ol>

            {{-- BOTÓN DE INICIO (solo si NO está iniciado) --}}
            @unless ($alreadyStarted)
                <form class="mt-6 text-center"
                      action="{{ route('worker.procedures.start', $procedure->id) }}"
                      method="POST">
                    @csrf

                    <flux:button
                        type="submit"
                        icon="plus"
                        icon-variant="outline"
                        variant="primary"
                        class="px-6 py-3 !bg-blue-600 hover:!bg-blue-700 !text-white text-lg"
                    >
                        Iniciar trámite
                    </flux:button>
                </form>
            @else
                <div class="mt-6 text-center">
                    <span class="text-[#241178] font-semibold text-sm">
                        Ya tienes este trámite en proceso.
                    </span>

                    <flux:button
                        size="sm"
                        icon="eye"
                        icon-variant="outline"
                        variant="filled"
                        :href="route('worker.index')"
                        class="mt-3"
                    >
                        Ver mis trámites
                    </flux:button>
                </div>
            @endunless

        </div>

    </div>

</x-layouts.app>
