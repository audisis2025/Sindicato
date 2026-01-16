{{-- 
* Nombre de la vista          : index.blade.php
* Descripción de la vista     : Vista para la gestión de publicaciones del sindicato (noticias, comunicados,
*                               convocatorias y eventos), mostrando el listado con su categoría, fecha de
*                               publicación, estado y acciones de edición o eliminación.
* Fecha de creación           : 19/11/2025
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

<x-layouts.app :title="__('Noticias y convocatorias')">

    <div class="flex flex-col gap-6 p-6 w-full max-w-6xl mx-auto">

        <div class="flex justify-between items-center">
            <h1 class="text-3xl font-bold text-[#DE6601]">
                Noticias y convocatorias
            </h1>

            <flux:button
                icon="plus"
                variant="primary"
                class="!bg-blue-600 hover:!bg-blue-700 !text-white"
                :href="route('union.news.create')"
            >
                Nueva publicación
            </flux:button>
        </div>

        <div class="overflow-x-auto border border-zinc-200 rounded-xl shadow-sm bg-white">
            <table class="min-w-full divide-y divide-zinc-200 text-sm text-left">
                <thead class="bg-zinc-100">
                    <tr>
                        <th class="px-4 py-3 font-semibold text-black">Título</th>
                        <th class="px-4 py-3 font-semibold text-black">Categoría</th>
                        <th class="px-4 py-3 font-semibold text-black">Fecha publicación</th>
                        <th class="px-4 py-3 font-semibold text-black">Estado</th>
                        <th class="px-4 py-3 text-center font-semibold text-black">Acciones</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-zinc-200 bg-white">
                    @forelse ($news_list as $news)
                        <tr class="hover:bg-zinc-50 transition">

                            <td class="px-4 py-3 font-medium text-black">
                                {{ $news->title ?? '—' }}
                            </td>

                            <td class="px-4 py-3 text-black">
                                @switch($news->type)
                                    @case('announcement') Convocatoria @break
                                    @case('communication') Comunicado @break
                                    @case('event') Evento @break
                                    @default — 
                                @endswitch
                            </td>

                            <td class="px-4 py-3 text-black">
                                {{ $news->publication_date_formatted ?? '—' }}
                            </td>

                            <td class="px-4 py-3 text-black">
                                @if ($news->status === 'published')
                                    <span class="text-green-700 font-semibold">Publicada</span>
                                @elseif ($news->status === 'draft')
                                    <span class="text-amber-600 font-semibold">Borrador</span>
                                @else
                                    <span class="text-gray-600 font-semibold">{{ ucfirst($news->status) }}</span>
                                @endif
                            </td>

                            <td class="px-4 py-3">
                                <div class="flex justify-center gap-2 flex-nowrap">

                                    <flux:button
                                        size="xs"
                                        icon="pencil-square"
                                        variant="primary"
                                        class="!bg-gray-500 hover:!bg-gray-600 !text-white"
                                        :href="route('union.news.edit', $news->id)"
                                    >
                                        Editar
                                    </flux:button>

                                    <form
                                        action="{{ route('union.news.destroy', $news->id) }}"
                                        method="POST"
                                        class="delete-form"
                                    >
                                        @csrf
                                        @method('DELETE')

                                        <flux:button
                                            size="xs"
                                            icon="trash"
                                            variant="danger"
                                            type="button"
                                            class="delete-btn !bg-red-600 hover:!bg-red-700 !text-white"
                                        >
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
                    title: "Eliminar publicación",
                    text: "Esta acción no se puede deshacer.",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonText: "Confirmar",
                    cancelButtonText: "Cancelar",
                    confirmButtonColor: "#dc2626",
                    cancelButtonColor: "#6b7280",
                }).then((result) => {
                    if (result.isConfirmed) form.submit();
                });
            });
        });
    </script>

</x-layouts.app>
