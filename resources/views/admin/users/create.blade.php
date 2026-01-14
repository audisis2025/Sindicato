{{-- 
* Nombre de la vista           : create.blade.php
* Descripción de la vista      : Formulario para el alta de usuarios en el sistema SINDISOFT.
* Fecha de creación            : 25/11/2025
* Elaboró                      : Iker Piza
* Fecha de liberación          : 25/11/2025
* Autorizó                     : Líder Técnico
* Versión                      : 1.4
* Fecha de mantenimiento       : 13/01/2026
* Folio de mantenimiento       : 
* Tipo de mantenimiento        : Correctivo y perfectivo
* Descripción del mantenimiento: Validaciones por estándar y homologación Flux UI (inputs, selects, botones).
* Responsable                  : Iker Piza
* Revisor                      : 
--}}

<x-layouts.app :title="__('Alta de usuario')">

    <div class="w-full flex flex-col items-center justify-center min-h-[80vh] bg-white text-black p-6">

        <div class="w-full max-w-3xl flex justify-end mb-4">
            <flux:button icon="arrow-long-left" icon-variant="outline" variant="ghost" :href="route('users.index')">
                Regresar
            </flux:button>
        </div>

        <h1 class="text-3xl font-bold text-[#DE6601] mb-2">
            Alta de Usuario
        </h1>

        <p class="text-[#272800] mb-6">
            Completa los datos para registrar un nuevo usuario en el sistema.
        </p>

        <form action="{{ route('users.store') }}" method="POST"
            class="w-full max-w-3xl bg-white border border-[#D9D9D9] shadow-md rounded-2xl p-8 space-y-8" novalidate>
            @csrf

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">

                <div class="space-y-1">
                    <flux:input name="name" :label="__('Nombre completo')" type="text" required
                        autocomplete="name" maxlength="120" value="{{ old('name') }}" placeholder="Juan Pérez" />
                </div>

                <div class="space-y-1">
                    <flux:input name="email" :label="__('Correo electrónico')" type="email" required
                        autocomplete="email" maxlength="120" value="{{ old('email') }}"
                        placeholder="correo@ejemplo.com" />
                </div>

            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">

                <div class="space-y-1">
                    <flux:input name="password" :label="__('Contraseña')" type="password" required
                        autocomplete="new-password" placeholder="********" viewable />
                </div>

                <div class="space-y-1">
                    <flux:select name="role" :label="__('Rol del usuario')" required>
                        <option value="">Selecciona</option>
                        <option value="union" {{ old('role') == 'union' ? 'selected' : '' }}>Usuario Sindicato</option>
                        <option value="worker" {{ old('role') == 'worker' ? 'selected' : '' }}>Usuario Trabajador
                        </option>
                    </flux:select>
                </div>

            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">

                <div class="space-y-1">
                    <flux:select name="gender" :label="__('Sexo / Género')" required>
                        <option value="">Selecciona</option>
                        <option value="H" {{ old('gender') == 'H' ? 'selected' : '' }}>Hombre</option>
                        <option value="M" {{ old('gender') == 'M' ? 'selected' : '' }}>Mujer</option>
                        <option value="ND" {{ old('gender') == 'ND' ? 'selected' : '' }}>No definido</option>
                        <option value="X" {{ old('gender') == 'X' ? 'selected' : '' }}>Prefiero no decirlo
                        </option>
                    </flux:select>
                </div>

                <div class="space-y-1">
                    <flux:input name="curp" :label="__('CURP')" type="text" required maxlength="18"
                        value="{{ old('curp') }}" placeholder="PEGA850101HDFRRN09" x-data
                        x-on:input="$el.value = $el.value.toUpperCase()" />
                </div>

            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">

                <div class="space-y-1">
                    <flux:input name="rfc" :label="__('RFC')" type="text" required maxlength="13"
                        value="{{ old('rfc') }}" placeholder="PEGA850101XXX" x-data
                        x-on:input="$el.value = $el.value.toUpperCase()" />
                </div>

                <div class="space-y-1">
                    <flux:input name="budget_key" :label="__('Clave presupuestal')" type="text" required
                        maxlength="30" value="{{ old('budget_key') }}" placeholder="123-ABC" />
                </div>

            </div>

            <div class="flex flex-col sm:flex-row justify-end gap-4 mt-4">

                <flux:button icon="x-circle" icon-variant="outline" variant="primary" :href="route('users.index')"
                    class="!bg-red-600 hover:!bg-red-700 !text-white">
                    Cancelar
                </flux:button>

                <flux:button icon="check-circle" icon-variant="outline" variant="primary" type="submit"
                    class="!bg-green-600 hover:!bg-green-700 !text-white">
                    Guardar
                </flux:button>

            </div>

        </form>
    </div>

</x-layouts.app>
