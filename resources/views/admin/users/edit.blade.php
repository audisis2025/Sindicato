{{-- 
* Nombre de la vista           : edit.blade.php
* Descripción de la vista      : Formulario para edición de usuarios del sistema SINDISOFT.
* Fecha de creación            : 26/11/2025
* Elaboró                      : Iker Piza
* Fecha de liberación          : 26/11/2025
* Autorizó                     : Líder Técnico
* Versión                      : 1.0
* Fecha de mantenimiento       : N/A
* Folio de mantenimiento       : N/A
* Tipo de mantenimiento        : Correctivo y perfectivo
* Descripción del mantenimiento: Homogeneización con estilos institucionales, actualización de selects y reorganización de campos.
* Responsable                  : Iker Piza
* Revisor                      : QA SINDISOFT
--}}

<x-layouts.app :title="__('Editar usuario')">

    <div class="w-full flex flex-col items-center justify-center min-h-[80vh] bg-white text-black p-6">

        <h1 class="text-3xl font-[Poppins] font-bold text-[#DE6601] mb-2">
            Editar Usuario
        </h1>

        <p class="text-[#241178] font-[Inter] mb-8">
            Modifica los datos del usuario seleccionado.
        </p>

        <form action="{{ route('users.update', $user->id) }}" method="POST"
            class="w-full max-w-3xl bg-white border border-[#D9D9D9] shadow-md rounded-2xl p-8 space-y-8 font-[Inter]">

            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <flux:input name="name" :label="__('Nombre completo')" type="text" required
                    value="{{ old('name', $user->name) }}" />

                <flux:input name="email" :label="__('Correo electrónico')" type="email" required
                    value="{{ old('email', $user->email) }}" />
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <flux:select name="role" :label="__('Rol del usuario')" required>
                    <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>Administrador
                    </option>
                    <option value="union" {{ old('role', $user->role) == 'union' ? 'selected' : '' }}>Usuario Sindicato
                    </option>
                    <option value="worker" {{ old('role', $user->role) == 'worker' ? 'selected' : '' }}>Usuario
                        Trabajador</option>
                </flux:select>

                <flux:select name="gender" :label="__('Sexo / Género')">
                    <option value="">Selecciona</option>
                    <option value="H" {{ old('gender', $user->gender) == 'H' ? 'selected' : '' }}>Hombre</option>
                    <option value="M" {{ old('gender', $user->gender) == 'M' ? 'selected' : '' }}>Mujer</option>
                    <option value="ND" {{ old('gender', $user->gender) == 'ND' ? 'selected' : '' }}>No definido
                    </option>
                    <option value="X" {{ old('gender', $user->gender) == 'X' ? 'selected' : '' }}>Prefiero no
                        decirlo</option>
                </flux:select>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <flux:input name="curp" :label="__('CURP')" type="text"
                    value="{{ old('curp', $user->curp) }}" />

                <flux:input name="rfc" :label="__('RFC')" type="text"
                    value="{{ old('rfc', $user->rfc) }}" />
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <flux:input name="budget_key" :label="__('Clave presupuestal')" type="text"
                    value="{{ old('budget_key', $user->budget_key) }}" />

                <flux:select name="active" :label="__('Estado')" required>
                    <option value="1" {{ old('active', $user->active) == 1 ? 'selected' : '' }}>Activo</option>
                    <option value="0" {{ old('active', $user->active) == 0 ? 'selected' : '' }}>Inactivo</option>
                </flux:select>
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

                <flux:button icon="check-circle" icon-variant="outline" type="submit" variant="primary"
                    class="!bg-blue-600 hover:!bg-blue-700 !text-white font-semibold rounded-lg transition">
                    Guardar cambios
                </flux:button>

            </div>

        </form>

    </div>

</x-layouts.app>
