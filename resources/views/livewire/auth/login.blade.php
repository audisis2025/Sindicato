<?php
/*
 * Nombre de la clase         : login.blade.php
 * Descripción de la clase    : Vista Livewire Volt para inicio de sesión de usuarios.
 * Fecha de creación          : 01/11/2025
 * Elaboró                    : Iker Piza
 * Fecha de liberación        : 01/11/2025
 * Autorizó                   : Líder Técnico
 * Versión                    : 2.0
 * Fecha de mantenimiento     : 25/11/2025
 * Folio de mantenimiento     : N/A
 * Tipo de mantenimiento      : Correctivo y perfectivo
 * Descripción del mantenimiento : Ajuste de validaciones, mensajes SweetAlert, homogeneización visual.
 * Responsable                : Iker Piza
 * Revisor                    : QA SINDISOFT
 */

use App\Models\User;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Validate;
use Livewire\Volt\Component;

new #[Layout('components.layouts.auth')] class extends Component {
    #[Validate('required|string|email')]
    public string $email = '';

    #[Validate('required|string')]
    public string $password = '';

    public bool $remember = false;

    public function login(): void
    {
        $this->validate();
        $this->ensureIsNotRateLimited();

        $user = $this->validateCredentials();

        Auth::login($user, $this->remember);
        RateLimiter::clear($this->throttleKey());
        Session::regenerate();

        $this->dispatch('show-swal', icon: 'success', title: 'Bienvenido', text: 'Inicio de sesión correcto.');

        $this->redirectIntended(default: route('dashboard'), navigate: true);
    }

    protected function validateCredentials(): User
    {
        $user = Auth::getProvider()->retrieveByCredentials([
            'email' => $this->email,
            'password' => $this->password,
        ]);

        if (!$user || !Auth::getProvider()->validateCredentials($user, ['password' => $this->password])) 
        {
            RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages([
                'email' => 'Correo o contraseña incorrectos.',
            ]);
        }

        return $user;
    }

    protected function ensureIsNotRateLimited(): void
    {
        if (!RateLimiter::tooManyAttempts($this->throttleKey(), 5)) 
        {
            return;
        }

        event(new Lockout(request()));

        throw ValidationException::withMessages([
            'email' => 'Demasiados intentos. Intenta más tarde.',
        ]);
    }

    public function exception($e, $stopPropagation): void
    {
        if ($e instanceof ValidationException) 
        {
            $first = collect($e->errors())->flatten()->first();

            $this->dispatch('show-swal', icon: 'error', title: 'Error', text: $first);

            $this->resetErrorBag();
            $stopPropagation();
        }
    }

    protected function throttleKey(): string
    {
        return Str::lower($this->email) . '|' . request()->ip();
    }
};
?>

<div class="flex flex-col gap-6">

    <x-auth-header :title="__('Ingresar a tu cuenta')" :description="__('Ingresa tu correo electrónico y contraseña a continuación para iniciar sesión')" />

    <form wire:submit="login" class="flex flex-col gap-6">

        <flux:input wire:model="email" :label="__('Correo electrónico')" type="email" required autofocus
            autocomplete="email" placeholder="email@gmail.com" />

        <div class="relative">
            <flux:input wire:model="password" :label="__('Contraseña')" type="password" required
                autocomplete="current-password" :placeholder="__('Contraseña')" viewable />

            <flux:link :href="route('password.request')" wire:navigate
                class="absolute top-0 end-0 text-sm text-custom-blue hover:text-custom-blue-dark">
                {{ __('¿Olvidaste tu contraseña?') }}
            </flux:link>
        </div>

        <flux:checkbox wire:model="remember" :label="__('Recordarme')" />

        <div class="flex items-center justify-end">
            <flux:button icon="user-circle" icon-variant="outline" variant="primary" type="submit"
                class="w-full bg-black hover:bg-custom-gray text-white">
                {{ __('Iniciar sesión') }}
            </flux:button>
        </div>

    </form>

    @script
    <script>
        $wire.on(
            'show-swal',
            (data) =>
            {
                Swal.fire(
                {
                    icon: data.icon,
                    title: data.title,
                    text: data.text,
                    confirmButtonColor: '#494949'
                });
            }
        );
    </script>
@endscript

</div>
