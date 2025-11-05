{{-- ===========================================================
 Nombre de la clase: create.blade.php
 Descripción: Vista para dar de alta usuarios en el sistema SINDISOFT.
 Fecha de creación: 02/11/2025
 Elaboró: Iker Piza
 Fecha de liberación: 02/11/2025
 Autorizó: Líder Técnico
 Versión: 1.0
 Tipo de mantenimiento: Creación.
 Descripción del mantenimiento: Se implementó formulario responsive con validaciones
 visuales y paleta institucional según el Manual PRO-Laravel V3.2.
 Responsable: Iker Piza
 Revisor: QA SINDISOFT
=========================================================== --}}

<x-layouts.app :title="__('Alta de usuario')">

    <div class="w-full flex flex-col items-center justify-center min-h-[80vh] bg-[#FFFFFF] text-[#000000] p-6">

        <h1 class="text-3xl font-[Poppins] font-bold text-[#DC6601] mb-2">
            Alta de Usuario
        </h1>
        <p class="text-[#241178] font-[Inter] mb-8">
            Completa los datos para registrar un nuevo usuario en el sistema.
        </p>

        <!-- 📋 Formulario -->
        <form action="{{ route('users.store') }}" method="POST"
              class="w-full max-w-2xl bg-[#FFFFFF] border border-[#D9D9D9] shadow-md rounded-2xl p-8 space-y-6 font-[Inter]">
            @csrf

            <!-- Nombre -->
            <div>
                <label for="name" class="block font-semibold text-[#272800] mb-1">Nombre completo</label>
                <input type="text" name="name" id="name" required
                       class="w-full border border-[#D9D9D9] rounded-lg px-4 py-2 focus:ring-2 focus:ring-[#DC6601] outline-none"
                       placeholder="Ejemplo: Juan Pérez Gómez" value="{{ old('name') }}">
                @error('name')
                    <p class="text-[#EE0000] text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Usuario -->
            <div>
                <label for="usuario" class="block font-semibold text-[#272800] mb-1">Usuario</label>
                <input type="text" name="usuario" id="usuario" required
                       class="w-full border border-[#D9D9D9] rounded-lg px-4 py-2 focus:ring-2 focus:ring-[#DC6601] outline-none"
                       placeholder="Ejemplo: jperez61" value="{{ old('usuario') }}">
                @error('usuario')
                    <p class="text-[#EE0000] text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Correo electrónico -->
            <div>
                <label for="email" class="block font-semibold text-[#272800] mb-1">Correo electrónico</label>
                <input type="email" name="email" id="email"
                       class="w-full border border-[#D9D9D9] rounded-lg px-4 py-2 focus:ring-2 focus:ring-[#DC6601] outline-none"
                       placeholder="correo@ejemplo.com" value="{{ old('email') }}">
                @error('email')
                    <p class="text-[#EE0000] text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Contraseña -->
            <div>
                <label for="password" class="block font-semibold text-[#272800] mb-1">Contraseña</label>
                <input type="password" name="password" id="password" required
                       class="w-full border border-[#D9D9D9] rounded-lg px-4 py-2 focus:ring-2 focus:ring-[#DC6601] outline-none"
                       placeholder="********">
                @error('password')
                    <p class="text-[#EE0000] text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Rol -->
            <div>
                <label for="rol" class="block font-semibold text-[#272800] mb-1">Rol del usuario</label>
                <select name="rol" id="rol" required
                        class="w-full border border-[#D9D9D9] rounded-lg px-4 py-2 focus:ring-2 focus:ring-[#DC6601] outline-none">
                    <option value="">Selecciona una opción</option>
                    <option value="sindicato" {{ old('rol') == 'sindicato' ? 'selected' : '' }}>Usuario Sindicato</option>
                    <option value="trabajador" {{ old('rol') == 'trabajador' ? 'selected' : '' }}>Usuario Trabajador</option>
                </select>
                @error('rol')
                    <p class="text-[#EE0000] text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Datos opcionales -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="curp" class="block font-semibold text-[#272800] mb-1">CURP</label>
                    <input type="text" name="curp" id="curp"
                           class="w-full border border-[#D9D9D9] rounded-lg px-4 py-2 focus:ring-2 focus:ring-[#DC6601] outline-none"
                           placeholder="Ejemplo: PEGA850101HDFRRN09" value="{{ old('curp') }}">
                </div>
                <div>
                    <label for="rfc" class="block font-semibold text-[#272800] mb-1">RFC</label>
                    <input type="text" name="rfc" id="rfc"
                           class="w-full border border-[#D9D9D9] rounded-lg px-4 py-2 focus:ring-2 focus:ring-[#DC6601] outline-none"
                           placeholder="Ejemplo: PEGA850101XXX" value="{{ old('rfc') }}">
                </div>
                <div>
                    <label for="sexo" class="block font-semibold text-[#272800] mb-1">Sexo</label>
                    <select name="sexo" id="sexo"
                            class="w-full border border-[#D9D9D9] rounded-lg px-4 py-2 focus:ring-2 focus:ring-[#DC6601] outline-none">
                        <option value="">Selecciona</option>
                        <option value="H" {{ old('sexo') == 'H' ? 'selected' : '' }}>Hombre</option>
                        <option value="M" {{ old('sexo') == 'M' ? 'selected' : '' }}>Mujer</option>
                    </select>
                </div>
                <div>
                    <label for="clave_presupuestal" class="block font-semibold text-[#272800] mb-1">Clave presupuestal</label>
                    <input type="text" name="clave_presupuestal" id="clave_presupuestal"
                           class="w-full border border-[#D9D9D9] rounded-lg px-4 py-2 focus:ring-2 focus:ring-[#DC6601] outline-none"
                           placeholder="Ejemplo: 123-ABC" value="{{ old('clave_presupuestal') }}">
                </div>
            </div>

            <!-- Botones -->
            <div class="flex flex-col sm:flex-row justify-end gap-4 mt-8">
                <a href="{{ route('users.index') }}"
                   class="px-6 py-2 bg-[#241178]/10 text-[#241178] hover:bg-[#241178]/20 font-semibold rounded-lg transition text-center">
                   Cancelar
                </a>
                <button type="submit"
                        class="px-6 py-2 bg-[#DC6601] hover:bg-[#EE0000] text-white font-semibold rounded-lg transition">
                    Guardar usuario
                </button>
            </div>
        </form>
    </div>
</x-layouts.app>
