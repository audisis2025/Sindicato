{{-- 
* Nombre de la vista          : index.blade.php
* Descripción de la vista     : Vista principal para la gestión de trámites por parte del sindicato, donde se
*                               muestra el listado de trámites registrados y se permite acceder a acciones
*                               de creación, visualización, edición, activación y eliminación de los mismos.
* Fecha de creación           : 03/11/2025
* Elaboró                     : Iker Piza
* Fecha de liberación         : 03/11/2025
* Autorizó                    :
* Versión                     : 1.0
* Fecha de mantenimiento      :
* Folio de mantenimiento      :
* Tipo de mantenimiento       :
* Descripción del mantenimiento:
* Responsable                 :
* Revisor                     :
--}}

<x-layouts.app :title="__('Gestión de trámites')">

    <div class="p-6 w-full max-w-6xl mx-auto space-y-8">

        <div class="flex items-center justify-between">
            <h1 class="text-3xl font-bold text-[#DE6601]">
                Gestión de Trámites
            </h1>

            <flux:button icon="plus" variant="primary" :href="route('union.procedures.create')"
                class="!bg-blue-600 hover:!bg-blue-700 !text-white">
                Crear trámite
            </flux:button>
        </div>

        <div class="border border-zinc-200 rounded-xl shadow-sm overflow-x-auto max-h-[400px]">
            <div class="min-w-full inline-block align-middle">

                <table class="min-w-full divide-y divide-zinc-200 table-auto">
                    <thead class="bg-zinc-100">
                        <tr>
                            <th class="px-4 py-3 font-semibold text-black">Nombre del trámite</th>
                            <th class="px-4 py-3 font-semibold text-black">Número de pasos</th>
                            <th class="px-4 py-3 font-semibold text-black">Tiempo estimado</th>
                            <th class="px-4 py-3 font-semibold text-black">Flujo alterno</th>
                            <th class="px-4 py-3 font-semibold text-black">Fechas</th>
                            <th class="px-4 py-3 font-semibold text-black">Acciones</th>
                        </tr>
                    </thead>


                    <tbody class="divide-y divide-zinc-200 bg-white">

                        @forelse ($procedures as $procedure)
                            <tr class="hover:bg-zinc-50 transition">

                                <td class="px-4 py-3 text-sm text-black text-center">
                                    {{ $procedure->name }}
                                </td>

                                <td class="px-4 py-3 text-sm text-black text-center">
                                    {{ $procedure->steps_count ?? '—' }}
                                </td>

                                <td class="px-4 py-3 text-sm text-black text-center">
                                    {{ $procedure->estimated_days ? $procedure->estimated_days . ' días' : '—' }}
                                </td>

                                <td class="px-4 py-3 text-sm text-black text-center">
                                    {{ $procedure->has_alternate_flow ? 'Sí' : 'No' }}
                                </td>

                                <td class="px-4 py-3 text-sm text-black text-center">
                                    Apertura:
                                    <span class="font-semibold">{{ $procedure->opening_date ?? '—' }}</span><br>
                                    Cierre:
                                    <span class="font-semibold">{{ $procedure->closing_date ?? '—' }}</span>
                                </td>

                                <td class="px-4 py-3 text-sm text-center">
                                    <div class="flex flex-col gap-2 px-2 py-1">
                                        <flux:button size="xs" icon="eye" variant="filled"
                                            :href="route('union.procedures.show', $procedure->id)"
                                            class="w-full !bg-gray-200 hover:!bg-gray-300 text-black">
                                            Ver
                                        </flux:button>

                                        <flux:button size="xs" icon="pencil-square" variant="primary"
                                            :href="route('union.procedures.edit', $procedure->id)"
                                            class="w-full !bg-gray-500 hover:!bg-gray-600 !text-white">
                                            Editar
                                        </flux:button>

                                        <form method="POST"
                                            action="{{ route('union.procedures.toggle', $procedure->id) }}">
                                            @csrf
                                            @method('PATCH')

                                            <flux:button size="xs"
                                                class="w-full !text-white {{ $procedure->status === 'active' ? '!bg-red-600 hover:!bg-red-700' : '!bg-green-600 hover:!bg-green-700' }}"
                                                icon="{{ $procedure->status === 'active' ? 'x-circle' : 'check-circle' }}"
                                                icon-variant="outline" type="submit">
                                                {{ $procedure->status === 'active' ? 'Desactivar' : 'Activar' }}
                                            </flux:button>
                                        </form>

                                        <form method="POST"
                                            action="{{ route('union.procedures.destroy', $procedure->id) }}"
                                            class="delete-form">
                                            @csrf
                                            @method('DELETE')

                                            <flux:button size="xs" icon="trash" variant="danger" type="button"
                                                class="w-full !bg-red-600 hover:!bg-red-700 !text-white delete-btn">
                                                Eliminar
                                            </flux:button>
                                        </form>

                                    </div>
                                </td>
                            </tr>

                        @empty
                            <tr>
                                <td colspan="6" class="px-4 py-6 text-center text-sm text-zinc-500">
                                    No hay trámites registrados.
                                </td>
                            </tr>
                        @endforelse

                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        document.querySelectorAll('.delete-btn').forEach((btn) => {
            btn.addEventListener('click', function() {
                const form = this.closest('form');

                Swal.fire({
                        title: '¿Eliminar trámite?',
                        text: 'Esta acción no se puede deshacer.',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Confirmar',
                        cancelButtonText: 'Cancelar',
                        confirmButtonColor: '#42A958',
                        cancelButtonColor: '#EE0000',
                        buttonsStyling: true
                    })
                    .then((result) => {
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    });
            });
        });
    </script>
    @if (session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Éxito',
                text: @json(session('success')),
                confirmButtonColor: '#16a34a',
            });
        </script>
    @endif

    @if (session('error'))
        <script>
            Swal.fire({
                icon: 'error',
                title: 'No se pudo eliminar',
                text: @json(session('error')),
                confirmButtonColor: '#dc2626',
            });
        </script>
    @endif
</x-layouts.app>
