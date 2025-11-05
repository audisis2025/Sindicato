{{-- ===========================================================
 Nombre de la vista: configuration.blade.php
 Descripción: Panel de configuración general del sistema SINDISOFT.
 Fecha de creación: 02/11/2025
 Versión: 1.1
 Tipo de mantenimiento: Refinamiento.
 Descripción: Ajuste del módulo de configuración, eliminando modo mantenimiento
 y color institucional. Incluye respaldo simulado y bitácora.
=========================================================== --}}

<x-layouts.app :title="__('Configuración del Sistema')">
    <div class="w-full min-h-[80vh] bg-[#FFFFFF] text-[#000000] p-8 font-[Inter]">

        <!-- 🏷️ Encabezado -->
        <div class="mb-8 text-center">
            <h1 class="text-3xl font-[Poppins] font-bold text-[#DC6601]">
                Configuración del Sistema
            </h1>
            <p class="text-[#241178] mt-2 text-base">
                Administra los parámetros generales de SINDISOFT
            </p>
            <div class="w-20 h-[3px] bg-[#DC6601] mx-auto mt-3 rounded-full"></div>
        </div>

        <!-- 🧭 Mensaje de estado -->
        @if (session('status'))
            <div
                class="mb-6 w-full max-w-2xl mx-auto bg-[#DC6601]/10 border border-[#DC6601]/40
                        text-[#DC6601] font-semibold rounded-xl p-4 shadow-sm text-center">
                {{ session('status') }}
            </div>
        @endif

        <!-- ⚙️ Formulario de configuración -->
        <div class="max-w-3xl mx-auto bg-[#FFFFFF] border border-[#D9D9D9] rounded-2xl shadow-md p-8">
            <form method="POST" action="{{ route('admin.configuration.update') }}">
                @csrf
                @method('PUT')

                <!-- Nombre del sistema -->
                <div class="mb-6">
                    <label for="app_name" class="block font-semibold text-[#000000] mb-2">
                        Nombre del sistema
                    </label>
                    <input type="text" id="app_name" name="app_name"
                        value="{{ old('app_name', config('app.name', 'SINDISOFT')) }}"
                        class="w-full border border-[#272800]/40 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#DC6601]" />
                </div>

                <!-- Correo institucional -->
                <div class="mb-6">
                    <label for="admin_email" class="block font-semibold text-[#000000] mb-2">
                        Correo institucional
                    </label>
                    <input type="email" id="admin_email" name="admin_email" value="{{ old('admin_email') }}"
                        placeholder="ejemplo@sindisoft.mx"
                        class="w-full border border-[#272800]/40 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#DC6601]" />
                </div>

                <!-- Tiempo de sesión -->
                <div class="mb-6">
                    <label for="session_timeout" class="block font-semibold text-[#000000] mb-2">
                        Tiempo de sesión (minutos)
                    </label>
                    <input type="number" id="session_timeout" name="session_timeout"
                        value="{{ old('session_timeout', session('session_timeout', 30)) }}" min="5"
                        max="120"
                        class="w-full border border-[#272800]/40 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-[#DC6601]" />
                </div>

                <!-- Botones -->
                <div class="flex flex-col sm:flex-row justify-center sm:justify-end gap-4 mt-8">
                    <button type="reset"
                        class="px-6 py-2 rounded-lg border border-[#241178] text-[#241178] hover:bg-[#241178]/10 transition">
                        Restablecer
                    </button>
                    <button type="submit"
                        class="px-6 py-2 rounded-lg bg-[#DC6601] text-white font-semibold hover:bg-[#EE0000] transition">
                        Guardar cambios
                    </button>
                </div>
            </form>

            <!-- 🔹 Acción: respaldo del sistema -->
            <form method="POST" action="{{ route('admin.configuration.backup') }}" class="mt-6 text-center">
                @csrf
                <button type="submit"
                    class="px-6 py-2 rounded-lg border border-[#DC6601] text-[#DC6601] hover:bg-[#DC6601]/10 transition font-semibold">
                    Generar respaldo del sistema
                </button>
            </form>
        </div>

        <!-- 📘 Pie -->
        <footer class="mt-10 text-center text-sm text-[#272800] leading-tight">
            © {{ date('Y') }} Sindicato Nacional de Trabajadores de la Educación – Sección 61<br>
            <span class="text-[#241178]">Sistema SINDISOFT</span> | Configuración Administrativa v1.1
        </footer>
    </div>
</x-layouts.app>
