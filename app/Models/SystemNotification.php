<?php

/*
* ===========================================================
* Nombre de la clase: SystemNotification
* Descripción: Modelo Eloquent para la tabla 'system_notifications'.
* Fecha de mantenimiento: 02/12/2025
* Responsable: Iker Piza
* Revisor: QA SINDISOFT
* Versión: 3.0 (Actualizado por nuevo ENUM)
* ===========================================================
*/

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SystemNotification extends Model
{
    use HasFactory;

    protected $table = 'system_notifications';

    /* ===========================================================
       ENUM — Tipos permitidos
       =========================================================== */
    public const TYPE_INFO       = 'info';
    public const TYPE_SUCCESS    = 'success';
    public const TYPE_ERROR      = 'error';
    public const TYPE_WARNING    = 'warning';
    public const TYPE_PENDING    = 'pending';
    public const TYPE_APPROVED   = 'approved';
    public const TYPE_REJECTED   = 'rejected';
    public const TYPE_CORRECTION = 'correction';

    public static function allowedTypes(): array
    {
        return [
            self::TYPE_INFO,
            self::TYPE_SUCCESS,
            self::TYPE_ERROR,
            self::TYPE_WARNING,
            self::TYPE_PENDING,
            self::TYPE_APPROVED,
            self::TYPE_REJECTED,
            self::TYPE_CORRECTION,
        ];
    }

    /* ===========================================================
       ENUM — Estados
       =========================================================== */
    public const STATUS_UNREAD = 'unread';
    public const STATUS_READ   = 'read';

    public static function allowedStatuses(): array
    {
        return [self::STATUS_UNREAD, self::STATUS_READ];
    }

    /* ===========================================================
       Fillable
       =========================================================== */
    protected $fillable = [
        'user_id',
        'title',
        'message',
        'type',
        'status',
    ];

    /* ===========================================================
       Relaciones
       =========================================================== */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /* ===========================================================
       Helpers
       =========================================================== */

    /** Verifica si el tipo coincide */
    public function isType(string $type): bool
    {
        return $this->type === $type;
    }

    /** Marcar como leída */
    public function markAsRead(): void
    {
        $this->update(['status' => self::STATUS_READ]);
    }

    /** Validar automáticamente tipo y estado */
    protected static function booted()
    {
        static::creating(function ($model) {
            if (!in_array($model->type, self::allowedTypes())) {
                throw new \InvalidArgumentException("Tipo de notificación inválido: {$model->type}");
            }

            if (!in_array($model->status, self::allowedStatuses())) {
                throw new \InvalidArgumentException("Estado inválido: {$model->status}");
            }
        });
    }
}
