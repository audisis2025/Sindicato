{{-- ===========================================================
 Nombre de la clase: edit.blade.php
 Descripción: Vista para editar usuarios existentes en el sistema SINDISOFT.
 Fecha de creación: 02/11/2025
 Elaboró: Iker Piza
 Fecha de liberación: 02/11/2025
 Autorizó: Líder Técnico
 Versión: 1.0
 Tipo de mantenimiento: Creación.
 Descripción del mantenimiento: Implementación del formulario de edición de
 usuarios con el mismo diseño visual de la vista de alta.
 Responsable: Iker Piza
 Revisor: QA SINDISOFT
=========================================================== --}}

<x-layouts.app :title="__('Editar usuario')">
    <div class="w-full flex flex-col items-center justify-center min-h-[80vh] bg-[#FFFFFF] text-[#000000] p-6">
        <h1 class="text-3xl font-[Poppins] font-bold text-[#DC6601] mb-2">
            Editar Usuario
        </h1>
        <p class="text-[#241178] font-[Inter] mb-8">
            Modifica los datos del usuario seleccionado.
        </p>

        <!-- 📋 Formulario -->
        <form action="{{ route('users.update', $user->id) }}" method="POST"
              class="w-full max-w-2xl bg-[#FFFFFF] border border-[#D9D9D9] shadow-md rounded-2xl p-8 space-y-6 font-[Inter]">
            @csrf
            @method('PUT')

            <!-- Nombre -->
            <div>
                <label for="name" class="block font-semibold text-[#272800] mb-1">Nombre completo</label>
                <input type="text" name="name" id="name" required
                       class="w-full border border-[#D9D9D9] rounded-lg px-4 py-2 focus:ring-2 focus:ring-[#DC6601] outline-none"
                       value="{{ old('name', $user->name) }}">
                @error('name')
                    <p class="text-[#EE0000] text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Correo electrónico -->
            <div>
                <label for="email" class="block font-semibold text-[#272800] mb-1">Correo electrónico</label>
                <input type="email" name="email" id="email"
                       class="w-full border border-[#D9D9D9] rounded-lg px-4 py-2 focus:ring-2 focus:ring-[#DC6601] outline-none"
                       value="{{ old('email', $user->email) }}">
                @error('email')
                    <p class="text-[#EE0000] text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Rol -->
            <div>
                <label for="rol" class="block font-semibold text-[#272800] mb-1">Rol del usuario</label>
                <select name="rol" id="rol" required
                        class="w-full border border-[#D9D9D9] rounded-lg px-4 py-2 focus:ring-2 focus:ring-[#DC6601] outline-none">
                    <option value="">Selecciona una opción</option>
                    <option value="sindicato" {{ old('rol', $user->rol) == 'sindicato' ? 'selected' : '' }}>Usuario Sindicato</option>
                    <option value="trabajador" {{ old('rol', $user->rol) == 'trabajador' ? 'selected' : '' }}>Usuario Trabajador</option>
                </select>
                @error('rol')
                    <p class="text-[#EE0000] text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Botones -->
            <div class="flex flex-col sm:flex-row justify-end gap-4 mt-8">
                <a href="{{ route('users.index') }}"
                   class="px-6 py-2 bg-[#241178]/10 text-[#241178] hover:bg-[#241178]/20 font-semibold rounded-lg transition text-center">
                    Cancelar
                </a>
                <button type="submit"
                        class="px-6 py-2 bg-[#DC6601] hover:bg-[#EE0000] text-white font-semibold rounded-lg transition">
                    Guardar cambios
                </button>
            </div>
        </form>
    </div>
</x-layouts.app>
