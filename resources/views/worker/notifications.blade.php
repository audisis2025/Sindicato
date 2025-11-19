<x-layouts.app :title="__('Notificaciones')">

    {{-- FLATPICKR --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://npmcdn.com/flatpickr/dist/l10n/es.js"></script>

    <div class="w-full flex flex-col items-center min-h-[80vh] bg-white text-black p-6">

        <h1 class="text-3xl font-[Poppins] font-bold text-[#DC6601] mb-6">
            Notificaciones
        </h1>

        {{-- FILTROS --}}
        <form method="GET" action="{{ route('worker.notifications.index') }}"
            class="w-full max-w-3xl bg-white border border-[#D9D9D9] rounded-xl shadow-md p-5 mb-8">

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">

                <div class="flex flex-col">
                    <label class="text-sm font-semibold text-[#241178]">Desde</label>
                    <input type="text" name="date_from" id="date_from" value="{{ request('date_from') }}"
                        class="border border-[#D9D9D9] rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-[#DC6601]">
                </div>

                <div class="flex flex-col">
                    <label class="text-sm font-semibold text-[#241178]">Hasta</label>
                    <input type="text" name="date_to" id="date_to" value="{{ request('date_to') }}"
                        class="border border-[#D9D9D9] rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-[#DC6601]">
                </div>

                <div class="flex items-center gap-2 mt-6">
                    <input type="checkbox" name="unread" id="unread" value="1"
                        {{ request('unread') ? 'checked' : '' }}>
                    <label for="unread" class="text-sm font-semibold text-[#241178]">
                        Solo no leídas
                    </label>
                </div>

            </div>

            <div class="flex justify-end mt-4 gap-3">
                <a href="{{ route('worker.notifications.index') }}"
                    class="px-4 py-2 bg-[#241178]/10 text-[#241178] hover:bg-[#241178]/20 font-semibold rounded-lg transition">
                    Limpiar
                </a>

                <button type="submit"
                    class="px-4 py-2 bg-[#DC6601] hover:bg-[#EE0000] text-white font-semibold rounded-lg transition">
                    Filtrar
                </button>
            </div>

        </form>

        {{-- BOTÓN MARCAR TODAS --}}
        @if ($notifications_list->count() > 0)
            <form method="POST" action="{{ route('worker.notifications.readAll') }}" class="mb-5">
                @csrf
                <button type="submit"
                    class="px-4 py-2 bg-[#241178] hover:bg-[#1e0f6b] text-white font-semibold rounded-lg transition">
                    Marcar todas como leídas
                </button>
            </form>
        @endif

        {{-- LISTADO --}}
        <div class="w-full max-w-3xl space-y-4">
            @forelse ($notifications_list as $n)
                @php
                    $bgClass = $n->status === 'unread' ? 'bg-[#FFF6EE]' : 'bg-white';
                    $borderClass = $n->status === 'unread' ? 'border-[#DC6601]' : 'border-[#D9D9D9]';
                @endphp

                <div class="rounded-2xl shadow-sm p-5 border {{ $bgClass }} {{ $borderClass }}">

                    <div class="flex justify-between items-center">
                        <h2 class="text-lg font-[Poppins] font-semibold text-[#241178]">
                            {{ $n->title }}
                        </h2>

                        @if ($n->status === 'unread')
                            <form method="POST" action="{{ route('worker.notifications.read', $n->id) }}">
                                @csrf
                                <button type="submit"
                                    class="text-sm text-[#DC6601] hover:text-[#EE0000] font-semibold">
                                    Marcar como leída
                                </button>
                            </form>
                        @endif
                    </div>

                    <p class="text-gray-700 text-sm mt-2 mb-2">{{ $n->message }}</p>

                    <p class="text-xs text-gray-500 text-right">
                        {{ $n->created_at->format('d/m/Y H:i') }}
                    </p>
                </div>

            @empty
                <p class="text-center text-gray-500">No hay notificaciones con estos filtros.</p>
            @endforelse
        </div>


        <div class="mt-10">
            <a href="{{ route('worker.index') }}"
                class="px-6 py-2 bg-[#241178]/10 text-[#241178] hover:bg-[#241178]/20 font-semibold rounded-lg transition">
                ← Volver al panel
            </a>
        </div>
    </div>

    {{-- SCRIPT FLATPICKR --}}
    <script>
        flatpickr.localize(flatpickr.l10ns.es);

        const baseConfig = {
            dateFormat: "Y-m-d",
            allowInput: true
        };

        flatpickr("#date_from", baseConfig);
        flatpickr("#date_to", baseConfig);
    </script>

</x-layouts.app>
