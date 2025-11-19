<x-layouts.app :title="__('Convocatorias y Anuncios')">

    <div class="w-full flex flex-col items-center min-h-[80vh] bg-white text-black p-6">

        <h1 class="text-3xl font-[Poppins] font-bold text-[#DC6601] mb-2">
            Convocatorias y Anuncios
        </h1>

        <p class="text-[#241178] font-[Inter] mb-8 text-center">
            Consulta los comunicados y avisos publicados por el sindicato.
        </p>

        {{-- FILTRO DE TIPO --}}
        <form method="GET" action="{{ route('worker.news.index') }}"
            class="w-full max-w-4xl mb-8 bg-white border border-[#D9D9D9] rounded-xl shadow-sm p-4 flex flex-wrap gap-4">

            <div class="flex flex-col">
                <label class="text-sm font-semibold text-[#241178] mb-1">Tipo</label>
                <select name="type"
                    class="border border-[#D9D9D9] rounded-lg px-3 py-2 text-sm bg-white focus:ring-2 focus:ring-[#DC6601] outline-none">
                    <option value="">Todos</option>
                    <option value="convocatoria" {{ request('type') == 'convocatoria' ? 'selected' : '' }}>Convocatorias
                    </option>
                    <option value="comunicado" {{ request('type') == 'comunicado' ? 'selected' : '' }}>Comunicados
                    </option>
                    <option value="evento" {{ request('type') == 'evento' ? 'selected' : '' }}>Eventos</option>
                </select>
            </div>

            <div class="flex flex-col">
                <label class="text-sm font-semibold text-[#241178] mb-1">Buscar</label>
                <input type="text" name="keyword" value="{{ request('keyword') }}" placeholder="Palabra clave..."
                    class="border border-[#D9D9D9] rounded-lg px-3 py-2 text-sm bg-white focus:ring-2 focus:ring-[#DC6601] outline-none">
            </div>

            <button
                class="bg-[#241178] hover:bg-[#1A0D5A] text-white font-semibold px-4 py-2 rounded-lg transition h-10 mt-auto">
                Filtrar
            </button>

            <a href="{{ route('worker.news.index') }}"
                class="bg-[#DC6601] hover:bg-[#EE0000] text-white font-semibold px-4 py-2 rounded-lg transition h-10 mt-auto">
                Limpiar
            </a>


        </form>

        {{-- LISTADO DE NOTICIAS --}}
        <div class="w-full max-w-4xl grid gap-6">

            @forelse ($news_list as $item)
                <div class="border border-[#D9D9D9] bg-white rounded-2xl shadow-sm p-6 transition hover:shadow-lg">

                    <h2 class="text-2xl font-[Poppins] font-bold text-[#241178] mb-1">
                        {{ $item->title }}
                    </h2>

                    <p class="text-gray-600 text-sm mb-3">
                        Publicado el {{ $item->created_at->format('d/m/Y') }}
                        — <span class="capitalize">{{ $item->type }}</span>
                    </p>

                    <p class="text-black font-[Inter] text-base leading-relaxed mb-4">
                        {{ $item->content }}
                    </p>

                    @if ($item->file_path)
                        <a href="{{ asset('storage/' . $item->file_path) }}"
                            class="text-[#DC6601] hover:text-[#EE0000] font-semibold text-sm">
                            Descargar archivo adjunto
                        </a>
                    @endif
                </div>

            @empty
                <p class="text-center text-gray-500">
                    No hay convocatorias ni anuncios publicados actualmente.
                </p>
            @endforelse

        </div>

        <div class="mt-10">
            <a href="{{ route('worker.index') }}"
                class="px-6 py-2 bg-[#241178]/10 text-[#241178] hover:bg-[#241178]/20 font-semibold rounded-lg transition">
                ← Volver al panel
            </a>
        </div>

    </div>

</x-layouts.app>
