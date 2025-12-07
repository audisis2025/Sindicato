<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Notifications\Messages\MailMessage;

class ResetPasswordByRole extends ResetPassword
{
    public function toMail($notifiable)
    {
        $url = url(route('password.reset', [
            'token' => $this->token,
            'email' => $notifiable->email,
        ], false));

        // PERSONALIZACIÓN POR ROL
        switch ($notifiable->role) {
            case 'admin':
                $subject = "Recuperación de contraseña — Administrador SINDISOFT";
                break;
            case 'union':
                $subject = "Recuperación de contraseña — Sindicato SINDISOFT";
                break;
            default:
                $subject = "Recuperación de contraseña — Trabajador SINDISOFT";
                break;
        }

        return (new MailMessage)
            ->subject($subject)
            ->greeting("Hola, {$notifiable->name}")
            ->line("Recibimos una solicitud para restablecer tu contraseña.")
            ->action("Restablecer contraseña", $url)
            ->line("Si no solicitaste este cambio, simplemente ignora este mensaje.");
    }
}
