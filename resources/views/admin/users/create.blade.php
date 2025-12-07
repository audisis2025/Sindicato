{{-- 
* Nombre de la vista           : create.blade.php
* Descripción de la vista      : Formulario para el alta de usuarios en el sistema SINDISOFT.
* Fecha de creación            : 25/11/2025
* Elaboró                      : Iker Piza
* Fecha de liberación          : 25/11/2025
* Autorizó                     : Líder Técnico
* Versión                      : 1.3
* Fecha de mantenimiento       : 26/11/2025
* Folio de mantenimiento       : N/A
* Tipo de mantenimiento        : Correctivo y perfectivo
* Descripción del mantenimiento: Optimización de distribución de campos y estandarización Flux UI.
* Responsable                  : Iker Piza
* Revisor                      : QA SINDISOFT
--}}

<x-layouts.app :title="__('Alta de usuario')">

    <div class="w-full flex flex-col items-center justify-center min-h-[80vh] bg-white text-black p-6">

        <h1 class="text-3xl font-bold text-[#DE6601] mb-2">
            Alta de Usuario
        </h1>

        <p class="text-[#272800] mb-6">
            Completa los datos para registrar un nuevo usuario en el sistema.
        </p>

        <form action="{{ route('users.store') }}" method="POST"
            class="w-full max-w-3xl bg-white border border-[#D9D9D9] shadow-md rounded-2xl p-8 space-y-8">

            @csrf

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <flux:input
                    name="name"
                    :label="__('Nombre completo')"
                    type="text"
                    required
                    value="{{ old('name') }}"
                    placeholder="Juan Pérez"
                />

                <flux:input
                    name="email"
                    :label="__('Correo electrónico')"
                    type="email"
                    required
                    value="{{ old('email') }}"
                    placeholder="correo@ejemplo.com"
                />
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <flux:input
                    name="password"
                    :label="__('Contraseña')"
                    type="password"
                    required
                    placeholder="********"
                    viewable
                />

                <flux:select
                    name="role"
                    :label="__('Rol del usuario')"
                    required
                >
                    <option value="">Selecciona</option>
                    <option value="union"  {{ old('role') == 'union'  ? 'selected' : '' }}>Usuario Sindicato</option>
                    <option value="worker" {{ old('role') == 'worker' ? 'selected' : '' }}>Usuario Trabajador</option>
                </flux:select>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <flux:select
                    name="gender"
                    :label="__('Sexo / Género')"
                >
                    <option value="">Selecciona</option>
                    <option value="H"  {{ old('gender') == 'H'  ? 'selected' : '' }}>Hombre</option>
                    <option value="M"  {{ old('gender') == 'M'  ? 'selected' : '' }}>Mujer</option>
                    <option value="ND" {{ old('gender') == 'ND' ? 'selected' : '' }}>No definido</option>
                    <option value="X"  {{ old('gender') == 'X'  ? 'selected' : '' }}>Prefiero no decirlo</option>
                </flux:select>

                <flux:input
                    name="curp"
                    :label="__('CURP')"
                    type="text"
                    value="{{ old('curp') }}"
                    placeholder="PEGA850101HDFRRN09"
                />
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <flux:input
                    name="rfc"
                    :label="__('RFC')"
                    type="text"
                    value="{{ old('rfc') }}"
                    placeholder="PEGA850101XXX"
                />

                <flux:input
                    name="budget_key"
                    :label="__('Clave presupuestal')"
                    type="text"
                    value="{{ old('budget_key') }}"
                    placeholder="123-ABC"
                />
            </div>

            <div class="flex flex-col sm:flex-row justify-end gap-4 mt-4">

                <flux:button
                    icon="x-circle"
                    icon-variant="outline"
                    variant="ghost"
                    :href="route('users.index')"
                    class="!bg-zinc-200 hover:!bg-zinc-300 !text-zinc-700 px-6 py-2 font-semibold rounded-lg transition"
                >
                    Cancelar
                </flux:button>

                <flux:button
                    icon="check-circle"
                    icon-variant="outline"
                    variant="primary"
                    type="submit"
                    class="!bg-blue-600 hover:!bg-blue-700 !text-white font-semibold rounded-lg transition"
                >
                    Guardar usuario
                </flux:button>

            </div>

        </form>
    </div>

</x-layouts.app>
