{{-- 
* Nombre de la vista          : edit.blade.php
* Descripción de la vista     : Vista para la actualización del perfil del usuario administrador,
*                               donde se permite modificar información personal y credenciales
*                               de acceso al sistema.
* Fecha de creación           : 10/11/2025
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

<x-layouts.app :title="__('Mi perfil')">

    <div class="w-full flex flex-col items-center min-h-[80vh] bg-white text-black p-6">

        <div class="w-full max-w-3xl flex justify-end mb-4">
            <flux:button icon="arrow-long-left" icon-variant="outline" variant="ghost" :href="route('dashboard')">
                Regresar
            </flux:button>
        </div>

        <h1 class="text-3xl font-bold text-[#DE6601] mb-2">
            Mi perfil
        </h1>

        <p class="text-[#272800] mb-6">
            Actualiza tu información de administrador.
        </p>

        <form action="{{ route('admin.profile.update') }}" method="POST"
            class="w-full max-w-3xl bg-white border border-[#D9D9D9] shadow-md rounded-2xl p-8 space-y-8" novalidate>
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">

                <div class="space-y-1">
                    <flux:input name="name" :label="__('Nombre completo')" type="text" required maxlength="120"
                        value="{{ old('name', $user->name) }}" placeholder="Administrador del Sistema" />
                    @error('name')
                        <p class="text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">

                <div class="space-y-1">
                    <flux:input name="email" :label="__('Correo electrónico (opcional)')" type="email"
                        maxlength="120" value="{{ old('email', $user->email) }}" placeholder="admin@sindisoft.local" />
                    @error('email')
                        <p class="text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="space-y-1">
                    <flux:select name="gender" :label="__('Sexo / Género (opcional)')">
                        <option value="">Selecciona</option>
                        <option value="H" {{ old('gender') == 'H' ? 'selected' : '' }}>Hombre</option>
                        <option value="M" {{ old('gender') == 'M' ? 'selected' : '' }}>Mujer</option>
                        <option value="ND" {{ old('gender') == 'ND' ? 'selected' : '' }}>No definido</option>
                        <option value="X" {{ old('gender') == 'X' ? 'selected' : '' }}>Prefiero no decirlo</option>
                    </flux:select>
                    @error('gender')
                        <p class="text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">

                <div class="space-y-1">
                    <flux:input name="curp" :label="__('CURP (opcional)')" type="text" maxlength="18"
                        value="{{ old('curp', $user->curp) }}" placeholder="AAAA000000HDFXXX00" x-data
                        x-on:input="$el.value = $el.value.toUpperCase()" />
                    @error('curp')
                        <p class="text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="space-y-1">
                    <flux:input name="rfc" :label="__('RFC (opcional)')" type="text" maxlength="13"
                        value="{{ old('rfc', $user->rfc) }}" placeholder="AAAA000000XXX" x-data
                        x-on:input="$el.value = $el.value.toUpperCase()" />
                    @error('rfc')
                        <p class="text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

            </div>

            <div class="space-y-1">
                <flux:input name="budget_key" :label="__('Clave presupuestal (opcional)')" type="text" maxlength="50"
                    value="{{ old('budget_key', $user->budget_key) }}" placeholder="ADMIN-000" />
                @error('budget_key')
                    <p class="text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="border-t border-zinc-200 pt-6 space-y-6">

                <h2 class="text-xl font-semibold text-[#241178]">
                    Cambiar contraseña
                </h2>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">

                    <div class="space-y-1">
                        <flux:input name="password" :label="__('Nueva contraseña (opcional)')" type="password"
                            autocomplete="new-password" placeholder="********" viewable />
                        @error('password')
                            <p class="text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="space-y-1">
                        <flux:input name="password_confirmation" :label="__('Confirmar contraseña (opcional)')"
                            type="password" autocomplete="new-password" placeholder="********" viewable />
                    </div>

                </div>

            </div>

            <div class="flex flex-col sm:flex-row justify-end gap-4 mt-4">

                <flux:button icon="x-circle" icon-variant="outline" variant="primary" :href="route('dashboard')"
                    class="!bg-red-600 hover:!bg-red-700 !text-white">
                    Cancelar
                </flux:button>

                <flux:button icon="check-circle" icon-variant="outline" variant="primary" type="submit"
                    class="!bg-green-600 hover:!bg-green-700 !text-white">
                    Guardar cambios
                </flux:button>

            </div>

        </form>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    @if (session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Guardado',
                text: @json(session('success')),
                confirmButtonText: 'Aceptar',
                confirmButtonColor: '#16a34a'
            }).then(() => {
                window.location.href = @json(route('dashboard'));
            });
        </script>
    @endif

</x-layouts.app>
