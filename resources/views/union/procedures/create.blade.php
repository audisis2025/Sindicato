{{-- ===========================================================
 Nombre de la clase: procedures.blade.php
 Descripción: Crear nuevo trámite con definición de pasos y flujo alterno por paso.
 Fecha: 03/11/2025 | Versión: 1.4
 Tipo de mantenimiento: Correctivo-Perfectivo
 Descripción del mantenimiento: Homogeneiza campos al modelo PRO-Laravel V3.4,
 añade picker de fechas, valida flujo alterno y corrige claves internas.
 Responsable: Iker Piza
 Revisor: QA SINDISOFT
=========================================================== --}}

<x-layouts.app :title="__('Crear nuevo trámite')">

    {{-- === Flatpickr === --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://npmcdn.com/flatpickr/dist/l10n/es.js"></script>

    <div class="w-full flex flex-col items-center justify-start min-h-[80vh] bg-[#FFFFFF] text-[#000000] p-6">

        <h1 class="text-3xl font-[Poppins] font-bold text-[#DC6601] mb-2">Nuevo Trámite</h1>
        <p class="text-[#241178] font-[Inter] mb-8">
            Define la estructura del trámite, sus pasos y comportamientos alternos.
        </p>

        <form action="{{ route('union.procedures.store') }}" method="POST" enctype="multipart/form-data"
            class="w-full max-w-4xl bg-[#FFFFFF] border border-[#D9D9D9] shadow-md rounded-2xl p-8 space-y-6 font-[Inter]">
            @csrf

            {{-- Datos generales --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                {{-- Nombre --}}
                <div>
                    <label class="block font-semibold text-[#272800] mb-1">Nombre del trámite</label>
                    <input type="text" name="name" required
                        class="w-full border border-[#D9D9D9] rounded-lg px-4 py-2 focus:ring-2 focus:ring-[#DC6601]"
                        placeholder="Ej. Solicitud de préstamo sindical" value="{{ old('name') }}">
                </div>

                {{-- Fecha apertura --}}
                <div>
                    <label class="block font-semibold text-[#272800] mb-1">Fecha de apertura</label>
                    <input type="text" id="opening_date" name="opening_date"
                        class="w-full border border-[#D9D9D9] rounded-lg px-4 py-2" value="{{ old('opening_date') }}">
                </div>

                {{-- Fecha cierre (opcional) --}}
                <div>
                    <label class="block font-semibold text-[#272800] mb-1">Fecha de cierre (opcional)</label>
                    <input type="text" id="closing_date" name="closing_date"
                        class="w-full border border-[#D9D9D9] rounded-lg px-4 py-2" value="{{ old('closing_date') }}">
                </div>
            </div>

            {{-- Descripción --}}
            <div>
                <label class="block font-semibold text-[#272800] mb-1">Descripción</label>
                <textarea name="description" rows="3"
                    class="w-full border border-[#D9D9D9] rounded-lg px-4 py-2 focus:ring-2 focus:ring-[#DC6601]"
                    placeholder="Describe brevemente el trámite">{{ old('description') }}</textarea>
            </div>

            {{-- Tiempo estimado global --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block font-semibold text-[#272800] mb-1">Tiempo estimado global (días) —
                        opcional</label>
                    <input type="number" min="1" name="estimated_days"
                        class="w-full border border-[#D9D9D9] rounded-lg px-4 py-2" placeholder="Ej. 15"
                        value="{{ old('estimated_days') }}">
                </div>
            </div>

            {{-- ========================= PASOS ========================= --}}
            <hr class="border-[#D9D9D9] my-4">

            <div class="flex items-center justify-between">
                <h2 class="text-xl font-[Poppins] font-semibold text-[#241178]">Definición de pasos</h2>
                <button type="button" id="btnAddPaso"
                    class="px-4 py-2 bg-[#DC6601] hover:bg-[#EE0000] text-white rounded-lg">
                    + Añadir paso
                </button>
            </div>

            <div id="pasosWrapper" class="space-y-4 mt-4"></div>

            {{-- Botones --}}
            <div class="flex flex-col sm:flex-row justify-end gap-4 mt-8">
                <a href="{{ route('union.procedures.index') }}"
                    class="px-6 py-2 bg-[#241178]/10 text-[#241178] hover:bg-[#241178]/20 font-semibold rounded-lg text-center">
                    Cancelar
                </a>
                <button type="submit"
                    class="px-6 py-2 bg-[#DC6601] hover:bg-[#EE0000] text-white font-semibold rounded-lg flex items-center gap-2">
                    <x-heroicon-o-document-plus class="w-5 h-5" />
                    Guardar trámite
                </button>
            </div>
        </form>
    </div>


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

                <input type="hidden" name="steps[IDX][order]" value="IDX" class="inp-orden">

                {{-- Nombre del paso --}}
                <div>
                    <label class="block font-semibold text-[#272800] mb-1">Nombre del paso</label>
                    <input type="text" name="steps[IDX][step_name]" required
                        class="w-full border border-[#D9D9D9] rounded-lg px-4 py-2"
                        placeholder="Ej. Registrar solicitud">
                </div>

                {{-- Tiempo estimado --}}
                <div>
                    <label class="block font-semibold text-[#272800] mb-1">Tiempo estimado (días)</label>
                    <input type="number" name="steps[IDX][estimated_days]" min="1"
                        class="w-full border border-[#D9D9D9] rounded-lg px-4 py-2" placeholder="Ej. 5">
                </div>

                {{-- Descripción --}}
                <div class="md:col-span-2">
                    <label class="block font-semibold text-[#272800] mb-1">Descripción</label>
                    <textarea name="steps[IDX][step_description]" rows="2" class="w-full border border-[#D9D9D9] rounded-lg px-4 py-2"
                        placeholder="Describe este paso..."></textarea>
                </div>

                {{-- ¿Requiere documento? (RF-02) --}}
                <div>
                    <label class="block font-semibold text-[#272800] mb-1">¿Requiere documento?</label>
                    <select name="steps[IDX][requires_file]"
                        class="w-full border border-[#D9D9D9] rounded-lg px-4 py-2">
                        <option value="no">No</option>
                        <option value="yes">Sí, el trabajador debe subir archivo</option>
                    </select>
                </div>

                {{-- Archivo ejemplo del paso (opcional) --}}
                <div>
                    <label class="block font-semibold text-[#272800] mb-1">Formato de ejemplo (PDF/Word/Excel)</label>
                    <input type="file" name="steps[IDX][file_path]"
                        class="block w-full text-sm text-[#272800] file:mr-3 file:py-2 file:px-4
                              file:rounded-md file:border-0 file:bg-[#241178]/10 hover:file:bg-[#241178]/20">
                </div>

                {{-- Flujo alterno --}}
                <div>
                    <label class="block font-semibold text-[#272800] mb-1">
                        Si este paso falla, ir al paso...
                    </label>
                    <select name="steps[IDX][next_step_if_fail]"
                        class="w-full border border-[#D9D9D9] rounded-lg px-4 py-2 sel-next">
                        <option value="">— Ninguno (flujo principal) —</option>
                    </select>
                </div>

            </div>
        </div>
    </template>


    {{-- ========================= JS ========================= --}}
    <script>
        flatpickr("#opening_date", {
            dateFormat: "Y-m-d",
            locale: "es"
        });
        flatpickr("#closing_date", {
            dateFormat: "Y-m-d",
            locale: "es",
            allowInput: true
        });

        const pasosWrapper = document.getElementById('pasosWrapper');
        const tplPaso = document.getElementById('tplPaso').content;
        const btnAddPaso = document.getElementById('btnAddPaso');

        function rebuildNextStepOptions() {
            const total = pasosWrapper.children.length;
            [...pasosWrapper.querySelectorAll('.sel-next')].forEach((sel, idx) => {
                const current = idx + 1;
                sel.innerHTML = '<option value="">— Ninguno (flujo principal) —</option>';
                for (let i = 1; i <= total; i++) {
                    if (i === current) continue;
                    sel.innerHTML += `<option value="${i}">Paso ${i}</option>`;
                }
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

            node.querySelector('.btnRemovePaso').addEventListener('click', e => {
                e.currentTarget.closest('div.border').remove();

                [...pasosWrapper.children].forEach((c, i) => {
                    const newIdx = i + 1;
                    c.querySelector('.badge-orden').textContent = newIdx;
                    c.querySelector('.inp-orden').value = newIdx;
                    c.querySelectorAll('[name]').forEach(el => {
                        el.name = el.name.replace(/steps\[\d+\]/, `steps[${newIdx}]`);
                    });
                });

                rebuildNextStepOptions();
            });

            pasosWrapper.appendChild(node);
            rebuildNextStepOptions();
        }

        addPaso();
        addPaso();

        btnAddPaso.addEventListener('click', addPaso);
    </script>

</x-layouts.app>
