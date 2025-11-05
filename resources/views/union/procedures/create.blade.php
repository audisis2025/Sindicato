{{-- ===========================================================
 Nombre de la clase: procedures.blade.php
 Descripción: Crear nuevo trámite con definición de pasos y flujo alterno por paso.
 Fecha: 03/11/2025 | Versión: 1.2 | Mantenimiento: Homogeneización + Flujo alterno + Archivos
=========================================================== --}}
<x-layouts.app :title="__('Crear nuevo trámite')">
    <div class="w-full flex flex-col items-center justify-start min-h-[80vh] bg-[#FFFFFF] text-[#000000] p-6">

        <h1 class="text-3xl font-[Poppins] font-bold text-[#DC6601] mb-2">Nuevo Trámite</h1>
        <p class="text-[#241178] font-[Inter] mb-8">
            Define la estructura del trámite, sus pasos y comportamientos alternos.
        </p>

        <form action="{{ route('union.procedures.store') }}" method="POST" enctype="multipart/form-data"
            class="w-full max-w-4xl bg-[#FFFFFF] border border-[#D9D9D9] shadow-md rounded-2xl p-8 space-y-6 font-[Inter]">
            @csrf

            {{-- Datos generales del trámite --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block font-semibold text-[#272800] mb-1">Nombre del trámite</label>
                    <input type="text" name="nombre" required
                        class="w-full border border-[#D9D9D9] rounded-lg px-4 py-2 focus:ring-2 focus:ring-[#DC6601] outline-none"
                        placeholder="Ej. Solicitud de préstamo sindical" value="{{ old('nombre') }}">
                    @error('nombre')
                        <p class="text-[#EE0000] text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="block font-semibold text-[#272800] mb-1">Fecha de apertura</label>
                    <input type="date" name="fecha_apertura"
                        class="w-full border border-[#D9D9D9] rounded-lg px-4 py-2 focus:ring-2 focus:ring-[#DC6601] outline-none"
                        value="{{ old('fecha_apertura') }}">
                    @error('fecha_apertura')
                        <p class="text-[#EE0000] text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="block font-semibold text-[#272800] mb-1">Fecha de cierre</label>
                    <input type="date" name="fecha_cierre"
                        class="w-full border border-[#D9D9D9] rounded-lg px-4 py-2 focus:ring-2 focus:ring-[#DC6601] outline-none"
                        value="{{ old('fecha_cierre') }}">
                    @error('fecha_cierre')
                        <p class="text-[#EE0000] text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div>
                <label class="block font-semibold text-[#272800] mb-1">Descripción</label>
                <textarea name="descripcion" rows="3"
                    class="w-full border border-[#D9D9D9] rounded-lg px-4 py-2 focus:ring-2 focus:ring-[#DC6601] outline-none"
                    placeholder="Describe brevemente el trámite">{{ old('descripcion') }}</textarea>
                @error('descripcion')
                    <p class="text-[#EE0000] text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block font-semibold text-[#272800] mb-1">Tiempo estimado global (días) —
                        opcional</label>
                    <input type="number" min="1" name="tiempo_estimado_dias"
                        class="w-full border border-[#D9D9D9] rounded-lg px-4 py-2 focus:ring-2 focus:ring-[#DC6601] outline-none"
                        placeholder="Ej. 15" value="{{ old('tiempo_estimado_dias') }}">
                    @error('tiempo_estimado_dias')
                        <p class="text-[#EE0000] text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- ========= Builder de pasos ========= --}}
            <hr class="border-[#D9D9D9] my-4">

            <div class="flex items-center justify-between">
                <h2 class="text-xl font-[Poppins] font-semibold text-[#241178]">Definición de pasos</h2>
                <button type="button" id="btnAddPaso"
                    class="px-4 py-2 bg-[#DC6601] hover:bg-[#EE0000] text-white rounded-lg transition">
                    + Añadir paso
                </button>
            </div>

            <div id="pasosWrapper" class="space-y-4 mt-4">
                {{-- plantilla de paso (se clona desde JS) --}}
            </div>

            {{-- ========= Botones ========= --}}
            <div class="flex flex-col sm:flex-row justify-end gap-4 mt-8">
                <a href="{{ route('union.procedures.index') }}"
                    class="px-6 py-2 bg-[#241178]/10 text-[#241178] hover:bg-[#241178]/20 font-semibold rounded-lg transition text-center">
                    Cancelar
                </a>
                <button type="submit"
                    class="px-6 py-2 bg-[#DC6601] hover:bg-[#EE0000] text-white font-semibold rounded-lg transition flex items-center gap-2 justify-center">
                    <x-heroicon-o-document-plus class="w-5 h-5" />
                    Guardar trámite
                </button>
            </div>
        </form>
    </div>

    {{-- === Plantilla oculta para cada paso === --}}
    <template id="tplPaso">
        <div class="border border-[#D9D9D9] rounded-xl p-4">
            <div class="flex items-center justify-between mb-3">
                <h3 class="font-[Poppins] font-semibold text-[#272800]">
                    Paso <span class="badge-orden"></span>
                </h3>
                <button type="button" class="btnRemovePaso text-[#EE0000] hover:text-[#DC6601] font-semibold">
                    Eliminar
                </button>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <input type="hidden" name="pasos[IDX][orden]" value="IDX" class="inp-orden">

                <div>
                    <label class="block font-semibold text-[#272800] mb-1">Nombre del paso</label>
                    <input type="text" name="pasos[IDX][nombre_paso]" required
                        class="w-full border border-[#D9D9D9] rounded-lg px-4 py-2 focus:ring-2 focus:ring-[#DC6601] outline-none"
                        placeholder="Ej. Inscribirse al sorteo">
                </div>

                <div>
                    <label class="block font-semibold text-[#272800] mb-1">Tiempo estimado (días)</label>
                    <input type="number" name="pasos[IDX][tiempo_estimado_dias]" min="1"
                        class="w-full border border-[#D9D9D9] rounded-lg px-4 py-2 focus:ring-2 focus:ring-[#DC6601] outline-none"
                        placeholder="Ej. 5">
                </div>

                <div class="md:col-span-2">
                    <label class="block font-semibold text-[#272800] mb-1">Descripción</label>
                    <textarea name="pasos[IDX][descripcion_paso]" rows="2"
                        class="w-full border border-[#D9D9D9] rounded-lg px-4 py-2 focus:ring-2 focus:ring-[#DC6601] outline-none"
                        placeholder="Describe qué hace este paso..."></textarea>
                </div>

                <div>
                    <label class="block font-semibold text-[#272800] mb-1">Archivo del paso (PDF/Word/Excel) —
                        opcional</label>
                    <input type="file" name="pasos[IDX][formato]"
                        class="block w-full text-sm text-[#272800] file:mr-3 file:py-2 file:px-4
                                  file:rounded-md file:border-0 file:text-sm file:font-semibold
                                  file:bg-[#241178]/10 file:text-[#241178] hover:file:bg-[#241178]/20">
                </div>

                <div>
                    <label class="block font-semibold text-[#272800] mb-1">
                        Si este paso falla, ir al paso...
                    </label>
                    <select name="pasos[IDX][next_step_if_fail]"
                        class="w-full border border-[#D9D9D9] rounded-lg px-4 py-2 focus:ring-2 focus:ring-[#DC6601] outline-none sel-next">
                        <option value="">— Ninguno (flujo principal) —</option>
                        <!-- Se llena dinámicamente con 1..N -->
                    </select>
                    <p class="text-xs text-[#241178] mt-1">Ejemplo: en el “Sorteo” (paso 2), si falla → volver al 1
                        (reinscribirse).</p>
                </div>
            </div>
        </div>
    </template>

    {{-- === JS mínimo para el builder de pasos === --}}
    <script>
        const pasosWrapper = document.getElementById('pasosWrapper');
        const tplPaso = document.getElementById('tplPaso').content;
        const btnAddPaso = document.getElementById('btnAddPaso');

        function rebuildNextStepOptions() {
            const total = pasosWrapper.children.length;
            [...pasosWrapper.querySelectorAll('.sel-next')].forEach((sel, idx) => {
                const currentOrden = idx + 1;
                sel.innerHTML = '<option value="">— Ninguno (flujo principal) —</option>';
                for (let i = 1; i <= total; i++) {
                    sel.innerHTML += `<option value="${i}">${i}</option>`;
                }
                // No tiene sentido saltar a sí mismo al fallar; opcionalmente lo puedes deshabilitar:
                // sel.querySelector(`option[value="${currentOrden}"]`)?.setAttribute('disabled', true);
            });
        }

        function addPaso() {
            const idx = pasosWrapper.children.length + 1;
            const node = document.importNode(tplPaso, true);

            node.querySelector('.badge-orden').textContent = idx;
            node.querySelector('.inp-orden').value = idx;
            node.querySelectorAll('[name]').forEach(el => {
                el.name = el.name.replaceAll('IDX', idx);
            });

            node.querySelector('.btnRemovePaso').addEventListener('click', (e) => {
                e.currentTarget.closest('div.border').remove();
                [...pasosWrapper.children].forEach((c, i) => {
                    const newIdx = i + 1;
                    c.querySelector('.badge-orden').textContent = newIdx;
                    c.querySelector('.inp-orden').value = newIdx;
                    c.querySelectorAll('[name]').forEach(el => {
                        el.name = el.name.replace(/pasos\[\d+\]/, `pasos[${newIdx}]`);
                    });
                });
                rebuildNextStepOptions();
            });

            pasosWrapper.appendChild(node);
            rebuildNextStepOptions();
        }


        // Inicial: dos pasos por defecto
        addPaso();
        addPaso();

        btnAddPaso.addEventListener('click', addPaso);
    </script>
</x-layouts.app>
