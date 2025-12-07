<x-layouts.app :title="__('Detalle del trámite')">

    <div class="w-full max-w-5xl mx-auto p-6 flex flex-col gap-6">

        <div class="flex items-center justify-between">
            <h1 class="text-3xl font-bold text-[#DE6601]">
                Detalle del trámite
            </h1>

            <flux:button
                icon="arrow-long-left"
                icon-variant="outline"
                variant="ghost"
                :href="route('worker.dashboard')"
                class="px-4 py-2 !bg-transparent hover:!bg-zinc-100 !text-[#241178] font-semibold rounded-lg"
            >
                Volver
            </flux:button>
        </div>

        @php
            $mapping = [
                'initiated'        => ['Iniciado', 'text-blue-600'],
                'in_progress'      => ['En proceso', 'text-[#DC6601]'],
                'pending_worker'   => ['Pendiente de acción del trabajador', 'text-amber-600'],
                'pending_union'    => ['Pendiente del sindicato', 'text-purple-600'],
                'completed'        => ['Finalizado', 'text-green-600'],
                'cancelled'        => ['Cancelado', 'text-gray-600'],
                'rejected'         => ['Rechazado', 'text-red-600'],
            ];

            [$estadoTexto, $color] = $mapping[$request->status] ?? ['Desconocido', 'text-gray-500'];
        @endphp

        <div class="bg-white border border-[#D9D9D9] rounded-xl shadow-sm p-6 font-[Inter]">

            <div class="border-b border-gray-200 pb-4 mb-4 space-y-1">
                <p class="text-sm text-black">
                    <span class="font-semibold">Trámite:</span>
                    {{ $request->procedure->name }}
                </p>
                <p class="text-sm text-black">
                    <span class="font-semibold">Estado:</span>
                    <span class="{{ $color }} font-semibold">{{ $estadoTexto }}</span>
                </p>
                <p class="text-sm text-black">
                    <span class="font-semibold">Fecha de inicio:</span>
                    {{ optional($request->created_at)->format('d/m/Y H:i') }}
                </p>
            </div>

            <h2 class="text-xl font-semibold text-[#241178] mb-3">
                Pasos del trámite
            </h2>

            <ol class="space-y-4">
                @foreach ($request->procedure->steps as $step)
                    @php
                        $isCurrent = $request->current_step == $step->order;
                        $isCompleted = $request->current_step > $step->order;
                    @endphp

                    <li class="border border-gray-200 rounded-xl p-4 shadow-sm bg-white">

                        <div class="flex flex-col gap-3">

                            <div class="flex items-start justify-between">
                                <div>
                                    <h3 class="font-semibold text-[#241178]">
                                        {{ $step->step_name }}
                                    </h3>
                                    <p class="text-gray-700 text-sm mt-1">
                                        {{ $step->step_description }}
                                    </p>
                                </div>

                                @if ($step->file_path)
                                    <flux:button
                                        size="xs"
                                        icon="arrow-down-tray"
                                        variant="filled"
                                        :href="asset('storage/' . $step->file_path)"
                                        class="!bg-blue-600 hover:!bg-blue-700 !text-white"
                                    >
                                        Descargar formato
                                    </flux:button>
                                @endif
                            </div>

                            <p class="text-sm">
                                <span class="font-semibold">Estado:</span>
                                <span class="{{ $isCompleted ? 'text-green-600' : ($isCurrent ? 'text-[#DC6601]' : 'text-gray-600') }} font-semibold">
                                    {{ $isCompleted ? 'Completado' : ($isCurrent ? 'En revisión' : 'Pendiente') }}
                                </span>
                            </p>

                            @if ($isCurrent && $request->status === 'pending_worker')
                                <div class="mt-3 p-4 border border-amber-300 bg-amber-50 rounded-lg">
                                    <p class="font-semibold text-amber-700 mb-2">
                                        Corrección requerida
                                    </p>

                                    <form method="POST"
                                          action="{{ route('worker.requests.correct-step', [$request->id, $step->order]) }}"
                                          enctype="multipart/form-data"
                                          class="space-y-4">

                                        @csrf

                                        @if ($step->requires_file)
                                            <div>
                                                <label class="block text-sm font-semibold text-black mb-1">
                                                    Subir archivo corregido
                                                </label>
                                                <input type="file"
                                                       name="file"
                                                       class="w-full border border-zinc-300 rounded-lg px-3 py-2 text-sm">
                                            </div>
                                        @endif

                                        <div>
                                            <label class="block text-sm font-semibold text-black mb-1">
                                                Observaciones o correcciones
                                            </label>
                                            <textarea
                                                name="comments"
                                                rows="3"
                                                class="w-full border border-zinc-300 rounded-lg px-3 py-2 text-sm"
                                                placeholder="Describe la corrección realizada"></textarea>
                                        </div>

                                        <flux:button
                                            icon="check"
                                            icon-variant="outline"
                                            variant="primary"
                                            type="submit"
                                            class="!bg-blue-600 hover:!bg-blue-700 !text-white font-semibold rounded-lg">
                                            Enviar corrección
                                        </flux:button>
                                    </form>
                                </div>
                            @endif
                        </div>

                    </li>
                @endforeach
            </ol>

        </div>

    </div>

</x-layouts.app>
