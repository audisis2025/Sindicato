{{-- ===========================================================
 Nombre de la clase: password.blade.php
 Descripción: Vista para que el trabajador cambie su contraseña en el sistema SINDISOFT.
 Fecha de creación: 02/11/2025
 Elaboró: Iker Piza
 Fecha de liberación: 02/11/2025
 Autorizó: Líder Técnico
 Versión: 1.0
 Tipo de mantenimiento: Creación.
 Descripción del mantenimiento: Implementa formulario institucional Flux UI para actualización de contraseña según Manual PRO-Laravel V3.2.
 Responsable: Iker Piza
 Revisor: QA SINDISOFT
=========================================================== --}}

<x-layouts.app :title="__('Cambio de contraseña')">
    <div class="flex flex-col items-center justify-center min-h-[80vh] bg-white dark:bg-zinc-900 p-6">

        <h1 class="text-3xl font-[Poppins] font-bold text-[#DC6601] mb-2">
            Cambio de contraseña
        </h1>
        <p class="text-[#241178] font-[Inter] mb-8">
            Actualiza tu contraseña para mantener la seguridad de tu cuenta.
        </p>

        <form method="POST" action="{{ route('user-password.update') }}"
            class="w-full max-w-md bg-white border border-zinc-200 dark:border-zinc-700 shadow-md rounded-2xl p-6 space-y-5 font-[Inter]">
            @csrf
            @method('PUT')

            <!-- Contraseña actual -->
            <div>
                <label for="current_password" class="block font-semibold text-[#272800] mb-1">
                    Contraseña actual
                </label>
                <input id="current_password" type="password" name="current_password" required
                    class="w-full border border-[#D9D9D9] rounded-lg px-4 py-2 focus:ring-2 focus:ring-[#DC6601] outline-none">
                @error('current_password')
                    <p class="text-[#EE0000] text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Nueva contraseña -->
            <div>
                <label for="password" class="block font-semibold text-[#272800] mb-1">
                    Nueva contraseña
                </label>
                <input id="password" type="password" name="password" required
                    class="w-full border border-[#D9D9D9] rounded-lg px-4 py-2 focus:ring-2 focus:ring-[#DC6601] outline-none">
                @error('password')
                    <p class="text-[#EE0000] text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Confirmación -->
            <div>
                <label for="password_confirmation" class="block font-semibold text-[#272800] mb-1">
                    Confirmar contraseña
                </label>
                <input id="password_confirmation" type="password" name="password_confirmation" required
                    class="w-full border border-[#D9D9D9] rounded-lg px-4 py-2 focus:ring-2 focus:ring-[#DC6601] outline-none">
            </div>

            <div class="flex justify-end mt-6">
                <flux:button variant="primary" icon="check-circle" type="submit">
                    Guardar cambios
                </flux:button>
            </div>
        </form>
    </div>
</x-layouts.app>
