<x-layouts.app :title="__('Catálogo de Trámites')">

    <div class="w-full flex flex-col items-center min-h-[80vh] bg-white text-black p-6">

        <h1 class="text-3xl font-[Poppins] font-bold text-[#DC6601] mb-4">
            Catálogo de Trámites
        </h1>

        <form method="GET" action="{{ route('worker.catalog.index') }}"
              class="w-full max-w-4xl mb-8 bg-white border border-[#D9D9D9] rounded-xl shadow-sm p-4 flex flex-wrap gap-4">

            <div class="flex flex-col">
                <label class="text-sm font-semibold text-[#241178]">Buscar</label>
                <input type="text" name="keyword" value="{{ request('keyword') }}"
                       placeholder="Nombre o descripción..."
                       class="border border-[#D9D9D9] rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-[#DC6601] outline-none">
            </div>

            <div class="flex flex-col">
                <label class="text-sm font-semibold text-[#241178]">Mínimo de pasos</label>
                <input type="number" name="steps_min" min="1" value="{{ request('steps_min') }}"
                       class="border border-[#D9D9D9] rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-[#DC6601] outline-none">
            </div>

            <button class="bg-[#241178] hover:bg-[#1A0D5A] text-white font-semibold px-4 py-2 rounded-lg transition h-10 mt-auto">
                Filtrar
            </button>

            <a href="{{ route('worker.catalog.index') }}"
               class="bg-[#DC6601] hover:bg-[#EE0000] text-white font-semibold px-4 py-2 rounded-lg transition h-10 mt-auto">
                Limpiar
            </a>
        </form>

        <div class="w-full max-w-4xl grid gap-6">

            @forelse ($procedures as $proc)

                <div class="border border-[#D9D9D9] bg-white rounded-2xl shadow-sm p-6 transition hover:shadow-lg">

                    <h2 class="text-xl font-[Poppins] font-bold text-[#241178] mb-1">
                        {{ $proc->name }}
                    </h2>

                    <p class="text-gray-600 text-sm">
                        Pasos: <strong>{{ $proc->steps_count }}</strong>
                    </p>

                    <p class="text-black text-sm mt-3">
                        {{ $proc->description ?? 'Sin descripción disponible.' }}
                    </p>

                    <div class="mt-4 flex gap-3">
                        <a href="{{ route('worker.catalog.detail', $proc->id) }}"
                           class="bg-[#241178] hover:bg-[#1A0D5A] text-white px-4 py-2 rounded-lg text-sm font-semibold">
                            Ver requisitos
                        </a>

                        <form action="{{ route('worker.procedures.start', $proc->id) }}" method="POST">
                            @csrf
                            <button class="bg-[#DC6601] hover:bg-[#EE0000] text-white px-4 py-2 rounded-lg text-sm font-semibold">
                                Iniciar trámite
                            </button>
                        </form>
                    </div>

                </div>

            @empty

                <p class="text-center text-gray-500">No hay trámites disponibles.</p>

            @endforelse

        </div>

    </div>

</x-layouts.app>
