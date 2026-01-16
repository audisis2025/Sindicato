{{-- 
* Nombre de la vista          : index.blade.php
* Descripción de la vista     : Vista del catálogo de trámites para el trabajador, donde se muestran los trámites
*                               disponibles con opciones de búsqueda y filtros, así como el listado de publicaciones
*                               del sindicato para consulta general.
* Fecha de creación           : 01/12/2025
* Elaboró                     : Iker Piza
* Fecha de liberación         : 14/01/2026
* Autorizó                    : Salvador Monroy
* Versión                     : 1.0
* Fecha de mantenimiento      :
* Folio de mantenimiento      :
* Tipo de mantenimiento       :
* Descripción del mantenimiento:
* Responsable                 :
* Revisor                     :
--}}

<x-layouts.app :title="__('Catálogo de Trámites')">

    <div class="p-6 w-full max-w-6xl mx-auto space-y-8">

        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
            <h1 class="text-3xl font-bold text-[#DE6601]">
                Catálogo de Trámites
            </h1>
        </div>

        <form method="GET" action="{{ route('worker.catalog.index') }}" class="flex flex-wrap gap-4 items-end">

            <div class="flex flex-col">
                <label class="text-sm font-semibold text-[#272800]">Buscar</label>
                <input type="text" name="keyword" value="{{ request('keyword') }}"
                    placeholder="Nombre o descripción..." maxlength="120"
                    class="border border-zinc-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-600 outline-none">
            </div>

            <div class="flex flex-col">
                <label class="text-sm font-semibold text-[#272800]">Mínimo de pasos</label>
                <input type="number" name="steps_min" min="1" value="{{ request('steps_min') }}"
                    class="border border-zinc-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-600 outline-none">
            </div>

            <div class="flex flex-col">
                <label class="text-sm font-semibold text-[#272800]">Categoría</label>
                <select name="type"
                    class="border border-zinc-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-600 outline-none">
                    <option value="">Todas</option>
                    <option value="announcement" {{ request('type') == 'announcement' ? 'selected' : '' }}>Convocatoria
                    </option>
                    <option value="communication" {{ request('type') == 'communication' ? 'selected' : '' }}>Comunicado
                    </option>
                    <option value="event" {{ request('type') == 'event' ? 'selected' : '' }}>Evento</option>
                </select>
            </div>

            <div class="flex flex-col">
                <label class="text-sm font-semibold text-[#272800]">Desde</label>
                <input type="date" name="from_date" value="{{ request('from_date') }}"
                    class="border border-zinc-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-600 outline-none">
            </div>

            <div class="flex flex-col">
                <label class="text-sm font-semibold text-[#272800]">Hasta (opcional)</label>
                <input type="date" name="to_date" value="{{ request('to_date') }}"
                    class="border border-zinc-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-600 outline-none">
            </div>

            <flux:button icon="magnifying-glass" variant="primary" type="submit"
                class="h-10 px-4 !bg-gray-500 hover:!bg-gray-600 !text-white">
                Buscar
            </flux:button>

            <flux:button icon="arrow-path" variant="primary" :href="route('worker.catalog.index')"
                class="h-10 px-4 !bg-green-600 hover:!bg-green-700 !text-white">
                Actualizar
            </flux:button>

        </form>

        <h2 class="text-2xl font-bold text-[#241178] mt-4">
            Trámites Disponibles
        </h2>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">

            @forelse ($procedures as $proc)
                <div class="border border-zinc-200 bg-white rounded-2xl shadow-sm p-6 hover:shadow-md transition">

                    <h2 class="text-xl font-semibold text-[#241178] mb-1 truncate">
                        {{ $proc->name }}
                    </h2>

                    <p class="text-sm text-zinc-600 line-clamp-3">
                        {{ $proc->description ?? 'Sin descripción disponible.' }}
                    </p>

                    <p class="mt-3 text-sm text-[#272800]">
                        <strong>Pasos:</strong> {{ $proc->steps_count }}
                    </p>

                    <div class="flex gap-2 mt-4">

                        <flux:button size="sm" icon="eye" variant="filled"
                            :href="route('worker.catalog.detail', $proc->id)"
                            class="flex-1 !bg-gray-500 hover:!bg-gray-600 !text-white">
                            Ver
                        </flux:button>

                        <form class="flex-1" method="POST" action="{{ route('worker.procedures.start', $proc->id) }}">
                            @csrf
                            <flux:button size="sm" icon="plus" variant="primary" type="submit"
                                class="w-full !bg-blue-600 hover:!bg-blue-700 !text-white">
                                Iniciar
                            </flux:button>
                        </form>

                    </div>

                </div>
            @empty
                <p class="text-center text-zinc-500 col-span-full py-6">
                    No hay trámites disponibles.
                </p>
            @endforelse

        </div>

        <h2 class="text-2xl font-bold text-[#241178] mt-10">
            Publicaciones del Sindicato
        </h2>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">

            @forelse ($news as $item)
                <div class="border border-zinc-200 bg-white rounded-2xl shadow-sm p-6 hover:shadow-md transition">

                    <h3 class="text-xl font-semibold text-[#DE6601] mb-2 truncate">
                        {{ $item->title }}
                    </h3>

                    <p class="text-sm text-zinc-600 line-clamp-3">
                        {{ $item->content }}
                    </p>

                    <p class="mt-3 text-xs text-[#272800]">
                        <strong>Publicada:</strong>
                        {{ \Carbon\Carbon::parse($item->publication_date)->format('d/m/Y') }}
                    </p>

                    @if ($item->expiration_date)
                        <p class="text-xs text-red-600">
                            <strong>Vigencia:</strong>
                            {{ \Carbon\Carbon::parse($item->expiration_date)->format('d/m/Y') }}
                        </p>
                    @endif

                    @if ($item->type)
                        <span
                            class="inline-block mt-2 px-2 py-1 text-xs rounded-full bg-zinc-100 text-[#241178] font-semibold">
                            {{ ucfirst($item->type) }}
                        </span>
                    @endif

                    <div class="flex gap-2 mt-4">

                        @if ($item->attachment)
                            <flux:button size="sm" icon="arrow-down-tray" variant="filled"
                                :href="asset('storage/' . $item->attachment)"
                                class="flex-1 !bg-gray-500 hover:!bg-gray-600 !text-white">
                                Descargar archivo
                            </flux:button>
                        @endif

                        @if ($item->image)
                            <flux:button size="sm" icon="eye" variant="filled"
                                :href="asset('storage/' . $item->image)" target="_blank"
                                class="flex-1 !bg-gray-500 hover:!bg-gray-600 !text-white">
                                Ver imagen
                            </flux:button>
                        @endif

                    </div>

                </div>

            @empty
                <p class="text-center text-zinc-500 col-span-full py-6">
                    No hay publicaciones disponibles.
                </p>
            @endforelse

        </div>

    </div>

</x-layouts.app>
