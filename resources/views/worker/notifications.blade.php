{{-- 
* Nombre de la vista          : notifications.blade.php
* Descripción de la vista     : Vista para la consulta de notificaciones del trabajador, mostrando el listado
*                               de avisos del sistema y permitiendo marcar notificaciones como leídas.
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

<x-layouts.app :title="__('Mis notificaciones')">

    <div class="flex flex-col gap-6 p-6 w-full max-w-6xl mx-auto">

        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
            <h1 class="text-3xl font-bold text-[#DE6601]">
                Mis notificaciones
            </h1>

            <flux:button icon="arrow-long-left" icon-variant="outline" variant="ghost" :href="route('worker.index')">
                Regresar
            </flux:button>
        </div>

        <div class="overflow-x-auto bg-white border border-[#D9D9D9] rounded-xl shadow-sm">

            @if ($notifications_list->count() > 0)
                <table class="min-w-full divide-y divide-zinc-200 text-sm">
                    <thead class="bg-zinc-100">
                        <tr>
                            <th class="px-4 py-3 text-left font-semibold text-black">Título</th>
                            <th class="px-4 py-3 text-left font-semibold text-black">Mensaje</th>
                            <th class="px-4 py-3 text-center font-semibold text-black">Estado</th>
                            <th class="px-4 py-3 text-center font-semibold text-black">Acciones</th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-zinc-200 bg-white">
                        @foreach ($notifications_list as $notification)
                            @php
                                $isUnread = $notification->status === 'unread' || is_null($notification->read_at);
                            @endphp

                            <tr class="hover:bg-zinc-50 transition">
                                <td class="px-4 py-3 font-semibold text-black">
                                    {{ $notification->title ?? 'Notificación' }}
                                </td>

                                <td class="px-4 py-3 text-black/80">
                                    {{ $notification->message ?? '-' }}
                                </td>

                                <td class="px-4 py-3 text-center">
                                    @if ($isUnread)
                                        <span class="text-amber-600 font-semibold">No leída</span>
                                    @else
                                        <span class="text-green-700 font-semibold">Leída</span>
                                    @endif
                                </td>

                                <td class="px-4 py-3 text-center">
                                    @if ($isUnread)
                                        <form method="POST"
                                            action="{{ route('worker.notifications.read', $notification->id) }}">
                                            @csrf
                                            @method('PATCH')

                                            <flux:button size="xs" icon="check-circle" icon-variant="outline"
                                                variant="primary" type="submit"
                                                class="!bg-green-600 hover:!bg-green-700 !text-white">
                                                Marcar como leída
                                            </flux:button>
                                        </form>
                                    @else
                                        <span class="text-gray-400 text-xs">Sin acciones</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <p class="text-center py-4 text-gray-500 text-sm">
                    No tienes notificaciones por el momento.
                </p>
            @endif

        </div>

    </div>

</x-layouts.app>
