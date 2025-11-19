<x-layouts.app :title="__('Detalle del Trámite')">

    <div class="w-full flex flex-col items-center min-h-[80vh] bg-white text-black p-6">

        <h1 class="text-3xl font-[Poppins] font-bold text-[#DC6601] mb-4">
            {{ $procedure->name }}
        </h1>

        <p class="text-gray-700 text-base max-w-3xl text-center mb-6">
            {{ $procedure->description ?? 'Este trámite no tiene descripción registrada.' }}
        </p>

        <div class="w-full max-w-3xl bg-white border border-[#D9D9D9] rounded-xl shadow-sm p-6">

            <h2 class="text-xl font-semibold font-[Poppins] text-[#241178] mb-3">
                Requisitos y Pasos
            </h2>

            <ol class="list-decimal list-inside space-y-3 text-black">

                @foreach ($procedure->steps as $step)
                    <li class="p-3 border-b border-[#D9D9D9]">
                        <strong class="text-[#241178]">{{ $step->step_name }}</strong>
                        <p class="text-sm mt-1">
                            {{ $step->step_description ?? 'Sin descripción del paso.' }}
                        </p>
                        @if ($step->file_path)
                            <a href="{{ asset('storage/' . $step->file_path) }}"
                               class="text-[#DC6601] hover:text-[#EE0000] text-sm font-semibold">
                                Descargar formato / documento
                            </a>
                        @endif
                    </li>
                @endforeach

            </ol>

            <form class="mt-6 text-center"
                  action="{{ route('worker.procedures.start', $procedure->id) }}"
                  method="POST">
                @csrf

                <button class="bg-[#DC6601] hover:bg-[#EE0000] text-white px-6 py-3 rounded-lg text-lg font-semibold">
                    Iniciar trámite
                </button>
            </form>

        </div>

        <a href="{{ route('worker.catalog.index') }}"
           class="mt-6 text-[#241178] hover:underline text-sm font-semibold">
            ← Volver al catálogo
        </a>

    </div>

</x-layouts.app>
