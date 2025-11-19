<x-layouts.app :title="__('Alta de usuario')">

    <div class="w-full flex flex-col items-center justify-center min-h-[80vh] bg-white text-black p-6">

        <h1 class="text-3xl font-[Poppins] font-bold text-[#DE6601] mb-2">
            Alta de Usuario
        </h1>

        <p class="text-[#272800] font-[Inter] mb-6">
            Completa los datos para registrar un nuevo usuario en el sistema.
        </p>

        <form action="{{ route('users.store') }}" method="POST"
              class="w-full max-w-2xl bg-white border border-[#D9D9D9] shadow-md rounded-2xl p-8 space-y-6 font-[Inter]">

            @csrf

            <!-- Nombre y Usuario -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block font-semibold text-[#272800] mb-1">Nombre completo</label>
                    <input type="text" name="name" required
                           class="w-full border border-[#D9D9D9] rounded-lg px-4 py-2 
                           focus:ring-2 focus:ring-[#DE6601] outline-none"
                           placeholder="Ejemplo: Juan Pérez" value="{{ old('name') }}">
                    @error('name') <p class="text-[#EE0000] text-sm">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block font-semibold text-[#272800] mb-1">Usuario</label>
                    <input type="text" name="username" required
                           class="w-full border border-[#D9D9D9] rounded-lg px-4 py-2 
                           focus:ring-2 focus:ring-[#DE6601] outline-none"
                           placeholder="Ejemplo: jperez61" value="{{ old('username') }}">
                    @error('username') <p class="text-[#EE0000] text-sm">{{ $message }}</p> @enderror
                </div>
            </div>

            <!-- Email y Contraseña -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block font-semibold text-[#272800] mb-1">Correo electrónico</label>
                    <input type="email" name="email"
                           class="w-full border border-[#D9D9D9] rounded-lg px-4 py-2 
                           focus:ring-2 focus:ring-[#DE6601] outline-none"
                           placeholder="correo@ejemplo.com" value="{{ old('email') }}">
                    @error('email') <p class="text-[#EE0000] text-sm">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block font-semibold text-[#272800] mb-1">Contraseña</label>
                    <input type="password" name="password" required
                           class="w-full border border-[#D9D9D9] rounded-lg px-4 py-2 
                           focus:ring-2 focus:ring-[#DE6601] outline-none"
                           placeholder="********">
                    @error('password') <p class="text-[#EE0000] text-sm">{{ $message }}</p> @enderror
                </div>
            </div>

            <!-- Rol -->
            <div>
                <label class="block font-semibold text-[#272800] mb-1">Rol del usuario</label>
                <select name="role" required
                        class="w-full border border-[#D9D9D9] rounded-lg px-4 py-2 
                        focus:ring-2 focus:ring-[#DE6601] outline-none">
                    <option value="">Selecciona una opción</option>
                    <option value="union" {{ old('role') == 'union' ? 'selected' : '' }}>Usuario Sindicato</option>
                    <option value="worker" {{ old('role') == 'worker' ? 'selected' : '' }}>Usuario Trabajador</option>
                </select>
                @error('role') <p class="text-[#EE0000] text-sm">{{ $message }}</p> @enderror
            </div>

            <!-- Datos adicionales -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block font-semibold text-[#272800] mb-1">CURP</label>
                    <input type="text" name="curp"
                           class="w-full border border-[#D9D9D9] rounded-lg px-4 py-2 
                           focus:ring-2 focus:ring-[#DE6601] outline-none"
                           placeholder="PEGA850101HDFRRN09" value="{{ old('curp') }}">
                </div>

                <div>
                    <label class="block font-semibold text-[#272800] mb-1">RFC</label>
                    <input type="text" name="rfc"
                           class="w-full border border-[#D9D9D9] rounded-lg px-4 py-2 
                           focus:ring-2 focus:ring-[#DE6601] outline-none"
                           placeholder="PEGA850101XXX" value="{{ old('rfc') }}">
                </div>

                <div>
                    <label class="block font-semibold text-[#272800] mb-1">Sexo</label>
                    <select name="gender"
                            class="w-full border border-[#D9D9D9] rounded-lg px-4 py-2 
                            focus:ring-2 focus:ring-[#DE6601] outline-none">
                        <option value="">Selecciona</option>
                        <option value="H" {{ old('gender') == 'H' ? 'selected' : '' }}>Hombre</option>
                        <option value="M" {{ old('gender') == 'M' ? 'selected' : '' }}>Mujer</option>
                    </select>
                </div>

                <div>
                    <label class="block font-semibold text-[#272800] mb-1">Clave presupuestal</label>
                    <input type="text" name="budget_key"
                           class="w-full border border-[#D9D9D9] rounded-lg px-4 py-2 
                           focus:ring-2 focus:ring-[#DE6601] outline-none"
                           placeholder="123-ABC" value="{{ old('budget_key') }}">
                </div>
            </div>

            <!-- Acciones -->
            <div class="flex flex-col sm:flex-row justify-end gap-4 mt-6">
                <a href="{{ route('users.index') }}"
                   class="px-6 py-2 bg-[#DE6601]/10 text-[#DE6601] hover:bg-[#DE6601]/20 
                          font-semibold rounded-lg transition text-center">
                    Cancelar
                </a>

                <button type="submit"
                        class="px-6 py-2 bg-[#DE6601] hover:bg-[#EE0000] text-white 
                               font-semibold rounded-lg transition">
                    Guardar usuario
                </button>
            </div>

        </form>
    </div>

</x-layouts.app>
