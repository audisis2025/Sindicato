{{-- 
* Nombre de la vista           : edit.blade.php
* Descripción de la vista      : Vista para editar un trámite creado por el Sindicato.
* Fecha de creación            : 03/11/2025
* Elaboró                      : Iker Piza
* Fecha de liberación          : 03/11/2025
* Autorizó                     : Líder Técnico
* Versión                      : 1.2
* Fecha de mantenimiento       : 13/01/2026
* Folio de mantenimiento       : N/A
* Tipo de mantenimiento        : Correctivo y perfectivo
* Descripción del mantenimiento: Homologación según Manual PRO-Laravel (Regresar, botones, colores y estructura).
* Responsable                  : Iker Piza
* Revisor                      : QA SINDISOFT
--}}

<x-layouts.app :title="__('Editar trámite')">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://npmcdn.com/flatpickr/dist/l10n/es.js"></script>

    <div class="w-full flex flex-col items-center min-h-[80vh] bg-white p-6">

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

        <h1 class="text-3xl font-bold text-[#DE6601] mb-2">Editar Trámite</h1>
        <p class="text-[#272800] mb-6">Actualiza la información del trámite y sus pasos.</p>

        <form
            action="{{ route('union.procedures.update', $procedure->id) }}"
            method="POST"
            enctype="multipart/form-data"
            class="w-full max-w-4xl bg-white border border-zinc-300 shadow-md rounded-2xl p-8 space-y-8"
        >
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                <div>
                    <label class="block font-semibold text-[#272800] mb-1">Nombre del trámite</label>
                    <input
                        type="text"
                        name="name"
                        required
                        maxlength="255"
                        class="w-full border border-zinc-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-600"
                        value="{{ old('name', $procedure->name) }}"
                    >
                </div>

                <div>
                    <label class="block font-semibold text-[#272800] mb-1">Tiempo estimado global (días)</label>
                    <input
                        type="number"
                        name="estimated_days"
                        required
                        min="1"
                        class="w-full border border-zinc-300 rounded-lg px-4 py-2"
                        value="{{ old('estimated_days', $procedure->estimated_days) }}"
                    >
                </div>

                <div>
                    <label class="block font-semibold text-[#272800] mb-1">Fecha de apertura</label>
                    <input
                        type="text"
                        id="opening_date"
                        name="opening_date"
                        required
                        class="w-full border border-zinc-300 rounded-lg px-4 py-2"
                        value="{{ old('opening_date', $procedure->opening_date) }}"
                    >
                </div>

                <div>
                    <label class="block font-semibold text-[#272800] mb-1">Fecha de cierre</label>
                    <input
                        type="text"
                        id="closing_date"
                        name="closing_date"
                        required
                        class="w-full border border-zinc-300 rounded-lg px-4 py-2"
                        value="{{ old('closing_date', $procedure->closing_date) }}"
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
                    class="w-full border border-zinc-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-600"
                >{{ old('description', $procedure->description) }}</textarea>
            </div>

            <hr class="border-zinc-300">

            <div class="flex items-center justify-between">
                <h2 class="text-xl font-semibold text-[#241178]">Pasos del trámite</h2>

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

            <div id="pasosWrapper" class="space-y-4">
                @foreach ($procedure->steps->sortBy('order') as $index => $step)
                    <div class="border border-zinc-300 rounded-xl p-4 step-card">

                        <div class="flex justify-between mb-3">
                            <h3 class="font-semibold text-[#DE6601]">
                                Paso <span class="badge-orden">{{ $index + 1 }}</span>
                            </h3>

                            <flux:button
                                icon="trash"
                                icon-variant="outline"
                                variant="danger"
                                size="xs"
                                type="button"
                                class="!bg-red-600 hover:!bg-red-700 !text-white btnRemovePaso"
                            >
                                Eliminar
                            </flux:button>
                        </div>

                        <input type="hidden" name="steps[{{ $index + 1 }}][order]" value="{{ $index + 1 }}" class="inp-orden">

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                            <div>
                                <label class="block font-semibold text-[#272800] mb-1">Nombre del paso</label>
                                <input
                                    type="text"
                                    name="steps[{{ $index + 1 }}][step_name]"
                                    required
                                    maxlength="255"
                                    class="w-full border border-zinc-300 rounded-lg px-4 py-2"
                                    value="{{ old("steps.".($index+1).".step_name", $step->step_name) }}"
                                >
                            </div>

                            <div class="md:col-span-2">
                                <label class="block font-semibold text-[#272800] mb-1">Descripción</label>
                                <textarea
                                    name="steps[{{ $index + 1 }}][step_description]"
                                    rows="2"
                                    maxlength="2000"
                                    class="w-full border border-zinc-300 rounded-lg px-4 py-2"
                                >{{ old("steps.".($index+1).".step_description", $step->step_description) }}</textarea>
                            </div>

                            <div>
                                <label class="block font-semibold text-[#272800] mb-1">Formato ejemplo (opcional)</label>

                                @if ($step->file_path)
                                    <a
                                        href="{{ asset('storage/' . $step->file_path) }}"
                                        target="_blank"
                                        class="text-[#241178] hover:text-[#DE6601] underline text-sm font-semibold"
                                    >
                                        Ver archivo actual
                                    </a>
                                @else
                                    <p class="text-gray-500 text-sm">Sin archivo</p>
                                @endif

                                <input
                                    type="file"
                                    name="steps[{{ $index + 1 }}][file_path]"
                                    class="block w-full mt-2 text-sm text-[#272800] file:py-2 file:px-4 file:bg-zinc-200 hover:file:bg-zinc-300 file:rounded-md"
                                >
                            </div>

                            <div>
                                <label class="block font-semibold text-[#272800] mb-1">¿Requiere archivo?</label>
                                <select
                                    name="steps[{{ $index + 1 }}][requires_file]"
                                    required
                                    class="w-full border border-zinc-300 rounded-lg px-4 py-2"
                                >
                                    <option value="yes" {{ old("steps.".($index+1).".requires_file", $step->requires_file ? 'yes' : 'no') === 'yes' ? 'selected' : '' }}>Sí</option>
                                    <option value="no"  {{ old("steps.".($index+1).".requires_file", $step->requires_file ? 'yes' : 'no') === 'no' ? 'selected' : '' }}>No</option>
                                </select>
                            </div>

                            <div>
                                <label class="block font-semibold text-[#272800] mb-1">Flujo alterno</label>
                                <select
                                    name="steps[{{ $index + 1 }}][next_step_if_fail]"
                                    class="w-full border border-zinc-300 rounded-lg px-4 py-2 sel-next"
                                >
                                    <option value="">— Ninguno (flujo principal) —</option>

                                    @for ($i = 1; $i <= $procedure->steps_count; $i++)
                                        @if ($i !== ($index + 1))
                                            <option value="{{ $i }}" {{ (string) old("steps.".($index+1).".next_step_if_fail", $step->next_step_if_fail) === (string) $i ? 'selected' : '' }}>
                                                Paso {{ $i }}
                                            </option>
                                        @endif
                                    @endfor
                                </select>
                            </div>

                        </div>
                    </div>
                @endforeach
            </div>

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
                    Guardar cambios
                </flux:button>

            </div>

        </form>
    </div>

    <template id="tplPaso">
        <div class="border border-zinc-300 rounded-xl p-4 step-card">

            <div class="flex justify-between mb-3">
                <h3 class="font-semibold text-[#DE6601]">
                    Paso <span class="badge-orden">IDX</span>
                </h3>

                <flux:button
                    icon="trash"
                    icon-variant="outline"
                    variant="danger"
                    size="xs"
                    type="button"
                    class="!bg-red-600 hover:!bg-red-700 !text-white btnRemovePaso"
                >
                    Eliminar
                </flux:button>
            </div>

            <input type="hidden" name="steps[IDX][order]" value="IDX" class="inp-orden">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                <div>
                    <label class="block font-semibold text-[#272800] mb-1">Nombre del paso</label>
                    <input
                        type="text"
                        name="steps[IDX][step_name]"
                        required
                        maxlength="255"
                        class="w-full border border-zinc-300 rounded-lg px-4 py-2"
                    >
                </div>

                <div class="md:col-span-2">
                    <label class="block font-semibold text-[#272800] mb-1">Descripción</label>
                    <textarea
                        name="steps[IDX][step_description]"
                        rows="2"
                        maxlength="2000"
                        class="w-full border border-zinc-300 rounded-lg px-4 py-2"
                    ></textarea>
                </div>

                <div>
                    <label class="block font-semibold text-[#272800] mb-1">Formato ejemplo (opcional)</label>
                    <input
                        type="file"
                        name="steps[IDX][file_path]"
                        class="block w-full text-sm text-[#272800] file:py-2 file:px-4 file:bg-zinc-200 hover:file:bg-zinc-300 file:rounded-md"
                    >
                </div>

                <div>
                    <label class="block font-semibold text-[#272800] mb-1">¿Requiere archivo?</label>
                    <select
                        name="steps[IDX][requires_file]"
                        required
                        class="w-full border border-zinc-300 rounded-lg px-4 py-2"
                    >
                        <option value="yes">Sí</option>
                        <option value="no">No</option>
                    </select>
                </div>

                <div>
                    <label class="block font-semibold text-[#272800] mb-1">Flujo alterno</label>
                    <select
                        name="steps[IDX][next_step_if_fail]"
                        class="w-full border border-zinc-300 rounded-lg px-4 py-2 sel-next"
                    >
                        <option value="">— Ninguno (flujo principal) —</option>
                    </select>
                </div>

            </div>
        </div>
    </template>

    <script>
        flatpickr("#opening_date", { dateFormat: "Y-m-d", locale: "es", allowInput: true });
        flatpickr("#closing_date", { dateFormat: "Y-m-d", locale: "es", allowInput: true });

        const pasosWrapper = document.getElementById('pasosWrapper');
        const btnAddPaso = document.getElementById('btnAddPaso');
        const tplPaso = document.getElementById('tplPaso').content;

        function rebuildNextStepOptions() {
            const total = pasosWrapper.querySelectorAll('.step-card').length;

            pasosWrapper.querySelectorAll('.step-card').forEach((card, idx) => {
                const current = idx + 1;
                const sel = card.querySelector('.sel-next');
                if (!sel) return;

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

        btnAddPaso.addEventListener('click', addPaso);

        pasosWrapper.addEventListener('click', (e) => {
            const btn = e.target.closest('.btnRemovePaso');
            if (!btn) return;

            const card = btn.closest('.step-card');
            if (card) card.remove();

            renumberSteps();
        });

        rebuildNextStepOptions();
    </script>

</x-layouts.app>
