{{-- ===========================================================
 Nombre de la clase: members.blade.php
 Descripción: Vista para dar de alta trabajadores por el usuario Sindicato.
 Fecha de creación: 02/11/2025
 Elaboró: Iker Piza
 Fecha de liberación: 02/11/2025
 Autorizó: Líder Técnico
 Versión: 1.1
 Tipo de mantenimiento: Homogeneización visual.
 Descripción del mantenimiento: Se adaptó el diseño al formulario de Alta de Usuario (Administrador)
 manteniendo la paleta, fuentes, botones e iconografía institucional según el Manual PRO-Laravel V3.2.
 Responsable: Iker Piza
 Revisor: QA SINDISOFT
=========================================================== --}}

<x-layouts.app :title="__('Alta de trabajador')">

    <div class="w-full flex flex-col items-center justify-center min-h-[80vh] bg-[#FFFFFF] text-[#000000] p-6">

        <!-- 🔸 Título -->
        <h1 class="text-3xl font-[Poppins] font-bold text-[#DC6601] mb-2">
            Alta de Trabajador
        </h1>
        <p class="text-[#241178] font-[Inter] mb-8">
            Completa los datos para registrar un nuevo trabajador del sindicato.
        </p>

        <!-- 📋 Formulario -->
        <form action="{{ route('union.members.store') }}" method="POST"
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

            <!-- Datos del trabajador -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="curp" class="block font-semibold text-[#272800] mb-1">CURP</label>
                    <input type="text" name="curp" id="curp"
                        class="w-full border border-[#D9D9D9] rounded-lg px-4 py-2 focus:ring-2 focus:ring-[#DC6601] outline-none"
                        placeholder="Ejemplo: PEGA850101HDFRRN09" value="{{ old('curp') }}">
                    @error('curp')
                        <p class="text-[#EE0000] text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="rfc" class="block font-semibold text-[#272800] mb-1">RFC</label>
                    <input type="text" name="rfc" id="rfc"
                        class="w-full border border-[#D9D9D9] rounded-lg px-4 py-2 focus:ring-2 focus:ring-[#DC6601] outline-none"
                        placeholder="Ejemplo: PEGA850101XXX" value="{{ old('rfc') }}">
                    @error('rfc')
                        <p class="text-[#EE0000] text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="sexo" class="block font-semibold text-[#272800] mb-1">Sexo</label>
                    <select name="sexo" id="sexo"
                        class="w-full border border-[#D9D9D9] rounded-lg px-4 py-2 focus:ring-2 focus:ring-[#DC6601] outline-none">
                        <option value="">Selecciona</option>
                        <option value="H" {{ old('sexo') == 'H' ? 'selected' : '' }}>Hombre</option>
                        <option value="M" {{ old('sexo') == 'M' ? 'selected' : '' }}>Mujer</option>
                    </select>
                    @error('sexo')
                        <p class="text-[#EE0000] text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="clave_presupuestal" class="block font-semibold text-[#272800] mb-1">Clave
                        presupuestal</label>
                    <input type="text" name="clave_presupuestal" id="clave_presupuestal"
                        class="w-full border border-[#D9D9D9] rounded-lg px-4 py-2 focus:ring-2 focus:ring-[#DC6601] outline-none"
                        placeholder="Ejemplo: 123-ABC" value="{{ old('clave_presupuestal') }}">
                    @error('clave_presupuestal')
                        <p class="text-[#EE0000] text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Botones -->
            <div class="flex flex-col sm:flex-row justify-end gap-4 mt-8">
                <a href="{{ route('union.members.index') }}"
                    class="px-6 py-2 bg-[#241178]/10 text-[#241178] hover:bg-[#241178]/20 font-semibold rounded-lg transition text-center">
                    Cancelar
                </a>
                <button type="submit"
                    class="px-6 py-2 bg-[#DC6601] hover:bg-[#EE0000] text-white font-semibold rounded-lg transition flex items-center gap-2 justify-center">
                    <x-heroicon-o-user-plus class="w-5 h-5" />
                    Guardar trabajador
                </button>
            </div>
        </form>
    </div>

</x-layouts.app>
