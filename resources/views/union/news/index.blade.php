{{-- 
* Nombre de la vista           : news-index.blade.php
* Descripción de la vista      : Vista para listar y administrar noticias y convocatorias del sindicato.
* Fecha de creación            : 27/11/2025
* Elaboró                      : Iker Piza
* Fecha de liberación          : 27/11/2025
* Autorizó                     : Líder Técnico
* Versión                      : 1.0
* Fecha de mantenimiento       : 27/11/2025
* Folio de mantenimiento       : N/A
* Tipo de mantenimiento        : Correctivo y perfectivo
* Descripción del mantenimiento: Homologación de botones, iconos, colores, tabla y alerts según Manual PRO-Laravel V3.4.
* Responsable                  : Iker Piza
* Revisor                      : QA SINDISOFT
--}}

<x-layouts.app :title="__('Noticias y convocatorias')">

    <div class="flex flex-col gap-6 p-6 w-full max-w-6xl mx-auto">

        <div class="flex justify-between items-center">
            <h1 class="text-3xl font-bold text-[#DE6601]">
                Noticias y convocatorias
            </h1>

            <flux:button icon="plus" icon-variant="outline" variant="primary"
                class="!bg-blue-600 hover:!bg-blue-700 !text-white" :href="route('union.news.create')">
                Nueva publicación
            </flux:button>
        </div>

        <div class="bg-white border border-zinc-200 rounded-xl shadow-sm overflow-hidden">
            <table class="min-w-full text-sm text-left">
                <thead class="bg-zinc-100 text-black">
                    <tr>
                        <th class="px-4 py-3 font-semibold">Título</th>
                        <th class="px-4 py-3 font-semibold">Categoría</th>
                        <th class="px-4 py-3 font-semibold">Fecha publicación</th>
                        <th class="px-4 py-3 font-semibold">Estado</th>
                        <th class="px-4 py-3 text-center font-semibold">Acciones</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-zinc-200">
                    @forelse ($news_list as $news)
                        <tr class="hover:bg-zinc-50 transition">

                            <td class="px-4 py-3 font-medium text-black">
                                {{ $news->title }}
                            </td>

                            <td class="px-4 py-3 text-black">
                                @switch($news->type)
                                    @case('announcement')
                                        Convocatoria
                                    @break

                                    @case('communication')
                                        Comunicado
                                    @break

                                    @case('event')
                                        Evento
                                    @break
                                @endswitch
                            </td>

                            <td class="px-4 py-3 text-black">
                                {{ $news->publication_date_formatted ?? '—' }}
                            </td>

                            <td class="px-4 py-3 text-black">
                                @if ($news->status === 'published')
                                    <span class="flex items-center gap-1 text-green-700 font-semibold">
                                        <x-heroicon-o-check class="w-4 h-4" />
                                        Publicada
                                    </span>
                                @elseif ($news->status === 'draft')
                                    <span class="flex items-center gap-1 text-yellow-700 font-semibold">
                                        <x-heroicon-o-exclamation-triangle class="w-4 h-4" />
                                        Borrador
                                    </span>
                                @else
                                    <span class="flex items-center gap-1 text-gray-600 font-semibold">
                                        <x-heroicon-o-information-circle class="w-4 h-4" />
                                        {{ ucfirst($news->status) }}
                                    </span>
                                @endif
                            </td>

                            <td class="px-4 py-3">
                                <div class="flex justify-center gap-2 flex-nowrap">

                                    <flux:button size="xs" icon="pencil-square" icon-variant="outline"
                                        variant="primary" class="!bg-gray-500 hover:!bg-gray-600 !text-white"
                                        :href="route('union.news.edit', $news->id)">
                                        Editar
                                    </flux:button>

                                    <form action="{{ route('union.news.destroy', $news->id) }}" method="POST"
                                        class="delete-form">
                                        @csrf
                                        @method('DELETE')

                                        <flux:button size="xs" icon="trash" icon-variant="outline"
                                            variant="danger" type="button"
                                            class="delete-btn !bg-red-600 hover:!bg-red-700 !text-white">
                                            Eliminar
                                        </flux:button>
                                        
                                    </form>

                                </div>
                            </td>

                        </tr>

                        @empty
                            <tr>
                                <td colspan="5" class="px-4 py-6 text-center text-zinc-500">
                                    No hay publicaciones registradas.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>

        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

        <script>
            document.querySelectorAll(".delete-btn").forEach(btn => {
                btn.addEventListener("click", function() {
                    const form = this.closest("form");

                    Swal.fire({
                        title: "¿Eliminar publicación?",
                        text: "Esta acción no se puede deshacer.",
                        icon: "warning",
                        showCancelButton: true,
                        confirmButtonText: "Eliminar",
                        cancelButtonText: "Cancelar",
                        confirmButtonColor: "#dc2626",
                        cancelButtonColor: "#6b7280",
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    });
                });
            });
        </script>

    </x-layouts.app>
