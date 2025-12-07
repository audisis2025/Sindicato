{{-- 
* Nombre de la vista           : edit.blade.php
* Descripción de la vista      : Vista para editar un trámite creado por el Sindicato.
* Fecha de creación            : 03/11/2025
* Elaboró                      : Iker Piza
* Fecha de liberación          : 03/11/2025
* Autorizó                     : Líder Técnico
* Versión                      : 1.1
* Fecha de mantenimiento       : 27/11/2025
* Folio de mantenimiento       : N/A
* Tipo de mantenimiento        : Correctivo y perfectivo
* Descripción del mantenimiento: Homologación de estructura, tipografía e iconografía según Manual PRO-Laravel V3.4.
* Responsable                  : Iker Piza
* Revisor                      : QA SINDISOFT
--}}


<x-layouts.app :title="__('Editar trámite')">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://npmcdn.com/flatpickr/dist/l10n/es.js"></script>

    <div class="w-full flex flex-col items-center min-h-[80vh] bg-white p-6">

        <div class="w-full max-w-4xl flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6">
            <div>
                <h1 class="text-3xl font-bold text-[#DE6601] mb-1">Editar Trámite</h1>
                <p class="text-[#241178] text-sm">Actualiza la información del trámite y sus pasos.</p>
            </div>

            <flux:button icon-variant="outline" variant="ghost" :href="route('union.procedures.index')"
                class="px-4 py-2 !bg-transparent hover:!bg-zinc-100 !text-[#241178] font-semibold rounded-lg flex items-center gap-2">
                <x-heroicon-o-arrow-long-left class="w-5 h-5" />
                Volver
            </flux:button>
        </div>

        <form action="{{ route('union.procedures.update', $procedure->id) }}" method="POST"
            enctype="multipart/form-data"
            class="w-full max-w-4xl bg-white border border-zinc-300 shadow-md rounded-2xl p-8 space-y-8">

            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                <div>
                    <label class="block font-semibold text-[#272800] mb-1">Nombre del trámite</label>
                    <input type="text" name="name" required
                        class="w-full border border-zinc-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-600"
                        value="{{ old('name', $procedure->name) }}">
                </div>

                <div>
                    <label class="block font-semibold text-[#272800] mb-1">Tiempo estimado global (días)</label>
                    <input type="number" name="estimated_days" min="1"
                        class="w-full border border-zinc-300 rounded-lg px-4 py-2"
                        value="{{ old('estimated_days', $procedure->estimated_days) }}">
                </div>

                <div>
                    <label class="block font-semibold text-[#272800] mb-1">Fecha de apertura</label>
                    <input type="text" id="opening_date" name="opening_date"
                        class="w-full border border-zinc-300 rounded-lg px-4 py-2"
                        value="{{ old('opening_date', $procedure->opening_date) }}">
                </div>

                <div>
                    <label class="block font-semibold text-[#272800] mb-1">Fecha de cierre</label>
                    <input type="text" id="closing_date" name="closing_date"
                        class="w-full border border-zinc-300 rounded-lg px-4 py-2"
                        value="{{ old('closing_date', $procedure->closing_date) }}">
                </div>

            </div>

            <div>
                <label class="block font-semibold text-[#272800] mb-1">Descripción</label>
                <textarea name="description" rows="3"
                    class="w-full border border-zinc-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-600">{{ old('description', $procedure->description) }}</textarea>
            </div>

            <hr class="border-zinc-300">

            <h2 class="text-xl font-semibold text-[#241178] mb-4">Pasos del trámite</h2>

            <div id="pasosWrapper" class="space-y-4">
                @foreach ($procedure->steps->sortBy('order') as $index => $step)
                    <div class="border border-zinc-300 rounded-xl p-4">

                        <div class="flex justify-between mb-3">
                            <h3 class="font-semibold text-[#DE6601]">Paso {{ $step->order }}</h3>

                            <flux:button icon="trash" variant="danger" size="xs" type="button"
                                class="!bg-red-600 hover:!bg-red-700 !text-white btnRemovePaso">
                                Eliminar
                            </flux:button>
                        </div>

                        <input type="hidden" name="steps[{{ $index + 1 }}][order]" value="{{ $step->order }}">

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                            <div>
                                <label class="block font-semibold text-[#272800] mb-1">Nombre del paso</label>
                                <input type="text" name="steps[{{ $index + 1 }}][step_name]" required
                                    class="w-full border border-zinc-300 rounded-lg px-4 py-2"
                                    value="{{ $step->step_name }}">
                            </div>

                            <div class="md:col-span-2">
                                <label class="block font-semibold text-[#272800] mb-1">Descripción</label>
                                <textarea name="steps[{{ $index + 1 }}][step_description]" rows="2"
                                    class="w-full border border-zinc-300 rounded-lg px-4 py-2">{{ $step->step_description }}</textarea>
                            </div>

                            <div>
                                <label class="block font-semibold text-[#272800] mb-1">Archivo del paso</label>

                                @if ($step->file_path)
                                    <a href="{{ asset('storage/' . $step->file_path) }}" target="_blank"
                                        class="text-[#241178] underline text-sm">
                                        Ver archivo actual
                                    </a>
                                @else
                                    <p class="text-gray-500 text-sm">Sin archivo</p>
                                @endif

                                <input type="file" name="steps[{{ $index + 1 }}][file_path]"
                                    class="block w-full mt-2 text-sm text-[#272800] file:py-2 file:px-4 file:bg-zinc-200 hover:file:bg-zinc-300">
                            </div>

                            <div>
                                <label class="block font-semibold text-[#272800] mb-1">¿Requiere archivo?</label>
                                <select name="steps[{{ $index + 1 }}][requires_file]" required
                                    class="w-full border border-zinc-300 rounded-lg px-4 py-2">
                                    <option value="yes" @selected($step->requires_file)>Sí</option>
                                    <option value="no" @selected(!$step->requires_file)>No</option>
                                </select>
                            </div>

                            <div>
                                <label class="block font-semibold text-[#272800] mb-1">Si este paso falla, ir al
                                    paso…</label>
                                <select name="steps[{{ $index + 1 }}][next_step_if_fail]"
                                    class="w-full border border-zinc-300 rounded-lg px-4 py-2">
                                    <option value="">— Ninguno —</option>

                                    @for ($i = 1; $i <= $procedure->steps_count; $i++)
                                        @if ($i !== $step->order)
                                            <option value="{{ $i }}" @selected($step->next_step_if_fail == $i)>
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

            <div class="flex justify-end mt-4">
                <flux:button icon="plus" icon-variant="outline" variant="primary" type="button" id="btnAddPaso"
                    class="px-4 py-2 !bg-blue-600 hover:!bg-blue-700 !text-white">
                    Añadir paso
                </flux:button>
            </div>

            <div class="flex flex-col sm:flex-row justify-end gap-4 mt-8">
                <flux:button icon="x-circle" icon-variant="outline" variant="ghost"
                    :href="route('union.procedures.index')"
                    class="px-6 py-2 !bg-transparent hover:!bg-zinc-100 !text-[#241178] rounded-lg">
                    Cancelar
                </flux:button>

                <flux:button icon="check-circle" icon-variant="outline" variant="primary" type="submit"
                    class="px-6 py-2 !bg-blue-600 hover:!bg-blue-700 !text-white font-semibold rounded-lg flex items-center gap-2">
                    Guardar cambios
                </flux:button>
            </div>

        </form>
    </div>

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
        const btnAddPaso = document.getElementById('btnAddPaso');

        btnAddPaso.addEventListener('click', () => {
            const idx = pasosWrapper.children.length + 1;

            const html = `
                        <div class="border border-zinc-300 rounded-xl p-4">
                            <div class="flex justify-between mb-3">
                                <h3 class="font-semibold text-[#DE6601]">Paso ${idx}</h3>

                                <button type="button"
                                    class="btnRemovePaso !bg-red-600 hover:!bg-red-700 !text-white px-3 py-1 text-sm rounded-md">
                                    Eliminar
                                </button>
                            </div>

                            <input type="hidden" name="steps[${idx}][order]" value="${idx}">

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                                <div>
                                    <label class="block font-semibold mb-1 text-[#272800]">Nombre del paso</label>
                                    <input type="text" name="steps[${idx}][step_name]" required
                                        class="w-full border border-zinc-300 rounded-lg px-4 py-2">
                                </div>

                                <div class="md:col-span-2">
                                    <label class="block font-semibold mb-1 text-[#272800]">Descripción</label>
                                    <textarea name="steps[${idx}][step_description]" rows="2"
                                            class="w-full border border-zinc-300 rounded-lg px-4 py-2"></textarea>
                                </div>

                                <div>
                                    <label class="block font-semibold mb-1 text-[#272800]">Archivo del paso</label>
                                    <input type="file" name="steps[${idx}][file_path]"
                                        class="block w-full text-sm text-[#272800] file:py-2 file:px-4 file:bg-zinc-200 hover:file:bg-zinc-300">
                                </div>

                                <div>
                                    <label class="block font-semibold mb-1 text-[#272800]">¿Requiere archivo?</label>
                                    <select name="steps[${idx}][requires_file]" required
                                            class="w-full border border-zinc-300 rounded-lg px-4 py-2">
                                        <option value="yes">Sí</option>
                                        <option value="no">No</option>
                                    </select>
                                </div>

                                <div>
                                    <label class="block font-semibold mb-1 text-[#272800]">Si este paso falla, ir al paso…</label>
                                    <select name="steps[${idx}][next_step_if_fail]"
                                            class="w-full border border-zinc-300 rounded-lg px-4 py-2">
                                        <option value="">— Ninguno —</option>
                                    </select>
                                </div>

                            </div>
                        </div>
                        `;

            pasosWrapper.insertAdjacentHTML('beforeend', html);
        });

        pasosWrapper.addEventListener('click', (e) => {
            if (e.target.classList.contains('btnRemovePaso')) {
                e.target.closest('div.border').remove();
            }
        });
    </script>

</x-layouts.app>
