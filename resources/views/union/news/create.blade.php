{{-- ===========================================================
 Nombre de la vista: create.blade.php
 Descripción: Formulario de creación de nuevas noticias, avisos o convocatorias.
 Fecha de creación: 03/11/2025
 Elaboró: Iker Piza
 Fecha de liberación: 03/11/2025
 Autorizó: Líder Técnico
 Versión: 1.0
 Tipo de mantenimiento: Creación inicial.
 Descripción del mantenimiento: Maquetación del formulario de publicación conforme
 al Manual PRO-Laravel V3.2 y estándares Flux UI.
 Responsable: Iker Piza
 Revisor: QA SINDISOFT
=========================================================== --}}

<x-layouts.app :title="__('Nueva publicación')">
    <div class="max-w-5xl mx-auto p-6">

        <!-- 🔸 Título principal -->
        <div class="flex items-center justify-between mb-6">
            <h1 class="text-3xl font-[Poppins] font-bold text-[#DC6601]">
                Crear nueva publicación
            </h1>
            <a href="{{ route('union.news.index') }}"
               class="flex items-center gap-2 text-[#241178] hover:text-[#DC6601] font-semibold transition">
                <x-heroicon-o-arrow-long-left class="w-5 h-5" />
                Volver al listado
            </a>
        </div>

        <!-- 🧾 Formulario -->
        <form method="POST" action="#" enctype="multipart/form-data"
              class="bg-white border border-[#D9D9D9]/60 rounded-xl shadow-sm p-6 space-y-5">
            @csrf

            <!-- 📝 Título -->
            <div>
                <label for="title" class="block text-sm font-semibold text-[#241178] mb-1">
                    Título
                </label>
                <input type="text" id="title" name="title"
                       placeholder="Ej. Convocatoria Becas 2025"
                       class="w-full border border-[#D9D9D9] rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#DC6601]" />
            </div>

            <!-- 📄 Descripción breve -->
            <div>
                <label for="description" class="block text-sm font-semibold text-[#241178] mb-1">
                    Descripción breve
                </label>
                <textarea id="description" name="description" rows="3"
                          placeholder="Resumen o propósito de la publicación"
                          class="w-full border border-[#D9D9D9] rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#DC6601]"></textarea>
            </div>

            <!-- 📅 Fechas -->
            <div class="grid sm:grid-cols-2 gap-4">
                <div>
                    <label for="publication_date" class="block text-sm font-semibold text-[#241178] mb-1">
                        Fecha de publicación
                    </label>
                    <input type="date" id="publication_date" name="publication_date"
                           class="w-full border border-[#D9D9D9] rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#DC6601]" />
                </div>

                <div>
                    <label for="expiration_date" class="block text-sm font-semibold text-[#241178] mb-1">
                        Fecha de vigencia (opcional)
                    </label>
                    <input type="date" id="expiration_date" name="expiration_date"
                           class="w-full border border-[#D9D9D9] rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#DC6601]" />
                </div>
            </div>

            <!-- 📂 Categoría -->
            <div>
                <label for="category" class="block text-sm font-semibold text-[#241178] mb-1">
                    Categoría
                </label>
                <select id="category" name="category"
                        class="w-full border border-[#D9D9D9] rounded-lg px-3 py-2 bg-white focus:outline-none focus:ring-2 focus:ring-[#DC6601]">
                    <option value="">Seleccione una categoría</option>
                    <option value="noticia">Noticia general</option>
                    <option value="convocatoria">Convocatoria</option>
                    <option value="aviso">Aviso urgente</option>
                </select>
            </div>

            <!-- 📎 Archivo adjunto -->
            <div>
                <label for="attachment" class="block text-sm font-semibold text-[#241178] mb-1">
                    Archivo adjunto (opcional)
                </label>
                <input type="file" id="attachment" name="attachment"
                       class="w-full border border-[#D9D9D9] rounded-lg px-3 py-2 text-sm text-[#241178]" />
            </div>

            <!-- 🖼️ Imagen de portada -->
            <div>
                <label for="cover_image" class="block text-sm font-semibold text-[#241178] mb-1">
                    Imagen de portada (opcional)
                </label>
                <input type="file" id="cover_image" name="cover_image"
                       class="w-full border border-[#D9D9D9] rounded-lg px-3 py-2 text-sm text-[#241178]" />
            </div>

            <!-- 🟢 Estado -->
            <div>
                <label for="status" class="block text-sm font-semibold text-[#241178] mb-1">
                    Estado
                </label>
                <select id="status" name="status"
                        class="w-full border border-[#D9D9D9] rounded-lg px-3 py-2 bg-white focus:outline-none focus:ring-2 focus:ring-[#DC6601]">
                    <option value="Publicada">Publicada</option>
                    <option value="Borrador">Borrador</option>
                    <option value="Archivada">Archivada</option>
                </select>
            </div>

            <!-- 🔘 Botones -->
            <div class="flex justify-end gap-4 pt-4 border-t border-[#E5E5E5]">
                <a href="{{ route('union.news.index') }}"
                   class="inline-flex items-center gap-2 bg-gray-200 hover:bg-gray-300 text-[#241178] font-semibold px-4 py-2 rounded-lg transition">
                    <x-heroicon-o-x-mark class="w-5 h-5" />
                    Cancelar
                </a>
                <button type="submit"
                        class="inline-flex items-center gap-2 bg-[#DC6601] hover:bg-[#241178] text-white font-semibold px-4 py-2 rounded-lg transition">
                    <x-heroicon-o-check-circle class="w-5 h-5" />
                    Guardar publicación
                </button>
            </div>
        </form>
    </div>
</x-layouts.app>
