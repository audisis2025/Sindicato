<?php
/*
* Nombre de la clase         : forgot-password.blde.php
* Descripción de la clase    : Componente Livewire Volt encargado de solicitar el enlace de restablecimiento de
*                              contraseña, validando el correo electrónico y mostrando alertas de éxito o error.
* Fecha de creación          : 14/01/2026
* Elaboró                    : Iker Piza
* Fecha de liberación        : 14/01/2026
* Autorizó                   :
* Versión                    : 1.0
* Fecha de mantenimiento     :
* Folio de mantenimiento     :
* Tipo de mantenimiento      :
* Descripción del mantenimiento :
* Responsable                :
* Revisor                    :
*/

use Illuminate\Support\Facades\Password;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('components.layouts.auth')] class extends Component
{
    public string $email = '';

    public function sendPasswordResetLink(): void
    {
        $this->validate([
            'email' => ['required', 'string', 'email'],
        ]);

        $status = Password::sendResetLink($this->only('email'));

        if ($status === Password::RESET_LINK_SENT) 
        {
            $this->dispatch(
                'show-swal',
                icon: 'success',
                title: 'Enlace enviado',
                text: __($status)
            );
        } else 
        {
            $this->dispatch(
                'show-swal',
                icon: 'error',
                title: 'Error',
                text: __($status)
            );
        }
    }

    public function exception($e, $stopPropagation): void
    {
        if ($e instanceof ValidationException) 
        {

            $first = collect($e->errors())->flatten()->first();

            $this->dispatch(
                'show-swal',
                icon: 'error',
                title: 'Error',
                text: $first
            );

            $this->resetErrorBag();
            $stopPropagation();
        }
    }
};
?>

<div class="flex flex-col gap-6">

    <x-auth-header
        :title="__('Recuperar contraseña')"
        :description="__('Ingresa tu correo electrónico para recibir un enlace de restablecimiento de contraseña')"
    />

    <form wire:submit="sendPasswordResetLink" class="flex flex-col gap-6">

        <flux:input
            wire:model="email"
            :label="__('Correo electrónico')"
            type="email"
            required
            autofocus
            autocomplete="email"
            placeholder="email@gmail.com"
        />

        <flux:button
            type="submit"
            variant="primary"
            icon="paper-airplane"
            icon-variant="outline"
            class="w-full bg-black hover:bg-custom-gray text-white"
            data-test="email-password-reset-link-button"
        >
            {{ __('Enviar enlace') }}
        </flux:button>

    </form>

    <div class="space-x-1 rtl:space-x-reverse text-center text-sm text-black/60 dark:text-white/60">
        <span>{{ __('O, regresa a') }}</span>
        <flux:link
            :href="route('login')"
            wire:navigate
            class="text-custom-blue hover:text-custom-blue-dark"
        >
            {{ __('Iniciar sesión') }}
        </flux:link>
    </div>

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
