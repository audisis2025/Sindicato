<x-layouts.app :title="__('Noticias y convocatorias')">
    <div class="flex flex-col gap-6 p-6 w-full">

        <div class="flex justify-between items-center">
            <h1 class="text-3xl font-[Poppins] font-bold text-[#DC6601]">
                Noticias y convocatorias
            </h1>

            <a href="{{ route('union.news.create') }}"
               class="inline-flex items-center gap-2 bg-[#DC6601] hover:bg-[#EE0000] text-white font-semibold px-4 py-2 rounded-lg transition">
                <x-heroicon-o-plus class="w-5 h-5" />
                Nueva publicación
            </a>
        </div>

        <div class="bg-white border border-[#D9D9D9]/60 rounded-xl shadow-sm overflow-hidden">
            <table class="min-w-full text-sm text-left text-[#241178]">
                <thead class="bg-[#241178] text-white">
                    <tr>
                        <th class="px-4 py-3">Título</th>
                        <th class="px-4 py-3">Categoría</th>
                        <th class="px-4 py-3">Fecha publicación</th>
                        <th class="px-4 py-3">Estado</th>
                        <th class="px-4 py-3 text-center">Acciones</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse ($news_list ?? [] as $news)
                        <tr class="border-t border-[#E5E5E5] hover:bg-[#DC6601]/5 transition">
                            <td class="px-4 py-3 font-medium">{{ $news->title }}</td>
                            <td class="px-4 py-3">{{ $news->category }}</td>
                            <td class="px-4 py-3">{{ $news->publication_date ?? '—' }}</td>

                            <td class="px-4 py-3">
                                @if ($news->status === 'published')
                                    <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-700 font-semibold">
                                        Publicada
                                    </span>
                                @elseif ($news->status === 'draft')
                                    <span class="px-2 py-1 text-xs rounded-full bg-yellow-100 text-yellow-700 font-semibold">
                                        Borrador
                                    </span>
                                @else
                                    <span class="px-2 py-1 text-xs rounded-full bg-gray-100 text-gray-600 font-semibold">
                                        {{ $news->status }}
                                    </span>
                                @endif
                            </td>

                            <td class="px-4 py-3 flex justify-center gap-3">
                                <a href="{{ route('union.news.edit', $news->id) }}"
                                   class="text-[#241178] hover:text-[#DC6601]">
                                    <x-heroicon-o-pencil-square class="w-5 h-5" />
                                </a>

                                <form action="{{ route('union.news.destroy', $news->id) }}"
                                      method="POST"
                                      onsubmit="return confirm('¿Deseas eliminar esta publicación?')">
                                    @csrf
                                    @method('DELETE')

                                    <button type="submit" class="text-red-600 hover:text-[#DC6601]">
                                        <x-heroicon-o-trash class="w-5 h-5" />
                                    </button>
                                </form>
                            </td>
                        </tr>

                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-6 text-center text-gray-500">
                                No hay publicaciones registradas.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div>
</x-layouts.app>
