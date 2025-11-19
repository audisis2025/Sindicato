<x-layouts.app :title="__('Editar usuario')">
    <div class="w-full flex flex-col items-center justify-center min-h-[80vh] bg-white text-black p-6">

        <h1 class="text-3xl font-[Poppins] font-bold text-[#DE6601] mb-2">
            Editar Usuario
        </h1>
        <p class="text-[#241178] font-[Inter] mb-8">
            Modifica los datos del usuario seleccionado.
        </p>

        <form action="{{ route('users.update', $user->id) }}" method="POST"
              class="w-full max-w-2xl bg-white border border-[#D9D9D9] shadow-md rounded-2xl p-8 space-y-6 font-[Inter]">
            @csrf
            @method('PUT')

            <div>
                <label for="name" class="block font-semibold text-[#272800] mb-1">Nombre completo</label>
                <input type="text" name="name" id="name" required
                       class="w-full border border-[#D9D9D9] rounded-lg px-4 py-2 focus:ring-2 focus:ring-[#DE6601] outline-none"
                       value="{{ old('name', $user->name) }}">
                @error('name')
                    <p class="text-[#EE0000] text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="username" class="block font-semibold text-[#272800] mb-1">Usuario</label>
                <input type="text" name="username" id="username" required
                       class="w-full border border-[#D9D9D9] rounded-lg px-4 py-2 focus:ring-2 focus:ring-[#DE6601] outline-none"
                       value="{{ old('username', $user->username) }}">
                @error('username')
                    <p class="text-[#EE0000] text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="email" class="block font-semibold text-[#272800] mb-1">Correo electrónico</label>
                <input type="email" name="email" id="email"
                       class="w-full border border-[#D9D9D9] rounded-lg px-4 py-2 focus:ring-2 focus:ring-[#DE6601] outline-none"
                       value="{{ old('email', $user->email) }}">
                @error('email')
                    <p class="text-[#EE0000] text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="role" class="block font-semibold text-[#272800] mb-1">Rol del usuario</label>
                <select name="role" id="role" required
                        class="w-full border border-[#D9D9D9] rounded-lg px-4 py-2 focus:ring-2 focus:ring-[#DE6601] outline-none">
                    <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>Administrador</option>
                    <option value="union" {{ old('role', $user->role) == 'union' ? 'selected' : '' }}>Usuario Sindicato</option>
                    <option value="worker" {{ old('role', $user->role) == 'worker' ? 'selected' : '' }}>Usuario Trabajador</option>
                </select>
                @error('role')
                    <p class="text-[#EE0000] text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

                <div>
                    <label for="curp" class="block font-semibold text-[#272800] mb-1">CURP</label>
                    <input type="text" name="curp" id="curp"
                           class="w-full border border-[#D9D9D9] rounded-lg px-4 py-2 focus:ring-2 focus:ring-[#DE6601] outline-none"
                           value="{{ old('curp', $user->curp) }}">
                </div>

                <div>
                    <label for="rfc" class="block font-semibold text-[#272800] mb-1">RFC</label>
                    <input type="text" name="rfc" id="rfc"
                           class="w-full border border-[#D9D9D9] rounded-lg px-4 py-2 focus:ring-2 focus:ring-[#DE6601] outline-none"
                           value="{{ old('rfc', $user->rfc) }}">
                </div>

                <div>
                    <label for="gender" class="block font-semibold text-[#272800] mb-1">Sexo</label>
                    <select name="gender" id="gender"
                            class="w-full border border-[#D9D9D9] rounded-lg px-4 py-2 focus:ring-2 focus:ring-[#DE6601] outline-none">
                        <option value="">Selecciona</option>
                        <option value="H" {{ old('gender', $user->gender) == 'H' ? 'selected' : '' }}>Hombre</option>
                        <option value="M" {{ old('gender', $user->gender) == 'M' ? 'selected' : '' }}>Mujer</option>
                    </select>
                </div>

                <div>
                    <label for="budget_key" class="block font-semibold text-[#272800] mb-1">Clave presupuestal</label>
                    <input type="text" name="budget_key" id="budget_key"
                           class="w-full border border-[#D9D9D9] rounded-lg px-4 py-2 focus:ring-2 focus:ring-[#DE6601] outline-none"
                           value="{{ old('budget_key', $user->budget_key) }}">
                </div>

            </div>

            <div>
                <label for="active" class="block font-semibold text-[#272800] mb-1">Estado</label>
                <select name="active" id="active" required
                        class="w-full border border-[#D9D9D9] rounded-lg px-4 py-2 focus:ring-2 focus:ring-[#DE6601] outline-none">
                    <option value="1" {{ old('active', $user->active) == 1 ? 'selected' : '' }}>Activo</option>
                    <option value="0" {{ old('active', $user->active) == 0 ? 'selected' : '' }}>Inactivo</option>
                </select>
            </div>

            <div class="flex flex-col sm:flex-row justify-end gap-4 mt-8">
                <a href="{{ route('users.index') }}"
                   class="px-6 py-2 bg-[#241178]/10 text-[#241178] hover:bg-[#241178]/20 font-semibold rounded-lg transition text-center">
                    Cancelar
                </a>

                <button type="submit"
                        class="px-6 py-2 bg-[#DE6601] hover:bg-[#EE0000] text-white font-semibold rounded-lg transition">
                    Guardar cambios
                </button>
            </div>

        </form>
    </div>
</x-layouts.app>
