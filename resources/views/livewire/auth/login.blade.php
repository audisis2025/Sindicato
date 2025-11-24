<?php

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

        // SWEETALERT DE ÉXITO
        $this->dispatch('show-swal', icon: 'success', title: 'Bienvenido', text: 'Inicio de sesión correcto.');

        // Redirección general al dashboard
        $this->redirectIntended(default: route('dashboard'), navigate: true);
    }

    protected function validateCredentials(): User
    {
        $user = Auth::getProvider()->retrieveByCredentials([
            'email' => $this->email,
            'password' => $this->password,
        ]);

        if (! $user || ! Auth::getProvider()->validateCredentials($user, ['password' => $this->password])) {
            RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages([
                'email' => 'Correo o contraseña incorrectos.',
            ]);
        }

        return $user;
    }

    protected function ensureIsNotRateLimited(): void
    {
        if (!RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout(request()));

        throw ValidationException::withMessages([
            'email' => 'Demasiados intentos. Intenta más tarde.',
        ]);
    }

    public function exception($e, $stopPropagation): void
    {
        if ($e instanceof ValidationException) {

            $first = collect($e->errors())->flatten()->first();

            // SWEETALERT DE ERROR
            $this->dispatch('show-swal', icon: 'error', title: 'Error', text: $first);

            $this->resetErrorBag();
            $stopPropagation();
        }
    }

    protected function throttleKey(): string
    {
        return Str::lower($this->email).'|'.request()->ip();
    }
};

?>

{{-- ===============================================
     PLANTILLA DE LOGIN SINDISOFT + FLUX UI
================================================== --}}

<div class="flex flex-col gap-6">

    <x-auth-header
        :title="__('Bienvenido a SINDISOFT')"
        :description="__('Ingresa tu correo institucional y contraseña para continuar')"
    />

    <form wire:submit="login" class="flex flex-col gap-6">

        <flux:input
            wire:model="email"
            :label="__('Correo electrónico')"
            type="email"
            placeholder="correo@ejemplo.com"
            required
            autofocus
        />

        <flux:input
            wire:model="password"
            :label="__('Contraseña')"
            type="password"
            placeholder="********"
            required
            viewable
        />

        <flux:checkbox
            wire:model="remember"
            :label="__('Recordarme')"
        />

        <flux:button
            type="submit"
            variant="primary"
            class="w-full !bg-[#DE6601] hover:!bg-[#C95500] text-white font-semibold"
            icon="arrow-right-start-on-rectangle"
            icon-variant="outline"
        >
            {{ __('Iniciar sesión') }}
        </flux:button>
    </form>

    <div class="text-center text-sm text-zinc-600 dark:text-neutral-300">
        <flux:link :href="route('password.request')" wire:navigate>
            {{ __('¿Olvidaste tu contraseña?') }}
        </flux:link>
    </div>

    @script
        <script>
            $wire.on('show-swal', (data) => {
                Swal.fire({
                    icon: data.icon,
                    title: data.title,
                    text: data.text,
                });
            });
        </script>
    @endscript
</div>
