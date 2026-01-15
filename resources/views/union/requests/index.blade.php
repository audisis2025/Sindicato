{{-- 
* Nombre de la vista          : index.blade.php
* Descripción de la vista     : Vista de listado de solicitudes realizadas por los trabajadores, permitiendo
*                               su consulta, filtrado por estado y acceso al detalle de cada trámite solicitado.
* Fecha de creación           : 14/01/2026
* Elaboró                     : Iker Piza
* Fecha de liberación         : 14/01/2026
* Autorizó                    :
* Versión                     : 1.0
* Fecha de mantenimiento      :
* Folio de mantenimiento      :
* Tipo de mantenimiento       :
* Descripción del mantenimiento:
* Responsable                 :
* Revisor                     :
--}}

<x-layouts.app :title="__('Solicitudes de trabajadores')">

    <div class="flex flex-col gap-6 p-6 w-full max-w-6xl mx-auto">

        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
            <h1 class="text-3xl font-bold text-[#DE6601]">
                Solicitudes de trabajadores
            </h1>

            <flux:button icon="arrow-long-left" icon-variant="outline" variant="ghost" :href="url()->previous()">
                Regresar
            </flux:button>
        </div>

        <form method="GET" action="{{ route('union.requests.index') }}" class="flex flex-wrap gap-4 items-end">

            <div class="flex flex-col">
                <label for="keyword" class="text-sm font-semibold text-[#272800]">Buscar</label>
                <input id="keyword" type="text" name="keyword" value="{{ request('keyword') }}"
                    placeholder="Trabajador o trámite..." maxlength="120"
                    class="border border-zinc-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-600 outline-none">
            </div>

            <div class="flex flex-col">
                <label for="status" class="text-sm font-semibold text-[#272800]">Estado</label>
                <select id="status" name="status"
                    class="border border-zinc-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-600 outline-none">
                    <option value="">Todos</option>
                    <option value="started" {{ request('status') === 'started' ? 'selected' : '' }}>Iniciado</option>
                    <option value="pending_worker" {{ request('status') === 'pending_worker' ? 'selected' : '' }}>
                        Pendiente trabajador</option>
                    <option value="pending_union" {{ request('status') === 'pending_union' ? 'selected' : '' }}>
                        Pendiente sindicato</option>
                    <option value="in_progress" {{ request('status') === 'in_progress' ? 'selected' : '' }}>En proceso
                    </option>
                    <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Finalizado
                    </option>
                    <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Cancelado
                    </option>
                    <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Rechazado</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pendiente</option>
                </select>
            </div>

            <flux:button icon="magnifying-glass" variant="primary" type="submit"
                class="h-10 px-4 !bg-gray-500 hover:!bg-gray-600 !text-white">
                Buscar
            </flux:button>

            <flux:button icon="arrow-path" variant="primary" :href="route('union.requests.index')"
                class="h-10 px-4 !bg-green-600 hover:!bg-green-700 !text-white">
                Actualizar
            </flux:button>

        </form>

        <div class="overflow-x-auto bg-white border border-[#D9D9D9] rounded-xl shadow-sm">

            @if ($requests->count() > 0)
                <table class="min-w-full divide-y divide-zinc-200 text-sm">

                    <thead class="bg-zinc-100">
                        <tr>
                            <th class="px-4 py-3 text-left font-semibold text-black">#</th>
                            <th class="px-4 py-3 text-left font-semibold text-black">Trabajador</th>
                            <th class="px-4 py-3 text-left font-semibold text-black">Trámite</th>
                            <th class="px-4 py-3 text-left font-semibold text-black">Estado</th>
                            <th class="px-4 py-3 text-left font-semibold text-black">Fecha</th>
                            <th class="px-4 py-3 text-center font-semibold text-black">Acciones</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-zinc-200 bg-white">
                        @foreach ($requests as $i => $req)
                            @php
                                $mapping = [
                                    'started' => ['Iniciado', 'text-blue-600'],
                                    'pending_worker' => ['Pendiente trabajador', 'text-amber-600'],
                                    'pending_union' => ['Pendiente sindicato', 'text-purple-600'],
                                    'in_progress' => ['En proceso', 'text-sky-600'],
                                    'completed' => ['Finalizado', 'text-green-600'],
                                    'cancelled' => ['Cancelado', 'text-gray-600'],
                                    'rejected' => ['Rechazado', 'text-red-600'],
                                    'pending' => ['Pendiente', 'text-amber-600'],
                                ];
                                [$estadoTexto, $color] = $mapping[$req->status] ?? ['Desconocido', 'text-gray-500'];
                            @endphp

                            <tr class="hover:bg-zinc-50 transition">

                                <td class="px-4 py-3">{{ $i + 1 }}</td>

                                <td class="px-4 py-3 text-black">
                                    {{ $req->user->name ?? '—' }}
                                </td>

                                <td class="px-4 py-3 text-black">
                                    {{ $req->procedure->name ?? '—' }}
                                </td>

                                <td class="px-4 py-3 text-black">
                                    <span class="{{ $color }} font-semibold">
                                        {{ $estadoTexto }}
                                    </span>
                                </td>

                                <td class="px-4 py-3 text-black">
                                    {{ optional($req->created_at)->format('d/m/Y') }}
                                </td>

                                <td class="px-4 py-3 text-center">
                                    <flux:button size="xs" icon="eye" variant="filled"
                                        :href="route('union.requests.show', $req->id)"
                                        class="!bg-gray-500 hover:!bg-gray-600 !text-white">
                                        Ver detalle
                                    </flux:button>
                                </td>

                            </tr>
                        @endforeach
                    </tbody>

                </table>
            @else
                <p class="text-center py-4 text-gray-500 text-sm">
                    No hay solicitudes registradas.
                </p>
            @endif

        </div>

    </div>

</x-layouts.app>
