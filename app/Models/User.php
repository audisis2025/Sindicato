<?php

/*
* ===========================================================
* Nombre de la clase     : User
* Descripción de la clase: Modelo de usuarios del sistema,
* refactorizado al esquema en inglés y sin username.
* Versión                : 3.0 (Login por email)
* ===========================================================
*/

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;


// Importar relaciones faltantes
use App\Models\ProcedureRequest;
use App\Models\ActivityLog;
use App\Models\SystemNotification;


class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * Campos asignables (username eliminado).
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'curp',
        'rfc',
        'gender',
        'budget_key',
        'role',
        'active',
    ];

    /**
     * Ocultar campos sensibles.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Cast automáticos.
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'active' => 'boolean',
    ];

    /**
     * Iniciales del nombre del usuario.
     */
    public function initials(): string
    {
        $name = $this->name ?? '';

        return collect(explode(' ', $name))
            ->filter()
            ->take(2)
            ->map(fn($word) => strtoupper(mb_substr($word, 0, 1)))
            ->implode('');
    }

    /**
     * Relación: Solicitudes de trámites.
     */
    public function procedureRequests(): HasMany
    {
        return $this->hasMany(ProcedureRequest::class);
    }

    /**
     * Relación: logs de actividad.
     */
    public function activityLogs(): HasMany
    {
        return $this->hasMany(ActivityLog::class);
    }

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new \App\Notifications\ResetPasswordByRole($token));
    }

    public function systemNotifications(): HasMany
    {
        return $this->hasMany(SystemNotification::class, 'user_id');
    }
}
