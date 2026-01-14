{{-- 
* Nombre de la vista           : news.blade.php
* Descripción de la vista      : Vista para consulta de convocatorias, comunicados y eventos visibles para el trabajador.
* Fecha de creación            : 12/01/2026
* Elaboró                      : Iker Piza
* Fecha de liberación          : 12/01/2026
* Autorizó                     : Líder Técnico
* Versión                      : 1.1
* Fecha de mantenimiento       : 13/01/2026
* Folio de mantenimiento       : N/A
* Tipo de mantenimiento        : Correctivo y perfectivo
* Descripción del mantenimiento: Homologación según Manual PRO-Laravel (Regresar, Buscar/Actualizar, botones Flux y estilos).
* Responsable                  : Iker Piza
* Revisor                      : QA SINDISOFT
--}}

<x-layouts.app :title="__('Convocatorias y anuncios')">

    <div class="p-6 w-full max-w-6xl mx-auto space-y-8">

        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
            <div>
                <h1 class="text-3xl font-bold text-[#DE6601]">
                    Convocatorias y anuncios
                </h1>

                <p class="text-[#272800]">
                    Consulta los comunicados, eventos y avisos oficiales publicados por el sindicato.
                </p>
            </div>

            <flux:button icon="arrow-long-left" icon-variant="outline" variant="ghost" :href="route('worker.index')">
                Regresar
            </flux:button>
        </div>

        <form method="GET" action="{{ route('worker.news.index') }}" class="flex flex-wrap gap-4 items-end">

            <div class="flex flex-col">
                <label class="text-sm font-semibold text-[#272800]">Tipo de publicación</label>
                <select name="type"
                    class="border border-zinc-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-600 outline-none">
                    <option value="">Todos</option>
                    <option value="announcement" {{ request('type') == 'announcement' ? 'selected' : '' }}>Convocatoria
                    </option>
                    <option value="communication" {{ request('type') == 'communication' ? 'selected' : '' }}>Comunicado
                    </option>
                    <option value="event" {{ request('type') == 'event' ? 'selected' : '' }}>Evento</option>
                </select>
            </div>

            <div class="flex flex-col">
                <label class="text-sm font-semibold text-[#272800]">Buscar</label>
                <input type="text" name="keyword" value="{{ request('keyword') }}"
                    placeholder="Becas, reunión, aviso..." maxlength="120"
                    class="border border-zinc-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-600 outline-none">
            </div>

            <flux:button icon="magnifying-glass" variant="primary" type="submit"
                class="h-10 px-4 !bg-gray-500 hover:!bg-gray-600 !text-white">
                Buscar
            </flux:button>

            <flux:button icon="arrow-path" variant="primary" :href="route('worker.news.index')"
                class="h-10 px-4 !bg-green-600 hover:!bg-green-700 !text-white">
                Actualizar
            </flux:button>

        </form>

        <div class="grid gap-6">

            @forelse ($news_list as $news_item)
                <div class="bg-white border border-zinc-200 rounded-2xl shadow-sm p-6 hover:shadow-md transition">

                    <h2 class="text-2xl font-bold text-[#241178] mb-1">
                        {{ $news_item->title }}
                    </h2>

                    <p class="text-sm text-zinc-600 mb-3">
                        Publicado el
                        {{ $news_item->publication_date ? \Carbon\Carbon::parse($news_item->publication_date)->format('d/m/Y') : $news_item->created_at->format('d/m/Y') }}
                        @if ($news_item->type === 'announcement')
                            <span class="ml-2 text-[#DE6601] font-semibold">Convocatoria</span>
                        @elseif ($news_item->type === 'communication')
                            <span class="ml-2 text-blue-600 font-semibold">Comunicado</span>
                        @elseif ($news_item->type === 'event')
                            <span class="ml-2 text-green-600 font-semibold">Evento</span>
                        @endif
                    </p>

                    <p class="text-black text-base leading-relaxed mb-4">
                        {{ $news_item->content }}
                    </p>

                    @if ($news_item->file_path)
                        <flux:button icon="arrow-down-tray" variant="filled"
                            :href="asset('storage/' . $news_item->file_path)"
                            class="!bg-gray-500 hover:!bg-gray-600 !text-white">
                            Descargar
                        </flux:button>
                    @endif

                </div>

            @empty

                <p class="text-center text-gray-500 text-sm">
                    No hay publicaciones disponibles en este momento.
                </p>
            @endforelse

        </div>

    </div>

</x-layouts.app>
