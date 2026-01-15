{{-- 
* Nombre de la vista          : create.blade.php
* Descripción de la vista     : Vista para la creación de nuevos trámites por parte del sindicato, permitiendo
*                               capturar información general, fechas, tiempo estimado y definir dinámicamente
*                               los pasos del trámite, incluyendo requerimiento de documentos y flujo alterno.
* Fecha de creación           : 14/01/2026
* Elaboró                     : Iker Piza
* Fecha de liberación         : 14/01/2026
* Autorizó                    :
* Versión                     : 1.0
* Fecha de mantenimiento      :
* Folio de mantenimiento      :
* Tipo de mantenimiento       :
* Descripción del mantenimiento:
* Responsable                 :
* Revisor                     :
--}}

<x-layouts.app :title="__('Crear nuevo trámite')">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://npmcdn.com/flatpickr/dist/l10n/es.js"></script>

    <div class="w-full flex flex-col items-center justify-start min-h-[80vh] bg-white text-black p-6">

        <div class="w-full max-w-4xl flex justify-end mb-4">
            <flux:button
                icon="arrow-long-left"
                icon-variant="outline"
                variant="ghost"
                :href="route('union.procedures.index')"
            >
                Regresar
            </flux:button>
        </div>

        <h1 class="text-3xl font-bold text-[#DE6601] mb-2">Nuevo Trámite</h1>
        <p class="text-[#272800] mb-6">
            Define la estructura del trámite, sus pasos y comportamientos alternos.
        </p>

        <form
            action="{{ route('union.procedures.store') }}"
            method="POST"
            enctype="multipart/form-data"
            class="w-full max-w-4xl bg-white border border-[#D9D9D9] shadow-md rounded-2xl p-8 space-y-6"
        >
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                <div>
                    <label class="block font-semibold text-[#272800] mb-1">Nombre del trámite</label>
                    <input
                        type="text"
                        name="name"
                        required
                        maxlength="255"
                        class="w-full border border-[#D9D9D9] rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-600"
                        placeholder="Ej. Solicitud de préstamo sindical"
                        value="{{ old('name') }}"
                    >
                </div>

                <div>
                    <label class="block font-semibold text-[#272800] mb-1">Tiempo estimado global (días)</label>
                    <input
                        type="number"
                        min="1"
                        name="estimated_days"
                        required
                        class="w-full border border-[#D9D9D9] rounded-lg px-4 py-2"
                        placeholder="Ej. 15"
                        value="{{ old('estimated_days') }}"
                    >
                </div>

                <div>
                    <label class="block font-semibold text-[#272800] mb-1">Fecha de apertura</label>
                    <input
                        type="text"
                        id="opening_date"
                        name="opening_date"
                        required
                        class="w-full border border-[#D9D9D9] rounded-lg px-4 py-2"
                        value="{{ old('opening_date') }}"
                    >
                </div>

                <div>
                    <label class="block font-semibold text-[#272800] mb-1">Fecha de cierre</label>
                    <input
                        type="text"
                        id="closing_date"
                        name="closing_date"
                        required
                        class="w-full border border-[#D9D9D9] rounded-lg px-4 py-2"
                        value="{{ old('closing_date') }}"
                    >
                </div>

            </div>

            <div>
                <label class="block font-semibold text-[#272800] mb-1">Descripción</label>
                <textarea
                    name="description"
                    rows="3"
                    required
                    maxlength="2000"
                    class="w-full border border-[#D9D9D9] rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-600"
                    placeholder="Describe brevemente el trámite"
                >{{ old('description') }}</textarea>
            </div>

            <hr class="border-[#D9D9D9] my-4">

            <div class="flex items-center justify-between">
                <h2 class="text-xl font-semibold text-[#241178]">Definición de pasos</h2>

                <flux:button
                    icon="plus"
                    icon-variant="outline"
                    variant="primary"
                    type="button"
                    id="btnAddPaso"
                    class="!bg-blue-600 hover:!bg-blue-700 !text-white"
                >
                    Añadir paso
                </flux:button>
            </div>

            <div id="pasosWrapper" class="space-y-4 mt-4"></div>

            <div class="flex flex-col sm:flex-row justify-end gap-4 mt-8">

                <flux:button
                    icon="x-circle"
                    icon-variant="outline"
                    variant="primary"
                    :href="route('union.procedures.index')"
                    class="!bg-red-600 hover:!bg-red-700 !text-white"
                >
                    Cancelar
                </flux:button>

                <flux:button
                    icon="check-circle"
                    icon-variant="outline"
                    variant="primary"
                    type="submit"
                    class="!bg-green-600 hover:!bg-green-700 !text-white"
                >
                    Guardar trámite
                </flux:button>

            </div>

        </form>
    </div>

    <template id="tplPaso">
        <div class="border border-[#D9D9D9] rounded-xl p-4 step-card">

            <div class="flex items-center justify-between mb-3">
                <h3 class="font-semibold text-[#272800]">
                    Paso <span class="badge-orden">IDX</span>
                </h3>

                <flux:button
                    icon="trash"
                    icon-variant="outline"
                    variant="danger"
                    type="button"
                    class="!bg-red-600 hover:!bg-red-700 !text-white px-3 py-1 text-sm btnRemovePaso"
                >
                    Eliminar
                </flux:button>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                <input type="hidden" name="steps[IDX][order]" value="IDX" class="inp-orden">

                <div>
                    <label class="block font-semibold text-[#272800] mb-1">Nombre del paso</label>
                    <input
                        type="text"
                        name="steps[IDX][step_name]"
                        required
                        maxlength="255"
                        class="w-full border border-[#D9D9D9] rounded-lg px-4 py-2"
                        placeholder="Ej. Registrar solicitud"
                    >
                </div>

                <div class="md:col-span-2">
                    <label class="block font-semibold text-[#272800] mb-1">Descripción (opcional)</label>
                    <textarea
                        name="steps[IDX][step_description]"
                        rows="2"
                        maxlength="2000"
                        class="w-full border border-[#D9D9D9] rounded-lg px-4 py-2"
                        placeholder="Describe este paso..."
                    ></textarea>
                </div>

                <div>
                    <label class="block font-semibold text-[#272800] mb-1">¿Requiere documento?</label>
                    <select
                        name="steps[IDX][requires_file]"
                        required
                        class="w-full border border-[#D9D9D9] rounded-lg px-4 py-2"
                    >
                        <option value="no">No</option>
                        <option value="yes">Sí, el trabajador debe subir archivo</option>
                    </select>
                </div>

                <div>
                    <label class="block font-semibold text-[#272800] mb-1">Formato ejemplo (opcional)</label>
                    <input
                        type="file"
                        name="steps[IDX][file_path]"
                        class="block w-full text-sm text-[#272800] file:mr-3 file:py-2 file:px-4 file:rounded-md file:border-0 file:bg-zinc-200 hover:file:bg-zinc-300"
                    >
                </div>

                <div>
                    <label class="block font-semibold text-[#272800] mb-1">Flujo alterno (opcional)</label>
                    <select
                        name="steps[IDX][next_step_if_fail]"
                        class="w-full border border-[#D9D9D9] rounded-lg px-4 py-2 sel-next"
                    >
                        <option value="">— Ninguno (flujo principal) —</option>
                    </select>
                </div>

            </div>
        </div>
    </template>

    <script>
        flatpickr.localize(flatpickr.l10ns.es);

        flatpickr("#opening_date", { dateFormat: "Y-m-d", allowInput: true });
        flatpickr("#closing_date", { dateFormat: "Y-m-d", allowInput: true });

        const pasosWrapper = document.getElementById('pasosWrapper');
        const tplPaso = document.getElementById('tplPaso').content;
        const btnAddPaso = document.getElementById('btnAddPaso');

        function rebuildNextStepOptions() {
            const total = pasosWrapper.querySelectorAll('.step-card').length;

            pasosWrapper.querySelectorAll('.step-card').forEach((card, idx) => {
                const current = idx + 1;
                const sel = card.querySelector('.sel-next');
                const selected = sel.value;

                sel.innerHTML = '<option value="">— Ninguno (flujo principal) —</option>';

                for (let i = 1; i <= total; i++) {
                    if (i === current) continue;
                    const opt = document.createElement('option');
                    opt.value = String(i);
                    opt.textContent = `Paso ${i}`;
                    sel.appendChild(opt);
                }

                if (selected && selected !== String(current)) {
                    sel.value = selected;
                }
            });
        }

        function renumberSteps() {
            const cards = pasosWrapper.querySelectorAll('.step-card');

            cards.forEach((card, idx) => {
                const newIdx = idx + 1;

                card.querySelector('.badge-orden').textContent = newIdx;
                card.querySelector('.inp-orden').value = newIdx;

                card.querySelectorAll('[name]').forEach(el => {
                    el.name = el.name.replace(/steps\[\d+\]/, `steps[${newIdx}]`);
                });
            });

            rebuildNextStepOptions();
        }

        function addPaso() {
            const idx = pasosWrapper.querySelectorAll('.step-card').length + 1;
            const node = document.importNode(tplPaso, true);

            node.querySelectorAll('[name]').forEach(el => {
                el.name = el.name.replaceAll('IDX', idx);
            });

            node.querySelectorAll('.badge-orden').forEach(el => el.textContent = idx);
            node.querySelectorAll('.inp-orden').forEach(el => el.value = idx);

            pasosWrapper.appendChild(node);
            renumberSteps();
        }

        pasosWrapper.addEventListener('click', (e) => {
            const btn = e.target.closest('.btnRemovePaso');
            if (!btn) return;

            const card = btn.closest('.step-card');
            if (card) card.remove();

            renumberSteps();
        });

        btnAddPaso.addEventListener('click', addPaso);

        addPaso();
        addPaso();
    </script>

</x-layouts.app>
