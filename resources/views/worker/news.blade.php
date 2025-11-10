{{-- ===========================================================
 Nombre de la vista: news.blade.php
 Módulo: Panel del Trabajador
 Descripción: Muestra las convocatorias y noticias del sindicato.
 Fecha de creación: 07/11/2025
 Elaboró: Iker Piza
 Versión: 1.0
 =========================================================== --}}

<x-layouts.app :title="__('Convocatorias y Anuncios')">
    <div class="w-full flex flex-col items-center min-h-[80vh] bg-[#FFFFFF] text-[#000000] p-6">

        <h1 class="text-3xl font-[Poppins] font-bold text-[#DC6601] mb-2">
            Convocatorias y Anuncios
        </h1>
        <p class="text-[#241178] font-[Inter] mb-8 text-center">
            Consulta los comunicados y noticias más recientes del sindicato.
        </p>

        <div class="w-full max-w-4xl grid gap-6">
            @forelse ($news as $item)
                <div class="border border-[#D9D9D9] bg-white rounded-2xl shadow-md p-6 transition hover:shadow-lg">
                    <h2 class="text-2xl font-[Poppins] font-bold text-[#241178] mb-2">
                        {{ $item->titulo }}
                    </h2>
                    <p class="text-gray-600 text-sm mb-3">
                        Publicado el {{ $item->created_at->format('d/m/Y') }}
                        — <span class="capitalize">{{ $item->tipo }}</span>
                    </p>
                    <p class="text-[#000000] font-[Inter] text-base leading-relaxed mb-4">
                        {{ $item->contenido }}
                    </p>

                    @if ($item->archivo_path)
                        <a href="{{ asset('storage/' . $item->archivo_path) }}"
                           class="text-[#DC6601] hover:underline font-semibold text-sm">
                           Descargar archivo adjunto
                        </a>
                    @endif
                </div>
            @empty
                <p class="text-center text-gray-500">No hay convocatorias ni anuncios publicados actualmente.</p>
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
