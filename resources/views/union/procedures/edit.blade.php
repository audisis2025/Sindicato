{{-- ===========================================================
 Nombre de la clase: procedures-edit.blade.php
 Descripción: Vista para editar un trámite creado por el Sindicato.
 Fecha: 03/11/2025 | Versión: 1.0
 Tipo de mantenimiento: Creación.
 Descripción del mantenimiento: Permite modificar la información general
 del trámite y sus pasos, manteniendo coherencia visual institucional.
 Responsable: Iker Piza
 Revisor: QA SINDISOFT
=========================================================== --}}

<x-layouts.app :title="__('Editar trámite')">
    <div class="w-full flex flex-col items-center justify-start min-h-[80vh] bg-[#FFFFFF] text-[#000000] p-6">

        <!-- 🔸 Encabezado -->
        <div class="w-full max-w-4xl flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6">
            <div>
                <h1 class="text-3xl font-[Poppins] font-bold text-[#DC6601] mb-1">
                    Editar Trámite
                </h1>
                <p class="text-[#241178] font-[Inter] text-sm">
                    Actualiza la información del trámite y sus pasos.
                </p>
            </div>

            <a href="{{ route('union.procedures.index') }}"
                class="px-4 py-2 bg-[#241178]/10 hover:bg-[#241178]/20 text-[#241178] font-semibold rounded-lg transition">
                ⬅️ Volver
            </a>
        </div>

        <!-- 📋 Formulario -->
        <form action="{{ route('union.procedures.update', $procedure->id) }}" method="POST" enctype="multipart/form-data"
            class="w-full max-w-4xl bg-[#FFFFFF] border border-[#D9D9D9] shadow-md rounded-2xl p-8 space-y-6 font-[Inter]">
            @csrf
            @method('PUT')

            {{-- Datos generales del trámite --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block font-semibold text-[#272800] mb-1">Nombre del trámite</label>
                    <input type="text" name="nombre" required
                        class="w-full border border-[#D9D9D9] rounded-lg px-4 py-2 focus:ring-2 focus:ring-[#DC6601] outline-none"
                        value="{{ old('nombre', $procedure->nombre) }}">
                </div>

                <div>
                    <label class="block font-semibold text-[#272800] mb-1">Tiempo estimado global (días)</label>
                    <input type="number" min="1" name="tiempo_estimado_dias"
                        class="w-full border border-[#D9D9D9] rounded-lg px-4 py-2 focus:ring-2 focus:ring-[#DC6601] outline-none"
                        value="{{ old('tiempo_estimado_dias', $procedure->tiempo_estimado_dias) }}">
                </div>

                <div>
                    <label class="block font-semibold text-[#272800] mb-1">Fecha de apertura</label>
                    <input type="date" name="fecha_apertura"
                        class="w-full border border-[#D9D9D9] rounded-lg px-4 py-2 focus:ring-2 focus:ring-[#DC6601] outline-none"
                        value="{{ old('fecha_apertura', $procedure->fecha_apertura) }}">
                </div>

                <div>
                    <label class="block font-semibold text-[#272800] mb-1">Fecha de cierre</label>
                    <input type="date" name="fecha_cierre"
                        class="w-full border border-[#D9D9D9] rounded-lg px-4 py-2 focus:ring-2 focus:ring-[#DC6601] outline-none"
                        value="{{ old('fecha_cierre', $procedure->fecha_cierre) }}">
                </div>
            </div>

            <div>
                <label class="block font-semibold text-[#272800] mb-1">Descripción</label>
                <textarea name="descripcion" rows="3"
                    class="w-full border border-[#D9D9D9] rounded-lg px-4 py-2 focus:ring-2 focus:ring-[#DC6601] outline-none">{{ old('descripcion', $procedure->descripcion) }}</textarea>
            </div>

            {{-- ========= Pasos existentes ========= --}}
            <hr class="border-[#D9D9D9] my-4">

            <h2 class="text-xl font-[Poppins] font-semibold text-[#241178] mb-4">
                Pasos actuales del trámite
            </h2>

            @if ($procedure->pasos->isEmpty())
                <p class="text-gray-500 text-sm mb-4">Este trámite aún no tiene pasos registrados.</p>
            @endif

            <div id="pasosWrapper" class="space-y-4">
                @foreach ($procedure->pasos as $index => $paso)
                    <div class="border border-[#D9D9D9] rounded-xl p-4">
                        <div class="flex items-center justify-between mb-3">
                            <h3 class="font-[Poppins] font-semibold text-[#DC6601]">
                                Paso {{ $paso->orden }}
                            </h3>
                            <button type="button"
                                class="btnRemovePaso text-[#EE0000] hover:text-[#DC6601] font-semibold">Eliminar</button>
                        </div>

                        <input type="hidden" name="pasos[{{ $index + 1 }}][orden]" value="{{ $index + 1 }}">

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block font-semibold text-[#272800] mb-1">Nombre del paso</label>
                                <input type="text" name="pasos[{{ $index + 1 }}][nombre_paso]"
                                    class="w-full border border-[#D9D9D9] rounded-lg px-4 py-2 focus:ring-2 focus:ring-[#DC6601] outline-none"
                                    value="{{ $paso->nombre_paso }}" required>
                            </div>

                            <div>
                                <label class="block font-semibold text-[#272800] mb-1">Tiempo estimado (días)</label>
                                <input type="number" name="pasos[{{ $index + 1 }}][tiempo_estimado_dias]" min="1"
                                    class="w-full border border-[#D9D9D9] rounded-lg px-4 py-2 focus:ring-2 focus:ring-[#DC6601] outline-none"
                                    value="{{ $paso->tiempo_estimado_dias }}">
                            </div>

                            <div class="md:col-span-2">
                                <label class="block font-semibold text-[#272800] mb-1">Descripción</label>
                                <textarea name="pasos[{{ $index + 1 }}][descripcion_paso]" rows="2"
                                    class="w-full border border-[#D9D9D9] rounded-lg px-4 py-2 focus:ring-2 focus:ring-[#DC6601] outline-none">{{ $paso->descripcion_paso }}</textarea>
                            </div>

                            <div>
                                <label class="block font-semibold text-[#272800] mb-1">Archivo actual</label>
                                @if ($paso->formato_path)
                                    <a href="{{ asset('storage/' . $paso->formato_path) }}" target="_blank"
                                        class="text-[#241178] hover:text-[#DC6601] underline text-sm">
                                        📄 Ver archivo existente
                                    </a>
                                @else
                                    <p class="text-gray-500 text-sm">Sin archivo</p>
                                @endif
                                <input type="file" name="pasos[{{ $index + 1 }}][formato]"
                                    class="block w-full text-sm text-[#272800] mt-2 file:mr-3 file:py-2 file:px-4
                                        file:rounded-md file:border-0 file:text-sm file:font-semibold
                                        file:bg-[#241178]/10 file:text-[#241178] hover:file:bg-[#241178]/20">
                            </div>

                            <div>
                                <label class="block font-semibold text-[#272800] mb-1">
                                    Si este paso falla, ir al paso...
                                </label>
                                <select name="pasos[{{ $index + 1 }}][next_step_if_fail]"
                                    class="w-full border border-[#D9D9D9] rounded-lg px-4 py-2 focus:ring-2 focus:ring-[#DC6601] outline-none">
                                    <option value="">— Ninguno (flujo principal) —</option>
                                    @for ($i = 1; $i <= $procedure->numero_pasos; $i++)
                                        <option value="{{ $i }}" @if ($paso->next_step_if_fail == $i) selected @endif>
                                            Paso {{ $i }}
                                        </option>
                                    @endfor
                                </select>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- ========= Botón para añadir nuevo paso ========= --}}
            <div class="flex justify-end mt-4">
                <button type="button" id="btnAddPaso"
                    class="px-4 py-2 bg-[#DC6601] hover:bg-[#EE0000] text-white rounded-lg transition">
                    + Añadir paso
                </button>
            </div>

            {{-- ========= Botones finales ========= --}}
            <div class="flex flex-col sm:flex-row justify-end gap-4 mt-8">
                <a href="{{ route('union.procedures.index') }}"
                    class="px-6 py-2 bg-[#241178]/10 text-[#241178] hover:bg-[#241178]/20 font-semibold rounded-lg transition text-center">
                    Cancelar
                </a>
                <button type="submit"
                    class="px-6 py-2 bg-[#DC6601] hover:bg-[#EE0000] text-white font-semibold rounded-lg transition flex items-center gap-2 justify-center">
                    <x-heroicon-o-document-check class="w-5 h-5" />
                    Guardar cambios
                </button>
            </div>
        </form>
    </div>

    {{-- === Script para añadir pasos dinámicos === --}}
    <script>
        const pasosWrapper = document.getElementById('pasosWrapper');
        const btnAddPaso = document.getElementById('btnAddPaso');

        btnAddPaso.addEventListener('click', () => {
            const count = pasosWrapper.children.length + 1;
            const html = `
                <div class="border border-[#D9D9D9] rounded-xl p-4">
                    <div class="flex items-center justify-between mb-3">
                        <h3 class="font-[Poppins] font-semibold text-[#DC6601]">Paso ${count}</h3>
                        <button type="button" class="btnRemovePaso text-[#EE0000] hover:text-[#DC6601] font-semibold">Eliminar</button>
                    </div>
                    <input type="hidden" name="pasos[${count}][orden]" value="${count}">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block font-semibold text-[#272800] mb-1">Nombre del paso</label>
                            <input type="text" name="pasos[${count}][nombre_paso]" required
                                class="w-full border border-[#D9D9D9] rounded-lg px-4 py-2 focus:ring-2 focus:ring-[#DC6601] outline-none">
                        </div>
                        <div>
                            <label class="block font-semibold text-[#272800] mb-1">Tiempo estimado (días)</label>
                            <input type="number" name="pasos[${count}][tiempo_estimado_dias]" min="1"
                                class="w-full border border-[#D9D9D9] rounded-lg px-4 py-2 focus:ring-2 focus:ring-[#DC6601] outline-none">
                        </div>
                        <div class="md:col-span-2">
                            <label class="block font-semibold text-[#272800] mb-1">Descripción</label>
                            <textarea name="pasos[${count}][descripcion_paso]" rows="2"
                                class="w-full border border-[#D9D9D9] rounded-lg px-4 py-2 focus:ring-2 focus:ring-[#DC6601] outline-none"></textarea>
                        </div>
                        <div>
                            <label class="block font-semibold text-[#272800] mb-1">Archivo del paso (PDF/Word/Excel)</label>
                            <input type="file" name="pasos[${count}][formato]"
                                class="block w-full text-sm text-[#272800] file:mr-3 file:py-2 file:px-4
                                    file:rounded-md file:border-0 file:text-sm file:font-semibold
                                    file:bg-[#241178]/10 file:text-[#241178] hover:file:bg-[#241178]/20">
                        </div>
                        <div>
                            <label class="block font-semibold text-[#272800] mb-1">Si este paso falla, ir al paso...</label>
                            <select name="pasos[${count}][next_step_if_fail]"
                                class="w-full border border-[#D9D9D9] rounded-lg px-4 py-2 focus:ring-2 focus:ring-[#DC6601] outline-none">
                                <option value="">— Ninguno (flujo principal) —</option>
                                @for ($i = 1; $i <= $procedure->numero_pasos; $i++)
                                    <option value="{{ $i }}">Paso {{ $i }}</option>
                                @endfor
                            </select>
                        </div>
                    </div>
                </div>`;
            pasosWrapper.insertAdjacentHTML('beforeend', html);
        });

        pasosWrapper.addEventListener('click', (e) => {
            if (e.target.classList.contains('btnRemovePaso')) {
                e.target.closest('div.border').remove();
            }
        });
    </script>
</x-layouts.app>
