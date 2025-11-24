{{-- ===========================================================
 Nombre de la clase: members.blade.php
 Descripción: Formulario para alta de trabajadores del Sindicato.
 Versión: 1.3 (Eliminado username – login solo por correo)
=========================================================== --}}

<x-layouts.app :title="__('Alta de trabajador')">

    <div class="w-full flex flex-col items-center justify-center min-h-[80vh] bg-[#FFFFFF] text-[#000000] p-6">

        <h1 class="text-3xl font-[Poppins] font-bold text-[#DC6601] mb-2">
            Alta de Trabajador
        </h1>

        <p class="text-[#241178] font-[Inter] mb-8">
            Completa los datos para registrar un nuevo trabajador del sindicato.
        </p>

        <form action="{{ route('union.members.store') }}" method="POST"
              class="w-full max-w-3xl bg-[#FFFFFF] border border-[#D9D9D9] shadow-md rounded-2xl p-8 space-y-6 font-[Inter]">

            @csrf

            {{-- Nombre --}}
            <div>
                <label for="name" class="block font-semibold text-[#272800] mb-1">
                    Nombre completo
                </label>
                <input type="text" name="name" id="name" required
                       class="w-full border border-[#D9D9D9] rounded-lg px-4 py-2 
                              focus:ring-2 focus:ring-[#DC6601] outline-none"
                       placeholder="Ejemplo: Juan Pérez"
                       value="{{ old('name') }}">
                @error('name')
                    <p class="text-[#EE0000] text-sm">{{ $message }}</p>
                @enderror
            </div>

            {{-- Correo --}}
            <div>
                <label for="email" class="block font-semibold text-[#272800] mb-1">
                    Correo electrónico
                </label>
                <input type="email" name="email" id="email" required
                       class="w-full border border-[#D9D9D9] rounded-lg px-4 py-2
                              focus:ring-2 focus:ring-[#DC6601] outline-none"
                       placeholder="correo@ejemplo.com"
                       value="{{ old('email') }}">
                @error('email')
                    <p class="text-[#EE0000] text-sm">{{ $message }}</p>
                @enderror
            </div>

            {{-- Datos del trabajador --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

                {{-- CURP --}}
                <div>
                    <label for="curp" class="block font-semibold text-[#272800] mb-1">CURP</label>
                    <input type="text" name="curp" id="curp"
                           oninput="this.value = this.value.toUpperCase()"
                           class="w-full border border-[#D9D9D9] rounded-lg px-4 py-2 uppercase 
                                  focus:ring-2 focus:ring-[#DC6601] outline-none"
                           placeholder="PEGA850101HDFRRN09"
                           value="{{ old('curp') }}">
                    @error('curp')
                        <p class="text-[#EE0000] text-sm">{{ $message }}</p>
                    @enderror
                </div>

                {{-- RFC --}}
                <div>
                    <label for="rfc" class="block font-semibold text-[#272800] mb-1">RFC</label>
                    <input type="text" name="rfc" id="rfc"
                           oninput="this.value = this.value.toUpperCase()"
                           class="w-full border border-[#D9D9D9] rounded-lg px-4 py-2 uppercase
                                  focus:ring-2 focus:ring-[#DC6601] outline-none"
                           placeholder="PEGA850101XXX"
                           value="{{ old('rfc') }}">
                    @error('rfc')
                        <p class="text-[#EE0000] text-sm">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Género --}}
                <div>
                    <label for="gender" class="block font-semibold text-[#272800] mb-1">Sexo / Género</label>
                    <select name="gender" id="gender"
                            class="w-full border border-[#D9D9D9] rounded-lg px-4 py-2 
                                   focus:ring-2 focus:ring-[#DC6601] outline-none">

                        <option value="">Selecciona</option>
                        <option value="H"  {{ old('gender') == 'H' ? 'selected' : '' }}>Hombre</option>
                        <option value="M"  {{ old('gender') == 'M' ? 'selected' : '' }}>Mujer</option>
                        <option value="ND" {{ old('gender') == 'ND' ? 'selected' : '' }}>No definido</option>
                        <option value="X"  {{ old('gender') == 'X' ? 'selected' : '' }}>Prefiero no decirlo</option>
                    </select>
                    @error('gender')
                        <p class="text-[#EE0000] text-sm">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Clave presupuestal --}}
                <div>
                    <label for="budget_key" class="block font-semibold text-[#272800] mb-1">Clave presupuestal</label>
                    <input type="text" name="budget_key" id="budget_key"
                           class="w-full border border-[#D9D9D9] rounded-lg px-4 py-2 
                                  focus:ring-2 focus:ring-[#DC6601] outline-none"
                           placeholder="Ejemplo: 123-ABC"
                           value="{{ old('budget_key') }}">
                    @error('budget_key')
                        <p class="text-[#EE0000] text-sm">{{ $message }}</p>
                    @enderror
                </div>

            </div>

            {{-- Botones --}}
            <div class="flex flex-col sm:flex-row justify-end gap-4 mt-8">
                <a href="{{ route('union.members.index') }}"
                    class="px-6 py-2 bg-[#241178]/10 text-[#241178] 
                           hover:bg-[#241178]/20 font-semibold rounded-lg transition text-center">
                    Cancelar
                </a>

                <button type="submit"
                    class="px-6 py-2 bg-[#DC6601] hover:bg-[#EE0000] text-white 
                           font-semibold rounded-lg flex items-center gap-2 justify-center transition">
                    <x-heroicon-o-user-plus class="w-5 h-5" />
                    Guardar trabajador
                </button>
            </div>

        </form>
    </div>

</x-layouts.app>
