{{-- ===========================================================
 Nombre de la vista: notifications.blade.php
 Módulo: Panel del Trabajador
 Descripción: Muestra las notificaciones enviadas por el sindicato.
 =========================================================== --}}

<x-layouts.app :title="__('Notificaciones')">
    <div class="w-full flex flex-col items-center min-h-[80vh] bg-[#FFFFFF] text-[#000000] p-6">
        <h1 class="text-3xl font-[Poppins] font-bold text-[#DC6601] mb-4">
            Notificaciones
        </h1>

        <div class="w-full max-w-3xl space-y-4">
            @forelse ($notifications as $n)
                <div
                    class="border border-[#D9D9D9] rounded-2xl shadow-sm p-5 bg-white 
                            {{ $n->estado === 'no_leida' ? 'border-[#DC6601]/50 bg-[#FFF6EE]' : '' }}">
                    <div class="flex justify-between items-center">
                        <h2 class="text-lg font-[Poppins] font-semibold text-[#241178]">
                            {{ $n->titulo }}
                        </h2>
                        <form method="POST" action="{{ route('worker.notifications.read', $n->id) }}">
                            @csrf
                            @if ($n->estado === 'no_leida')
                                <button type="submit" class="text-sm text-[#DC6601] hover:underline font-semibold">
                                    Marcar como leída
                                </button>
                            @endif
                        </form>
                    </div>

                    <p class="text-gray-700 text-sm mt-2 mb-2">{{ $n->mensaje }}</p>

                    <p class="text-xs text-gray-500 text-right">
                        {{ $n->created_at->format('d/m/Y H:i') }}
                    </p>
                </div>
            @empty
                <p class="text-center text-gray-500">No tienes notificaciones.</p>
            @endforelse
        </div>

        <div class="mt-10">
            <a href="{{ route('worker.index') }}"
                class="px-6 py-2 bg-[#241178]/10 text-[#241178] hover:bg-[#241178]/20 font-semibold rounded-lg transition">
                ← Volver al panel
            </a>
        </div>
    </div>
</x-layouts.app>
