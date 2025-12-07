{{-- 
* Nombre de la vista           : procedures-index.blade.php
* Descripción de la vista      : Módulo de gestión de trámites creados por el Sindicato. Incluye listado y acciones CRUD.
* Fecha de creación            : 03/11/2025
* Elaboró                      : Iker Piza
* Fecha de liberación          : 03/11/2025
* Autorizó                     : Líder Técnico
* Versión                      : 1.1
* Fecha de mantenimiento       : 27/11/2025
* Folio de mantenimiento       : N/A
* Tipo de mantenimiento        : Correctivo y perfectivo
* Descripción del mantenimiento: Homologación según sección 8.8 del Manual PRO-Laravel V3.4.
* Responsable                  : Iker Piza
* Revisor                      : QA SINDISOFT
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
                                    <div class="flex gap-3 overflow-x-auto whitespace-nowrap px-2 py-1">
                                        <flux:button size="xs" icon="eye" variant="filled"
                                            :href="route('union.procedures.show', $procedure->id)"
                                            class="!bg-gray-200 hover:!bg-gray-300 text-black">
                                            Ver
                                        </flux:button>

                                        <flux:button size="xs" icon="pencil-square" variant="primary"
                                            :href="route('union.procedures.edit', $procedure->id)"
                                            class="!bg-gray-500 hover:!bg-gray-600 !text-white">
                                            Editar
                                        </flux:button>

                                        <form method="POST"
                                            action="{{ route('union.procedures.toggle', $procedure->id) }}">
                                            @csrf
                                            @method('PATCH')

                                            <flux:button size="xs"
                                                class="!text-white {{ $procedure->status === 'active' ? '!bg-red-600 hover:!bg-red-700' : '!bg-green-600 hover:!bg-green-700' }}"
                                                icon="{{ $procedure->status === 'active' ? 'x-circle' : 'check-circle' }}"
                                                icon-variant="outline" type="submit">
                                                {{ $procedure->status === 'active' ? 'Desactivar' : 'Activar' }}
                                            </flux:button>
                                        </form>

                                        <form method="POST"
                                            action="{{ route('union.procedures.destroy', $procedure->id) }}"
                                            onsubmit="return confirm('¿Seguro que deseas eliminar este trámite?');">
                                            @csrf
                                            @method('DELETE')

                                            <flux:button size="xs" icon="trash"
                                                class="!bg-red-600 hover:!bg-red-700 !text-white" type="submit">
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

</x-layouts.app>
