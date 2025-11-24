{{-- ===========================================================
 Vista: union/requests/show.blade.php
 Adaptada a RF-04 (Estados completos)
=========================================================== --}}

<x-layouts.app :title="__('Detalle de la solicitud de trámite')">

    <div class="max-w-5xl mx-auto bg-white shadow-lg rounded-2xl p-6 mt-8 font-[Inter]">

        <h2 class="text-2xl font-[Poppins] font-bold text-[#DC6601] mb-4">
            Detalle de la solicitud
        </h2>

        @php
            $mapping = [
                'started'          => ['Iniciado', 'text-blue-600'],
                'pending_worker'   => ['Pendiente de acción del trabajador', 'text-amber-600'],
                'pending_union'    => ['Pendiente de acción del sindicato', 'text-purple-600'],
                'in_progress'      => ['En proceso', 'text-sky-600'],
                'completed'        => ['Finalizado', 'text-green-600'],
                'cancelled'        => ['Cancelado', 'text-gray-600'],
                'rejected'         => ['Rechazado', 'text-red-600'],
                'pending'          => ['Pendiente', 'text-amber-600'],
            ];

            [$estadoTexto, $color] = $mapping[$request->status] ?? ['Desconocido', 'text-gray-500'];
        @endphp

        <div class="mb-6 border-b border-gray-200 pb-4">
            <p><strong>Trabajador:</strong> {{ $request->user->name }}</p>
            <p><strong>Trámite:</strong> {{ $request->procedure->name }}</p>

            <p>
                <strong>Estado:</strong>
                <span class="{{ $color }} font-semibold">
                    {{ $estadoTexto }}
                </span>
            </p>

            <p><strong>Fecha de solicitud:</strong> {{ $request->created_at->format('d/m/Y H:i') }}</p>
        </div>

        <h3 class="text-xl font-semibold text-[#241178] mb-3">Pasos del trámite</h3>

        <ol class="list-decimal list-inside space-y-4">
            @foreach ($request->procedure->steps as $step)

                @php $isCompleted = $request->current_step > $step->order; @endphp

                <li class="border border-gray-200 rounded-xl p-4 shadow-sm">

                    <div class="flex justify-between">
                        <div>
                            <h4 class="font-semibold text-[#241178]">{{ $step->step_name }}</h4>
                            <p class="text-gray-700 text-sm">{{ $step->step_description }}</p>
                        </div>

                        @if ($step->file_path)
                            <a href="{{ asset('storage/' . $step->file_path) }}"
                               target="_blank"
                               class="text-[#DC6601] hover:underline text-sm">
                                Descargar formato
                            </a>
                        @endif
                    </div>

                    <p class="mt-2 text-sm">
                        <strong>Estado:</strong>
                        <span class="{{ $isCompleted ? 'text-green-600' : 'text-[#DC6601]' }}">
                            {{ $isCompleted ? 'Completado' : 'Pendiente' }}
                        </span>
                    </p>
                </li>

            @endforeach
        </ol>

        <div class="mt-6 flex gap-3">

            <form method="POST" action="{{ route('union.procedures.finalize', [$request->id, 'completed']) }}">
                @csrf
                <button type="submit"
                    class="px-6 py-2 bg-[#DC6601] hover:bg-[#EE0000] text-white font-semibold rounded-lg">
                    Marcar como completado
                </button>
            </form>

            <form method="POST" action="{{ route('union.procedures.finalize', [$request->id, 'rejected']) }}">
                @csrf
                <button type="submit"
                    class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg">
                    Marcar como rechazado
                </button>
            </form>
        </div>

        <div class="mt-8">
            <a href="{{ route('union.workers.requests.index') }}"
                class="text-[#241178] hover:underline font-semibold">
                ← Volver a solicitudes
            </a>
        </div>

    </div>

</x-layouts.app>
