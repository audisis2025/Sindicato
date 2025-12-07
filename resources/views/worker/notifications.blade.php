{{-- 
* Nombre de la vista           : notifications.blade.php
* Descripción de la vista      : Panel de notificaciones internas del usuario trabajador.
* Fecha de creación            : 25/11/2025
* Elaboró                      : Iker Piza
* Fecha de liberación          : 25/11/2025
* Autorizó                     : Líder Técnico
* Versión                      : 1.1
* Fecha de mantenimiento       : 27/11/2025
* Folio de mantenimiento       : N/A
* Tipo de mantenimiento        : Correctivo y perfectivo
* Descripción del mantenimiento: Homologación de tabla, botones e iconos según Manual PRO-Laravel V3.4.
* Responsable                  : Iker Piza
* Revisor                      : QA SINDISOFT
--}}

<x-layouts.app :title="__('Mis notificaciones')">

    <div class="flex flex-col gap-6 p-6 w-full max-w-6xl mx-auto">

        <div class="flex justify-between items-center">
            <h1 class="text-3xl font-bold text-[#DE6601]">
                Mis notificaciones
            </h1>

            <flux:button icon="arrow-long-left" icon-variant="outline" variant="ghost" :href="route('worker.index')"
                class="px-4 py-2 !bg-transparent hover:!bg-zinc-100 !text-[#241178] font-semibold rounded-lg">
                Volver al panel
            </flux:button>
        </div>

        <div class="overflow-x-auto bg-white border border-[#D9D9D9] rounded-xl shadow-sm">
            @if ($notifications_list->count() > 0)
                <table class="min-w-full divide-y divide-zinc-200 text-sm font-[Inter]">
                    <thead class="bg-zinc-100">
                        <tr>
                            <th class="px-4 py-3 text-left font-semibold text-black">
                                Título
                            </th>
                            <th class="px-4 py-3 text-left font-semibold text-black">
                                Mensaje
                            </th>
                            <th class="px-4 py-3 text-center font-semibold text-black">
                                Estado
                            </th>
                            <th class="px-4 py-3 text-center font-semibold text-black">
                                Acciones
                            </th>
                        </tr>
                    </thead>

                    <tbody class="divide-y divide-zinc-200 bg-white">
                        @foreach ($notifications_list as $notification)
                            @php
                                $isUnread =
                                    $notification->status === 'unread' || is_null($notification->read_at ?? null);
                            @endphp

                            <tr class="hover:bg-zinc-50 transition">
                                <td class="px-4 py-3 text-sm font-semibold text-black">
                                    {{ $notification->title ?? 'Notificación' }}
                                </td>

                                <td class="px-4 py-3 text-sm text-black/80">
                                    {{ $notification->message ?? '-' }}
                                </td>

                                <td class="px-4 py-3 text-center text-sm">
                                    @if ($isUnread)
                                        <span class="text-orange-600 font-semibold">
                                            No leída
                                        </span>
                                    @else
                                        <span class="text-green-700 font-semibold">
                                            Leída
                                        </span>
                                    @endif
                                </td>

                                <td class="px-4 py-3 text-center text-sm">
                                    @if ($isUnread)
                                        <form method="POST"
                                            action="{{ route('worker.notifications.read', $notification->id) }}"
                                            class="inline-flex">
                                            @csrf
                                            @method('PATCH')

                                            <flux:button size="xs" icon="check" icon-variant="outline"
                                                variant="primary" type="submit"
                                                class="!bg-green-600 hover:!bg-green-700 !text-white font-semibold">
                                                Marcar como leída
                                            </flux:button>
                                        </form>
                                    @else
                                        <span class="text-gray-400 text-xs">
                                            Sin acciones
                                        </span>
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
