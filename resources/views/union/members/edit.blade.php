{{-- 
* Nombre de la vista           : members-edit.blade.php
* Descripción de la vista      : Formulario para editar los datos de un trabajador del Sindicato.
* Fecha de creación            : 04/11/2025
* Elaboró                      : Iker Piza
* Fecha de liberación          : 04/11/2025
* Autorizó                     : Líder Técnico
* Versión                      : 1.3
* Fecha de mantenimiento       : 27/11/2025
* Folio de mantenimiento       : N/A
* Tipo de mantenimiento        : Correctivo y perfectivo
* Descripción del mantenimiento: Homologación con create, estandarización Flux UI y colorset PRO-Laravel V3.4.
* Responsable                  : Iker Piza
* Revisor                      : QA SINDISOFT
--}}

<x-layouts.app :title="__('Editar trabajador')">

    <div class="w-full flex flex-col items-center justify-start min-h-[80vh] bg-white text-black p-6">

        <div class="w-full max-w-3xl flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6">
            <div>
                <h1 class="text-3xl font-bold text-[#DE6601] mb-1">Editar Trabajador</h1>
                <p class="text-[#241178] text-sm">
                    Actualiza los datos del trabajador seleccionado.
                </p>
            </div>

            <flux:button
                icon="arrow-long-left"
                icon-variant="outline"
                variant="ghost"
                :href="route('union.members.index')"
                class="px-4 py-2 !bg-transparent hover:!bg-zinc-100 !text-[#241178] font-semibold rounded-lg mt-3 sm:mt-0"
            >
                Volver
            </flux:button>
        </div>

        <form 
            action="{{ route('union.members.update', $worker->id) }}" 
            method="POST"
            class="w-full max-w-3xl bg-white border border-[#D9D9D9] shadow-md rounded-2xl p-8 space-y-8"
        >

            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <flux:input
                    name="name"
                    :label="__('Nombre completo')"
                    type="text"
                    required
                    value="{{ old('name', $worker->name) }}"
                />

                <flux:input
                    name="email"
                    :label="__('Correo electrónico')"
                    type="email"
                    required
                    value="{{ old('email', $worker->email) }}"
                    placeholder="correo@ejemplo.com"
                />
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">

                <flux:input
                    name="curp"
                    :label="__('CURP')"
                    type="text"
                    value="{{ old('curp', $worker->curp) }}"
                    placeholder="PEGA850101HDFRRN09"
                />

                <flux:input
                    name="rfc"
                    :label="__('RFC')"
                    type="text"
                    value="{{ old('rfc', $worker->rfc) }}"
                    placeholder="PEGA850101XXX"
                />
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">

                <flux:select
                    name="gender"
                    :label="__('Sexo / Género')"
                >
                    <option value="">Selecciona</option>
                    <option value="H"  {{ old('gender', $worker->gender) == 'H' ? 'selected' : '' }}>Hombre</option>
                    <option value="M"  {{ old('gender', $worker->gender) == 'M' ? 'selected' : '' }}>Mujer</option>
                    <option value="ND" {{ old('gender', $worker->gender) == 'ND' ? 'selected' : '' }}>No definido</option>
                    <option value="X"  {{ old('gender', $worker->gender) == 'X' ? 'selected' : '' }}>Prefiero no decirlo</option>
                </flux:select>

                <flux:input
                    name="budget_key"
                    :label="__('Clave presupuestal')"
                    type="text"
                    value="{{ old('budget_key', $worker->budget_key) }}"
                    placeholder="123-ABC"
                />
            </div>

            <flux:select
                name="active"
                :label="__('Estado')"
            >
                <option value="1" {{ $worker->active ? 'selected' : '' }}>Activo</option>
                <option value="0" {{ !$worker->active ? 'selected' : '' }}>Inactivo</option>
            </flux:select>

            <div class="flex flex-col sm:flex-row justify-end gap-4 mt-4">

                <flux:button
                    icon="x-circle"
                    icon-variant="outline"
                    variant="ghost"
                    :href="route('union.members.index')"
                    class="!bg-zinc-200 hover:!bg-zinc-300 !text-zinc-700 px-6 py-2 font-semibold rounded-lg"
                >
                    Cancelar
                </flux:button>

                <flux:button
                    icon="check-circle"
                    icon-variant="outline"
                    variant="primary"
                    type="submit"
                    class="!bg-blue-600 hover:!bg-blue-700 !text-white font-semibold rounded-lg"
                >
                    Guardar cambios
                </flux:button>

            </div>

        </form>

    </div>

</x-layouts.app>
