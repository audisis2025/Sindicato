{{-- ===========================================================
 Nombre de la clase: members-edit.blade.php
 Descripción: Vista para editar los datos de un trabajador por el Sindicato.
 Fecha: 04/11/2025
 Elaboró: Iker Piza
 Fecha de liberación: 04/11/2025
 Autorizó: Líder Técnico
 Versión: 1.0
 Tipo de mantenimiento: Creación.
 Descripción del mantenimiento: Implementa edición de trabajadores (RF02-RF13)
 conforme al Manual PRO-Laravel V3.2.
 Responsable: Iker Piza
 Revisor: QA SINDISOFT
=========================================================== --}}

<x-layouts.app :title="__('Editar trabajador')">

    <div class="w-full flex flex-col items-center justify-start min-h-[80vh] bg-[#FFFFFF] text-[#000000] p-6">

        <!-- 🔸 Encabezado -->
        <div class="w-full max-w-3xl flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6">
            <div>
                <h1 class="text-3xl font-[Poppins] font-bold text-[#DC6601] mb-1">Editar Trabajador</h1>
                <p class="text-[#241178] font-[Inter] text-sm">Actualiza los datos del trabajador seleccionado.</p>
            </div>

            <a href="{{ route('union.members.index') }}"
               class="px-4 py-2 bg-[#241178]/10 hover:bg-[#241178]/20 text-[#241178] font-semibold rounded-lg transition">
                ⬅️ Volver
            </a>
        </div>

        <!-- 📋 Formulario -->
        <form action="{{ route('union.members.update', $worker->id) }}" method="POST"
              class="w-full max-w-3xl bg-[#FFFFFF] border border-[#D9D9D9] shadow-md rounded-2xl p-8 space-y-6 font-[Inter]">
            @csrf
            @method('PUT')

            <!-- Nombre -->
            <div>
                <label for="name" class="block font-semibold text-[#272800] mb-1">Nombre completo</label>
                <input type="text" id="name" name="name" value="{{ old('name', $worker->name) }}" required
                       class="w-full border border-[#D9D9D9] rounded-lg px-4 py-2 focus:ring-2 focus:ring-[#DC6601] outline-none"
                       placeholder="Ejemplo: Juan Pérez Gómez">
                @error('name')
                    <p class="text-[#EE0000] text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Correo -->
            <div>
                <label for="email" class="block font-semibold text-[#272800] mb-1">Correo electrónico</label>
                <input type="email" id="email" name="email" value="{{ old('email', $worker->email) }}"
                       class="w-full border border-[#D9D9D9] rounded-lg px-4 py-2 focus:ring-2 focus:ring-[#DC6601] outline-none"
                       placeholder="correo@ejemplo.com">
                @error('email')
                    <p class="text-[#EE0000] text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Datos complementarios -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="curp" class="block font-semibold text-[#272800] mb-1">CURP</label>
                    <input type="text" id="curp" name="curp" value="{{ old('curp', $worker->detalle->curp ?? '') }}"
                           class="w-full border border-[#D9D9D9] rounded-lg px-4 py-2 focus:ring-2 focus:ring-[#DC6601] outline-none"
                           placeholder="Ejemplo: PEGA850101HDFRRN09">
                </div>

                <div>
                    <label for="rfc" class="block font-semibold text-[#272800] mb-1">RFC</label>
                    <input type="text" id="rfc" name="rfc" value="{{ old('rfc', $worker->detalle->rfc ?? '') }}"
                           class="w-full border border-[#D9D9D9] rounded-lg px-4 py-2 focus:ring-2 focus:ring-[#DC6601] outline-none"
                           placeholder="Ejemplo: PEGA850101XXX">
                </div>

                <div>
                    <label for="sexo" class="block font-semibold text-[#272800] mb-1">Sexo</label>
                    <select id="sexo" name="sexo"
                            class="w-full border border-[#D9D9D9] rounded-lg px-4 py-2 focus:ring-2 focus:ring-[#DC6601] outline-none">
                        <option value="">Selecciona</option>
                        <option value="H" {{ old('sexo', $worker->detalle->sexo ?? '') == 'H' ? 'selected' : '' }}>Hombre</option>
                        <option value="M" {{ old('sexo', $worker->detalle->sexo ?? '') == 'M' ? 'selected' : '' }}>Mujer</option>
                    </select>
                </div>

                <div>
                    <label for="clave_presupuestal" class="block font-semibold text-[#272800] mb-1">Clave presupuestal</label>
                    <input type="text" id="clave_presupuestal" name="clave_presupuestal"
                           value="{{ old('clave_presupuestal', $worker->detalle->clave_presupuestal ?? '') }}"
                           class="w-full border border-[#D9D9D9] rounded-lg px-4 py-2 focus:ring-2 focus:ring-[#DC6601] outline-none"
                           placeholder="Ejemplo: 123-ABC">
                </div>
            </div>

            <!-- Estado -->
            <div>
                <label class="block font-semibold text-[#272800] mb-1">Estado</label>
                <select name="activo"
                        class="w-full border border-[#D9D9D9] rounded-lg px-4 py-2 focus:ring-2 focus:ring-[#DC6601] outline-none">
                    <option value="1" {{ $worker->activo ? 'selected' : '' }}>Activo</option>
                    <option value="0" {{ !$worker->activo ? 'selected' : '' }}>Inactivo</option>
                </select>
            </div>

            <!-- Botones -->
            <div class="flex flex-col sm:flex-row justify-end gap-4 mt-8">
                <a href="{{ route('union.members.index') }}"
                   class="px-6 py-2 bg-[#241178]/10 text-[#241178] hover:bg-[#241178]/20 font-semibold rounded-lg transition text-center">
                    Cancelar
                </a>
                <button type="submit"
                        class="px-6 py-2 bg-[#DC6601] hover:bg-[#EE0000] text-white font-semibold rounded-lg transition flex items-center gap-2 justify-center">
                    <x-heroicon-o-user-circle class="w-5 h-5" />
                    Guardar cambios
                </button>
            </div>
        </form>
    </div>
</x-layouts.app>
