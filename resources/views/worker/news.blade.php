<x-layouts.app :title="__('Convocatorias y Anuncios')">

    <div class="w-full flex flex-col items-center min-h-[80vh] bg-white text-black p-6">

        {{-- TÍTULO PRINCIPAL --}}
        <div class="text-center mb-6">
            <h1 class="text-3xl font-[Poppins] font-bold text-[#DE6601]">
                Convocatorias y Anuncios
            </h1>
            <p class="text-[#241178] font-[Inter]">
                Consulta los comunicados, eventos y avisos oficiales publicados por el sindicato.
            </p>
        </div>

        {{-- FILTROS --}}
        <form method="GET" action="{{ route('worker.news.index') }}"
            class="w-full max-w-4xl bg-white border border-zinc-200 rounded-xl shadow-sm p-5 mb-8 grid grid-cols-1 sm:grid-cols-3 gap-4">

            {{-- Tipo --}}
            <div>
                <label class="text-sm font-semibold text-[#241178] mb-1">Tipo de publicación</label>
                <select name="type"
                    class="border border-zinc-300 rounded-lg px-3 py-2 text-sm bg-white focus:ring-2 focus:ring-[#DE6601] outline-none w-full">
                    <option value="">Todos</option>
                    <option value="announcement" {{ request('type') == 'announcement' ? 'selected' : '' }}>Convocatorias</option>
                    <option value="communication" {{ request('type') == 'communication' ? 'selected' : '' }}>Comunicados</option>
                    <option value="event" {{ request('type') == 'event' ? 'selected' : '' }}>Eventos</option>
                </select>
            </div>

            {{-- Buscar --}}
            <div>
                <label class="text-sm font-semibold text-[#241178] mb-1">Buscar por palabra clave</label>
                <input type="text" name="keyword" value="{{ request('keyword') }}"
                    placeholder="Becas, reunión, aviso..."
                    class="border border-zinc-300 rounded-lg px-3 py-2 text-sm bg-white focus:ring-2 focus:ring-[#DE6601] outline-none w-full">
            </div>

            {{-- Acciones --}}
            <div class="flex gap-3 items-end">
                <button
                    class="bg-[#241178] hover:bg-[#1A0D5A] text-white font-semibold px-4 py-2 rounded-lg transition w-full">
                    Filtrar
                </button>

                <a href="{{ route('worker.news.index') }}"
                    class="bg-[#DE6601] hover:bg-[#EE0000] text-white font-semibold px-4 py-2 rounded-lg transition w-full text-center">
                    Limpiar
                </a>
            </div>

        </form>

        {{-- LISTADO DE PUBLICACIONES --}}
        <div class="w-full max-w-4xl grid gap-6">

            @forelse ($news_list as $item)
                <div class="bg-white border border-zinc-200 rounded-2xl shadow-sm p-6 hover:shadow-md transition">

                    {{-- Título --}}
                    <h2 class="text-2xl font-[Poppins] font-bold text-[#241178] mb-1">
                        {{ $item->title }}
                    </h2>

                    {{-- Fecha + tipo --}}
                    <p class="text-gray-600 text-sm mb-3 flex items-center gap-2">
                        <span>
                            Publicado el {{ $item->publication_date ? \Carbon\Carbon::parse($item->publication_date)->format('d/m/Y') : $item->created_at->format('d/m/Y') }}
                        </span>

                        <span class="px-2 py-0.5 rounded-md text-white text-xs font-semibold
                            @if ($item->type === 'announcement') bg-[#DE6601]
                            @elseif($item->type === 'communication') bg-blue-600
                            @elseif($item->type === 'event') bg-green-600
                            @endif">
                            @if ($item->type === 'announcement') Convocatoria
                            @elseif($item->type === 'communication') Comunicado
                            @elseif($item->type === 'event') Evento
                            @endif
                        </span>
                    </p>

                    {{-- Contenido --}}
                    <p class="text-black font-[Inter] text-base leading-relaxed mb-4">
                        {{ $item->content }}
                    </p>

                    {{-- Archivo adjunto --}}
                    @if ($item->file_path)
                        <a href="{{ asset('storage/' . $item->file_path) }}" target="_blank"
                            class="inline-flex items-center gap-2 text-[#DE6601] hover:text-[#EE0000] font-semibold text-sm">
                            <x-heroicon-o-document-arrow-down class="w-5 h-5" />
                            Descargar archivo adjunto
                        </a>
                    @endif

                </div>

            @empty
                <p class="text-center text-gray-500 text-sm">
                    No hay publicaciones disponibles en este momento.
                </p>
            @endforelse

        </div>

        {{-- BOTÓN VOLVER --}}
        <div class="mt-10">
            <a href="{{ route('worker.index') }}"
                class="px-6 py-2 bg-[#241178]/10 text-[#241178] hover:bg-[#241178]/20 font-semibold rounded-lg transition">
                ← Volver al panel
            </a>
        </div>

    </div>

</x-layouts.app>
