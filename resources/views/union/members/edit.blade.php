{{-- ===========================================================
 Nombre de la clase: members-edit.blade.php
 Descripción: Vista para editar datos de trabajador.
 Versión: 1.3 (Eliminado username – login solo por correo)
=========================================================== --}}

<x-layouts.app :title="__('Editar trabajador')">

    <div class="w-full flex flex-col items-center justify-start min-h-[80vh] bg-[#FFFFFF] text-[#000000] p-6">

        <!-- Encabezado -->
        <div class="w-full max-w-3xl flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6">
            <div>
                <h1 class="text-3xl font-[Poppins] font-bold text-[#DC6601] mb-1">Editar Trabajador</h1>
                <p class="text-[#241178] font-[Inter] text-sm">
                    Actualiza los datos del trabajador seleccionado.
                </p>
            </div>

            <a href="{{ route('union.members.index') }}"
                class="px-4 py-2 bg-[#241178]/10 hover:bg-[#241178]/20 text-[#241178] 
                       font-semibold rounded-lg transition">
                ⬅ Volver
            </a>
        </div>

        <form action="{{ route('union.members.update', $worker->id) }}" method="POST"
              class="w-full max-w-3xl bg-[#FFFFFF] border border-[#D9D9D9] shadow-md rounded-2xl p-8
                     space-y-6 font-[Inter]">

            @csrf
            @method('PUT')

            {{-- Nombre --}}
            <div>
                <label class="block font-semibold text-[#272800] mb-1" for="name">Nombre completo</label>
                <input type="text" name="name" id="name" required
                       class="w-full border border-[#D9D9D9] rounded-lg px-4 py-2 focus:ring-2 focus:ring-[#DC6601] outline-none"
                       value="{{ old('name', $worker->name) }}">
                @error('name')
                    <p class="text-[#EE0000] text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Email --}}
            <div>
                <label for="email" class="block font-semibold text-[#272800] mb-1">Correo electrónico</label>
                <input type="email" name="email" id="email" required
                       class="w-full border border-[#D9D9D9] rounded-lg px-4 py-2 focus:ring-2 focus:ring-[#DC6601] outline-none"
                       value="{{ old('email', $worker->email) }}"
                       placeholder="correo@ejemplo.com">
                @error('email')
                    <p class="text-[#EE0000] text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Datos adicionales --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

                {{-- CURP --}}
                <div>
                    <label class="block font-semibold text-[#272800] mb-1" for="curp">CURP</label>
                    <input type="text" id="curp" name="curp"
                           oninput="this.value = this.value.toUpperCase()"
                           value="{{ old('curp', $worker->curp) }}"
                           class="w-full uppercase border border-[#D9D9D9] rounded-lg px-4 py-2 
                                  focus:ring-2 focus:ring-[#DC6601] outline-none"
                           placeholder="PEGA850101HDFRRN09">
                    @error('curp')
                        <p class="text-[#EE0000] text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- RFC --}}
                <div>
                    <label class="block font-semibold text-[#272800] mb-1" for="rfc">RFC</label>
                    <input type="text" id="rfc" name="rfc"
                           oninput="this.value = this.value.toUpperCase()"
                           value="{{ old('rfc', $worker->rfc) }}"
                           class="w-full uppercase border border-[#D9D9D9] rounded-lg px-4 py-2 
                                  focus:ring-2 focus:ring-[#DC6601] outline-none"
                           placeholder="PEGA850101XXX">
                    @error('rfc')
                        <p class="text-[#EE0000] text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Gender --}}
                <div>
                    <label class="block font-semibold text-[#272800] mb-1" for="gender">Sexo / Género</label>
                    <select name="gender" id="gender"
                        class="w-full border border-[#D9D9D9] rounded-lg px-4 py-2 
                               focus:ring-2 focus:ring-[#DC6601] outline-none">

                        <option value="">Selecciona</option>
                        <option value="H"  {{ old('gender', $worker->gender) == 'H' ? 'selected' : '' }}>Hombre</option>
                        <option value="M"  {{ old('gender', $worker->gender) == 'M' ? 'selected' : '' }}>Mujer</option>
                        <option value="ND" {{ old('gender', $worker->gender) == 'ND' ? 'selected' : '' }}>No definido</option>
                        <option value="X"  {{ old('gender', $worker->gender) == 'X' ? 'selected' : '' }}>Prefiero no decirlo</option>
                    </select>
                    @error('gender')
                        <p class="text-[#EE0000] text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Budget Key --}}
                <div>
                    <label class="block font-semibold text-[#272800] mb-1" for="budget_key">Clave presupuestal</label>
                    <input type="text" id="budget_key" name="budget_key"
                           value="{{ old('budget_key', $worker->budget_key) }}"
                           class="w-full border border-[#D9D9D9] rounded-lg px-4 py-2 
                                  focus:ring-2 focus:ring-[#DC6601] outline-none"
                           placeholder="Ejemplo: 123-ABC">
                    @error('budget_key')
                        <p class="text-[#EE0000] text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

            </div>

            {{-- Estado --}}
            <div>
                <label class="block font-semibold text-[#272800] mb-1">Estado</label>
                <select name="active"
                        class="w-full border border-[#D9D9D9] rounded-lg px-4 py-2 
                               focus:ring-2 focus:ring-[#DC6601] outline-none">
                    <option value="1" {{ $worker->active ? 'selected' : '' }}>Activo</option>
                    <option value="0" {{ !$worker->active ? 'selected' : '' }}>Inactivo</option>
                </select>
            </div>

            {{-- Botones --}}
            <div class="flex flex-col sm:flex-row justify-end gap-4 mt-8">
                <a href="{{ route('union.members.index') }}"
                    class="px-6 py-2 bg-[#241178]/10 text-[#241178] hover:bg-[#241178]/20
                           font-semibold rounded-lg transition text-center">
                    Cancelar
                </a>

                <button type="submit"
                    class="px-6 py-2 bg-[#DC6601] hover:bg-[#EE0000] text-white 
                           font-semibold rounded-lg flex items-center gap-2 justify-center transition">
                    <x-heroicon-o-user-circle class="w-5 h-5" />
                    Guardar cambios
                </button>
            </div>

        </form>

    </div>

</x-layouts.app>
