{{-- 
* Nombre de la vista          : edit.blade.php
* Descripción de la vista     : Vista para la edición de usuarios del sistema por parte del administrador,
*                               permitiendo actualizar información personal, rol, estado y datos fiscales.
* Fecha de creación           : 05/11/2025
* Elaboró                     : Iker Piza
* Fecha de liberación         : 14/01/2026
* Autorizó                    : Salvador Monroy
* Versión                     : 1.0
* Fecha de mantenimiento      :
* Folio de mantenimiento      :
* Tipo de mantenimiento       :
* Descripción del mantenimiento:
* Responsable                 :
* Revisor                     :
--}}


<x-layouts.app :title="__('Editar usuario')">

    <div class="w-full flex flex-col items-center justify-center min-h-[80vh] bg-white text-black p-6">

        <div class="w-full max-w-3xl flex justify-end mb-4">
            <flux:button
                icon="arrow-long-left"
                icon-variant="outline"
                variant="ghost"
                :href="route('users.index')"
            >
                Regresar
            </flux:button>
        </div>

        <h1 class="text-3xl font-bold text-[#DE6601] mb-2">
            Editar Usuario
        </h1>

        <p class="text-[#272800] mb-6">
            Modifica los datos del usuario seleccionado.
        </p>

        <form
            action="{{ route('users.update', $user->id) }}"
            method="POST"
            class="w-full max-w-3xl bg-white border border-[#D9D9D9] shadow-md rounded-2xl p-8 space-y-8"
            novalidate
        >
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">

                <flux:input
                    name="name"
                    :label="__('Nombre completo')"
                    type="text"
                    required
                    autocomplete="name"
                    maxlength="120"
                    value="{{ old('name', $user->name) }}"
                    placeholder="Juan Pérez"
                />

                <flux:input
                    name="email"
                    :label="__('Correo electrónico')"
                    type="email"
                    required
                    autocomplete="email"
                    maxlength="120"
                    value="{{ old('email', $user->email) }}"
                    placeholder="correo@ejemplo.com"
                />

            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">

                <flux:select name="role" :label="__('Rol del usuario')" required>
                    <option value="">Selecciona</option>
                    <option value="union" {{ old('role', $user->role) == 'union' ? 'selected' : '' }}>
                        Usuario Sindicato
                    </option>
                    <option value="worker" {{ old('role', $user->role) == 'worker' ? 'selected' : '' }}>
                        Usuario Trabajador
                    </option>
                </flux:select>

                <flux:select name="gender" :label="__('Sexo / Género')" required>
                    <option value="">Selecciona</option>
                    <option value="H" {{ old('gender', $user->gender) == 'H' ? 'selected' : '' }}>Hombre</option>
                    <option value="M" {{ old('gender', $user->gender) == 'M' ? 'selected' : '' }}>Mujer</option>
                    <option value="ND" {{ old('gender', $user->gender) == 'ND' ? 'selected' : '' }}>No definido</option>
                    <option value="X" {{ old('gender', $user->gender) == 'X' ? 'selected' : '' }}>Prefiero no decirlo</option>
                </flux:select>

            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">

                <flux:input
                    name="curp"
                    :label="__('CURP')"
                    type="text"
                    required
                    maxlength="18"
                    value="{{ old('curp', $user->curp) }}"
                    placeholder="PEGA850101HDFRRN09"
                    x-data
                    x-on:input="$el.value = $el.value.toUpperCase()"
                />

                <flux:input
                    name="rfc"
                    :label="__('RFC')"
                    type="text"
                    required
                    maxlength="13"
                    value="{{ old('rfc', $user->rfc) }}"
                    placeholder="PEGA850101XXX"
                    x-data
                    x-on:input="$el.value = $el.value.toUpperCase()"
                />

            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">

                <flux:input
                    name="budget_key"
                    :label="__('Clave presupuestal')"
                    type="text"
                    required
                    maxlength="30"
                    value="{{ old('budget_key', $user->budget_key) }}"
                    placeholder="123-ABC"
                />

                <flux:select name="active" :label="__('Estado')" required>
                    <option value="">Selecciona</option>
                    <option value="1" {{ old('active', $user->active) == 1 ? 'selected' : '' }}>Activo</option>
                    <option value="0" {{ old('active', $user->active) == 0 ? 'selected' : '' }}>Inactivo</option>
                </flux:select>

            </div>

            <div class="flex flex-col sm:flex-row justify-end gap-4 mt-4">

                <flux:button
                    icon="x-circle"
                    icon-variant="outline"
                    variant="primary"
                    :href="route('users.index')"
                    class="!bg-red-600 hover:!bg-red-700 !text-white"
                >
                    Cancelar
                </flux:button>

                <flux:button
                    icon="check-circle"
                    icon-variant="outline"
                    type="submit"
                    variant="primary"
                    class="!bg-green-600 hover:!bg-green-700 !text-white"
                >
                    Guardar cambios
                </flux:button>

            </div>

        </form>

    </div>

</x-layouts.app>
