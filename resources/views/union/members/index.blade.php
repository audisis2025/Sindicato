{{-- 
* Nombre de la vista           : members-index.blade.php
* Descripción de la vista      : Vista para listar y administrar trabajadores registrados del Sindicato.
* Fecha de creación            : 04/11/2025
* Elaboró                      : Iker Piza
* Fecha de liberación          : 04/11/2025
* Autorizó                     : Líder Técnico
* Versión                      : 1.4
* Fecha de mantenimiento       : 27/11/2025
* Folio de mantenimiento       : N/A
* Tipo de mantenimiento        : Correctivo y perfectivo
* Descripción del mantenimiento: Homologación total según Manual PRO-Laravel V3.4 (botones, colores, alerts e iconografía).
* Responsable                  : Iker Piza
* Revisor                      : QA SINDISOFT
--}}

<x-layouts.app :title="__('Trabajadores registrados')">

    <div class="p-6 w-full max-w-6xl mx-auto space-y-8">

        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
            <h1 class="text-3xl font-bold text-[#DE6601]">
                Gestión de Trabajadores
            </h1>

            <flux:button
                icon="plus"
                icon-variant="outline"
                variant="primary"
                class="!bg-blue-600 hover:!bg-blue-700 !text-white"
                :href="route('union.members.create')"
            >
                Registrar nuevo trabajador
            </flux:button>
        </div>

        <form
            method="GET"
            action="{{ route('union.members.index') }}"
            class="flex flex-wrap gap-4 items-end bg-white p-4 border border-zinc-200 rounded-xl shadow-sm"
        >
            <div class="flex flex-col">
                <label for="name" class="text-sm font-semibold text-[#272800]">Nombre</label>
                <input
                    id="name"
                    type="text"
                    name="name"
                    value="{{ request('name') }}"
                    placeholder="Buscar por nombre..."
                    class="border border-zinc-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-600 outline-none"
                >
            </div>

            <div class="flex flex-col">
                <label for="gender" class="text-sm font-semibold text-[#272800]">Género</label>
                <select
                    id="gender"
                    name="gender"
                    class="border border-zinc-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-600 outline-none"
                >
                    <option value="">Todos</option>
                    <option value="H"  {{ request('gender') === 'H' ? 'selected' : '' }}>Hombre</option>
                    <option value="M"  {{ request('gender') === 'M' ? 'selected' : '' }}>Mujer</option>
                    <option value="ND" {{ request('gender') === 'ND' ? 'selected' : '' }}>No definido</option>
                    <option value="X"  {{ request('gender') === 'X' ? 'selected' : '' }}>Prefiero no decirlo</option>
                </select>
            </div>

            <flux:button
                icon="magnifying-glass"
                icon-variant="outline"
                variant="primary"
                type="submit"
                class="h-10 px-4 !bg-gray-500 hover:!bg-gray-600 !text-white"
            >
                Filtrar
            </flux:button>

            <flux:button
                icon="arrow-path"
                icon-variant="outline"
                variant="primary"
                :href="route('union.members.index')"
                class="h-10 px-4 !bg-blue-500 hover:!bg-blue-600 !text-white"
            >
                Limpiar
            </flux:button>
        </form>

        <div class="overflow-x-auto border border-zinc-200 rounded-xl shadow-sm bg-white">
            <table class="min-w-full divide-y divide-zinc-200 text-sm">
                <thead class="bg-zinc-100">
                    <tr>
                        <th class="px-4 py-3 text-left font-semibold text-black">Nombre</th>
                        <th class="px-4 py-3 text-left font-semibold text-black">Correo</th>
                        <th class="px-4 py-3 text-left font-semibold text-black">CURP</th>
                        <th class="px-4 py-3 text-left font-semibold text-black">RFC</th>
                        <th class="px-4 py-3 text-left font-semibold text-black">Sexo</th>
                        <th class="px-4 py-3 text-left font-semibold text-black">Clave presupuestal</th>
                        <th class="px-4 py-3 text-center font-semibold text-black">Estado</th>
                        <th class="px-4 py-3 text-center font-semibold text-black">Acciones</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-zinc-200 bg-white">
                    @forelse ($workers as $worker)
                        <tr class="hover:bg-zinc-50 transition">

                            <td class="px-4 py-3 max-w-[180px] truncate text-black" title="{{ $worker->name }}">
                                {{ $worker->name ?? '—' }}
                            </td>

                            <td class="px-4 py-3 text-black">
                                {{ $worker->email ?? '—' }}
                            </td>

                            <td class="px-4 py-3 text-black">
                                {{ $worker->curp ?? '—' }}
                            </td>

                            <td class="px-4 py-3 text-black">
                                {{ $worker->rfc ?? '—' }}
                            </td>

                            <td class="px-4 py-3 text-black">
                                @switch($worker->gender)
                                    @case('H') Hombre @break
                                    @case('M') Mujer @break
                                    @case('ND') No definido @break
                                    @case('X') Pref. no decirlo @break
                                    @default —
                                @endswitch
                            </td>

                            <td class="px-4 py-3 text-black">
                                {{ $worker->budget_key ?? '—' }}
                            </td>

                            <td class="px-4 py-3 text-center font-semibold">
                                @if ($worker->active)
                                    <span class="text-green-700 flex items-center justify-center gap-1">
                                        <x-heroicon-o-check class="w-4 h-4" />
                                        Activo
                                    </span>
                                @else
                                    <span class="text-red-600 flex items-center justify-center gap-1">
                                        <x-heroicon-o-x-circle class="w-4 h-4" />
                                        Inactivo
                                    </span>
                                @endif
                            </td>

                            <td class="px-4 py-3">
                                <div class="flex gap-2 justify-center flex-nowrap min-w-[140px]">

                                    <flux:button
                                        size="xs"
                                        icon="pencil-square"
                                        icon-variant="outline"
                                        variant="primary"
                                        :href="route('union.members.edit', $worker->id)"
                                        class="!bg-gray-500 hover:!bg-gray-600 !text-white"
                                    >
                                        Editar
                                    </flux:button>

                                    <form
                                        action="{{ route('union.members.destroy', $worker->id) }}"
                                        method="POST"
                                        class="delete-form"
                                    >
                                        @csrf
                                        @method('DELETE')

                                        <flux:button
                                            size="xs"
                                            icon="trash"
                                            icon-variant="outline"
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
                            <td colspan="8" class="px-4 py-6 text-center text-sm text-zinc-500">
                                No hay trabajadores registrados.
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
            btn.addEventListener("click", function () {
                const form = this.closest("form");

                Swal.fire({
                    title: "¿Eliminar trabajador?",
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
