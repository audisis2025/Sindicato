{{-- 
* Nombre de la vista          : create.blade.php
* Descripción de la vista     : Vista para la creación de publicaciones del sindicato (noticias, comunicados,
*                               convocatorias y eventos), permitiendo capturar título, contenido, fechas,
*                               tipo/estado y adjuntar archivo PDF o imagen de portada.
* Fecha de creación           : 19/11/2025
* Elaboró                     : Iker Piza
* Fecha de liberación         : 14/01/2026
* Autorizó                    : Salvador Monroy
* Versión                     : 1.0
* Fecha de mantenimiento      :
* Folio de mantenimiento      :
* Tipo de mantenimiento       :
* Descripción del mantenimiento:
* Responsable                 :
* Revisor                     :
--}}

<x-layouts.app :title="__('Nueva publicación')">

    <div class="w-full flex flex-col items-center justify-center min-h-[80vh] bg-white text-black p-6">

        <div class="w-full max-w-4xl flex items-center justify-between mb-6">
            <h1 class="text-3xl font-bold text-[#DE6601]">
                Crear nueva publicación
            </h1>

            <flux:button icon="arrow-long-left" icon-variant="outline" variant="ghost" :href="route('union.news.index')">
                Regresar
            </flux:button>
        </div>

        <form method="POST" action="{{ route('union.news.store') }}" enctype="multipart/form-data"
            class="w-full max-w-4xl bg-white border border-[#D9D9D9] shadow-md rounded-2xl p-8 space-y-6">
            @csrf

            <flux:input name="title" :label="__('Título')" type="text" required maxlength="255"
                value="{{ old('title') }}" placeholder="Convocatoria Becas 2025" />

            <div>
                <label for="content" class="block text-sm font-semibold text-[#272800] mb-1">
                    Contenido
                </label>
                <textarea id="content" name="content" rows="4" required maxlength="5000"
                    class="w-full border border-zinc-300 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-blue-600 outline-none"
                    placeholder="Redacta el contenido completo de la publicación">{{ old('content') }}</textarea>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <flux:input name="publication_date" :label="__('Fecha de publicación')" type="date" required
                    value="{{ old('publication_date') }}" />

                <flux:input name="expiration_date" :label="__('Fecha de vigencia (opcional)')" type="date"
                    value="{{ old('expiration_date') }}" />
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <flux:select name="type" :label="__('Tipo de publicación')" required>
                    <option value="">Selecciona</option>
                    <option value="announcement" {{ old('type') === 'announcement' ? 'selected' : '' }}>Convocatoria
                    </option>
                    <option value="communication" {{ old('type') === 'communication' ? 'selected' : '' }}>Comunicado
                    </option>
                    <option value="event" {{ old('type') === 'event' ? 'selected' : '' }}>Evento</option>
                </flux:select>

                <flux:select name="status" :label="__('Estado')" required>
                    <option value="">Selecciona</option>
                    <option value="published" {{ old('status') === 'published' ? 'selected' : '' }}>Publicada
                    </option>
                    <option value="draft" {{ old('status') === 'draft' ? 'selected' : '' }}>Borrador</option>
                </flux:select>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div>
                    <label for="attachment" class="block text-sm font-semibold text-[#272800] mb-1">
                        Archivo adjunto (opcional)
                    </label>
                    <input id="attachment" name="attachment" type="file" accept="application/pdf"
                        class="w-full border border-zinc-300 rounded-lg px-3 py-2 text-sm text-black">
                </div>

                <div>
                    <label for="image" class="block text-sm font-semibold text-[#272800] mb-1">
                        Imagen de portada (opcional)
                    </label>
                    <input id="image" name="image" type="file" accept=".jpg,.jpeg,.png,.webp"
                        class="w-full border border-zinc-300 rounded-lg px-3 py-2 text-sm text-black">
                </div>
            </div>

            <div class="flex flex-col sm:flex-row justify-end gap-4 pt-4 border-t border-zinc-200 mt-4">

                <flux:button icon="x-circle" icon-variant="outline" variant="primary" :href="route('union.news.index')"
                    class="!bg-red-600 hover:!bg-red-700 !text-white">
                    Cancelar
                </flux:button>

                <flux:button icon="check-circle" icon-variant="outline" variant="primary" type="submit"
                    class="!bg-green-600 hover:!bg-green-700 !text-white">
                    Guardar publicación
                </flux:button>

            </div>

        </form>
    </div>

</x-layouts.app>
