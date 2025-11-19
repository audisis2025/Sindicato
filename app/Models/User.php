<?php

/*
* ===========================================================
* Nombre de la clase     : User
* Descripción de la clase: Modelo que representa a los usuarios del sistema,
* contiene la información principal y sus relaciones.
* (Refactorizado para alinear a estándares)
* Fecha de creación      : [Fecha Original]
* Elaboró                : [Autor Original]
* Fecha de liberación    : 12/11/2025
* Autorizó               : [Autorizador]
* Versión                : 2.0 (Refactorización mayor)
*
* Historial de Mantenimiento
* Fecha de mantenimiento  : 12/11/2025
* Folio de mantenimiento  : [Folio]
* Tipo de mantenimiento   : Perfectivo (Refactorización)
* Descripción del mantenimiento: Se elimina lógica obsoleta (detalle(), showNews()),
* se traducen campos a inglés en $fillable
* y se añaden nuevas relaciones (procedureRequests, notifications).
* Responsable             : [Tu Nombre]
* Revisor                 : Gemini
* ===========================================================
*/

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany; // Importar HasMany

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * Campos asignables en masa. (Traducidos a inglés).
     */
    protected $fillable = [
        'username', // 'usuario'
        'name',
        'email',
        'password',
        'curp',
        'rfc',
        'gender',   // 'sexo'
        'budget_key', // 'clave_presupuestal'
        'role',     // 'rol'
        'active',   // 'activo'
    ];

    /**
     * Campos ocultos al serializar el modelo.
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Tipos de datos convertidos automáticamente.
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'active' => 'boolean', // Corregido de 'activo'
    ];

    /**
     * Obtiene las iniciales del nombre del usuario.
     *
     * @return string
     */
    public function initials(): string
    {
        $name = $this->name ?? '';
        return collect(explode(' ', $name))
            ->filter()
            ->take(2)
            ->map(fn($word) => mb_strtoupper(mb_substr($word, 0, 1)))
            ->implode('');
    }

    // --- MÉTODOS OBSOLETOS ELIMINADOS (detalle() y showNews()) ---

    /**
     * Obtiene las solicitudes de trámite (ProcedureRequests) asociadas al usuario.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function procedureRequests(): HasMany
    {
        return $this->hasMany(ProcedureRequest::class);
    }

    /**
     * Obtiene las notificaciones (Notifications) asociadas al usuario.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function notifications(): HasMany
    {
        return $this->hasMany(Notification::class);
    }

    /**
     * Obtiene los registros de actividad (ActivityLogs) asociados al usuario.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function activityLogs(): HasMany
    {
        return $this->hasMany(ActivityLog::class);
    }
}