{{-- 
* Nombre de la vista           : reminders.blade.php
* Descripción de la vista      : Configuración de recordatorios automáticos del sistema.
* Fecha de creación            : 25/11/2025
* Elaboró                      : Iker Piza
* Fecha de liberación          : 25/11/2025
* Autorizó                     : Líder Técnico
* Versión                      : 1.2
* Fecha de mantenimiento       : 26/11/2025
* Folio de mantenimiento       : N/A
* Tipo de mantenimiento        : Correctivo y perfectivo
* Descripción del mantenimiento: Se elimina tabla de reglas, 
                                 se integra SweetAlert2 global y se mejoran estilos.
* Responsable                  : Iker Piza
* Revisor                      : QA SINDISOFT
--}}

<x-layouts.app :title="__('Configuración de Recordatorios')">

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    @if (session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: '¡Actualizado!',
                text: "{{ session('success') }}",
                confirmButtonColor: '#DE6601',
            });
        </script>
    @endif

    @if (session('error'))
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: "{{ session('error') }}",
                confirmButtonColor: '#DE6601',
            });
        </script>
    @endif


    <div class="flex flex-col gap-6">

        <div class="flex justify-between items-center">
            <h1 class="text-3xl font-bold text-[#DE6601]">Configuración de Recordatorios</h1>
        </div>

        <form method="POST" action="{{ route('admin.reminders.update') }}"
            class="bg-white p-6 border border-[#D9D9D9] rounded-xl shadow-md flex flex-col gap-6">
            @csrf

            <div class="flex flex-col gap-2 w-full sm:w-60">
                <label class="text-sm font-semibold text-[#241178]">Activar recordatorios automáticos</label>
                <select name="enabled"
                    class="border border-[#D9D9D9] rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-[#DE6601] outline-none">
                    <option value="1" {{ old('enabled', $config->enabled ?? 0) == 1 ? 'selected' : '' }}>Activados</option>
                    <option value="0" {{ old('enabled', $config->enabled ?? 0) == 0 ? 'selected' : '' }}>Desactivados</option>
                </select>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="flex flex-col">
                    <label class="text-sm font-semibold text-[#241178]">Enviar por</label>
                    <select name="channel"
                        class="border border-[#D9D9D9] rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-[#DE6601] outline-none">
                        <option value="email"  {{ old('channel', $config->channel ?? '') == 'email' ? 'selected' : '' }}>
                            Correo electrónico
                        </option>
                        <option value="inapp"  {{ old('channel', $config->channel ?? '') == 'inapp' ? 'selected' : '' }}>
                            Notificación interna
                        </option>
                    </select>
                </div>

                <div class="flex flex-col">
                    <label class="text-sm font-semibold text-[#241178]">Intervalo entre recordatorios (días)</label>
                    <input type="number" min="1" name="interval_days"
                        value="{{ old('interval_days', $config->interval_days ?? '') }}"
                        class="border border-[#D9D9D9] rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-[#DE6601] outline-none"
                        placeholder="Ej. 2">
                </div>
            </div>

            <div class="flex flex-col">
                <label class="text-sm font-semibold text-[#241178]">Mensaje base del recordatorio</label>
                <textarea name="base_message" rows="4"
                    class="border border-[#D9D9D9] rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-[#DE6601] outline-none">{{ old('base_message', $config->base_message ?? '') }}</textarea>
            </div>
            <div class="flex gap-4 mt-4">

                <flux:button icon="check-circle" icon-variant="outline" type="submit" variant="primary"
                    class="!bg-blue-600 hover:!bg-blue-700 !text-white font-semibold rounded-lg transition">
                    Guardar cambios
                </flux:button>

                <flux:button
                    icon="x-circle"
                    icon-variant="outline"
                    variant="ghost"
                    :href="route('dashboard')"
                    class="!bg-zinc-200 hover:!bg-zinc-300 !text-zinc-700 px-6 py-2 font-semibold rounded-lg transition"
                >
                    Cancelar
                </flux:button>

            </div>
        </form>

    </div>

</x-layouts.app>
