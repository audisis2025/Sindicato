{{-- 
* Vista: news_detail.blade.php
* Descripción: Detalle de una publicación (convocatoria, comunicado o evento)
* Versión: 1.0 – Homologada según Manual PRO-Laravel V3.4
--}}

<x-layouts.app :title="$news->title">

    <div class="w-full flex flex-col items-center min-h-[80vh] bg-white text-black p-6">

        {{-- HEADER --}}
        <div class="w-full max-w-4xl flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6">
            <div>
                <h1 class="text-3xl font-[Poppins] font-bold text-[#DE6601] mb-2">
                    {{ $news->title }}
                </h1>

                <p class="text-sm text-gray-600">
                    Publicado el: 
                    <strong>{{ \Carbon\Carbon::parse($news->publication_date)->format('d/m/Y') }}</strong>
                </p>

                @if ($news->expiration_date)
                    <p class="text-sm text-red-600 font-semibold">
                        Vigencia hasta: {{ \Carbon\Carbon::parse($news->expiration_date)->format('d/m/Y') }}
                    </p>
                @endif
            </div>

            <flux:button
                icon="arrow-long-left"
                icon-variant="outline"
                variant="ghost"
                :href="route('worker.news.index')"
                class="px-4 py-2 !bg-transparent hover:!bg-zinc-100 !text-[#241178] font-semibold rounded-lg mt-3 sm:mt-0 flex items-center gap-2"
            >
                Volver
            </flux:button>
        </div>

        {{-- TARJETA --}}
        <div class="w-full max-w-4xl bg-white border border-zinc-200 rounded-2xl shadow-md p-6">

            {{-- IMAGEN DE PORTADA --}}
            @if ($news->image_path)
                <img 
                    src="{{ asset('storage/' . $news->image_path) }}" 
                    class="w-full h-64 object-cover rounded-xl mb-5 shadow-sm"
                    alt="Imagen de portada"
                >
            @endif

            {{-- TIPO --}}
            <span class="
                px-3 py-1 text-xs font-semibold rounded-lg text-white mb-4 inline-block
                @if($news->type === 'announcement') bg-[#241178] 
                @elseif($news->type === 'event') bg-[#DE6601] 
                @else bg-gray-600 @endif
            ">
                {{ strtoupper($news->type) }}
            </span>

            {{-- CONTENIDO --}}
            <div class="text-gray-800 leading-relaxed text-sm mb-6 whitespace-pre-line">
                {!! nl2br(e($news->content)) !!}
            </div>

            {{-- ARCHIVO ADJUNTO --}}
            @if ($news->file_path)
                <flux:button
                    icon="arrow-down-tray"
                    icon-variant="outline"
                    variant="filled"
                    :href="asset('storage/' . $news->file_path)"
                    class="mt-4"
                >
                    Descargar archivo adjunto
                </flux:button>
            @endif

        </div>

    </div>

</x-layouts.app>
