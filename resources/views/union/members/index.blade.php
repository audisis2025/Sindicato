{{-- ===========================================================
 Nombre de la clase: members-index.blade.php
 Descripción: Vista para listar y administrar trabajadores registrados por el Sindicato.
 Fecha de creación: 02/11/2025
 Elaboró: Iker Piza
 Fecha de liberación: 02/11/2025
 Autorizó: Líder Técnico
 Versión: 1.1
 Tipo de mantenimiento: Adaptación.
 Descripción del mantenimiento: Se adaptó el listado de usuarios para gestión de trabajadores del Sindicato conforme al Manual PRO-Laravel V3.2.
 Responsable: Iker Piza
 Revisor: QA SINDISOFT
=========================================================== --}}

<x-layouts.app :title="__('Trabajadores registrados')">
    <div class="flex flex-col gap-6 p-6 w-full">

        <!-- 🔸 Título y botón de alta -->
        <div class="flex justify-between items-center">
            <h1 class="text-3xl font-[Poppins] font-bold text-[#DC6601]">
                Gestión de Trabajadores
            </h1>

            <a href="{{ route('union.members.create') }}"
                class="bg-[#DC6601] hover:bg-[#EE0000] text-white font-semibold py-2 px-4 rounded-lg transition">
                + Registrar nuevo trabajador
            </a>
        </div>

        <!-- 📋 Tabla de trabajadores -->
        <div class="overflow-x-auto bg-white border border-[#D9D9D9] rounded-2xl shadow-md">
            <table class="w-full border-collapse text-sm font-[Inter]">
                <thead class="bg-[#241178] text-white">
                    <tr>
                        <th class="p-2 text-left">Nombre</th>
                        <th class="p-2 text-left">Correo</th>
                        <th class="p-2 text-left">CURP</th>
                        <th class="p-2 text-left">RFC</th>
                        <th class="p-2 text-left">Sexo</th>
                        <th class="p-2 text-left">Clave presupuestal</th>
                        <th class="p-2 text-center">Estado</th>
                        <th class="p-2 text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($workers as $worker)
                        <tr class="border-t border-[#272800]/10 hover:bg-[#F9F9F9] transition">
                            <td class="p-2 max-w-[120px] truncate text-sm text-[#000000]" title="{{ $worker->name }}">
                                {{ $worker->name ?? '—' }}
                            </td>
                            <td class="p-2">{{ $worker->email ?? '—' }}</td>
                            <td class="p-2 max-w-[140px] truncate text-sm text-[#000000]"
                                title="{{ $worker->detalle->curp }}">
                                {{ $worker->detalle->curp ?? '—' }}
                            </td>
                            <td class="p-2 max-w-[120px] truncate text-sm text-[#000000]"
                                title="{{ $worker->detalle->rfc }}">
                                {{ $worker->detalle->rfc ?? '—' }}
                            </td>
                            <td class="p-2">
                                @if ($worker->detalle->sexo === 'H')
                                    Hombre
                                @elseif ($worker->detalle->sexo === 'M')
                                    Mujer
                                @else
                                    —
                                @endif
                            </td>
                            <td class="p-2 max-w-[100px] truncate text-sm text-[#000000]"
                                title="{{ $worker->detalle->clave_presupuestal }}">
                                {{ $worker->detalle->clave_presupuestal ?? '—' }}
                            </td>
                            <td class="p-2 text-center">
                                @if ($worker->activo)
                                    <span class="text-green-600 font-semibold">Activo</span>
                                @else
                                    <span class="text-red-600 font-semibold">Inactivo</span>
                                @endif
                            </td>

                            <!-- 🛠️ Acciones -->
                            <td class="p-2 flex flex-wrap gap-2 justify-center">
                                <!-- Editar -->
                                <a href="{{ route('union.members.edit', $worker->id) }}"
                                    class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded-md text-sm transition">
                                    Editar
                                </a>


                                <!-- Eliminar -->
                                <form action="{{ route('union.members.destroy', $worker->id) }}" method="POST"
                                    onsubmit="return confirm('⚠️ ¿Seguro que deseas eliminar este trabajador?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded-md text-sm transition">
                                        Eliminar
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-4 text-gray-500">
                                No hay trabajadores registrados.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-layouts.app>
