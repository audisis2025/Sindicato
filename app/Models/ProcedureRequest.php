<?php
/*
* ===========================================================
* Nombre de la clase: ProcedureRequest.php
* Descripción: Modelo Eloquent para la tabla 'procedure_requests',
* representando la solicitud de un trabajador para un trámite.
* Estados alineados con RF-04.
* Fecha: 10/11/2025
* Versión: 2.0 (Actualizado RF-04)
* ===========================================================
*/

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProcedureRequest extends Model
{
    use HasFactory;

    protected $table = 'procedure_requests';

    /**
     * ============================
     *   ESTADOS OFICIALES (RF-04)
     * ============================
     */
    public const STATUS_INITIATED        = 'initiated';
    public const STATUS_IN_PROGRESS      = 'in_progress';
    public const STATUS_PENDING_WORKER   = 'pending_worker';
    public const STATUS_PENDING_UNION    = 'pending_union';
    public const STATUS_COMPLETED        = 'completed';
    public const STATUS_CANCELLED        = 'cancelled';
    public const STATUS_REJECTED         = 'rejected';

    /**
     * Estados válidos para validación
     */
    public static function validStatuses(): array
    {
        return [
            self::STATUS_INITIATED,
            self::STATUS_IN_PROGRESS,
            self::STATUS_PENDING_WORKER,
            self::STATUS_PENDING_UNION,
            self::STATUS_COMPLETED,
            self::STATUS_CANCELLED,
            self::STATUS_REJECTED,
        ];
    }

    /**
     * Campos asignables
     */
    protected $fillable = [
        'user_id',
        'procedure_id',
        'current_step',
        'status',
    ];

    /**
     * ============================
     *   RELACIONES
     * ============================
     */

    // Trámite al que pertenece
    public function procedure(): BelongsTo
    {
        return $this->belongsTo(Procedure::class);
    }

    // Usuario (trabajador)
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Archivos subidos por paso
    public function documents(): HasMany
    {
        return $this->hasMany(ProcedureDocument::class);
    }

    /**
     * ============================
     *   HELPERS DE ESTADO
     * ============================
     */

    public function isInitiated(): bool
    {
        return $this->status === self::STATUS_INITIATED;
    }

    public function isInProgress(): bool
    {
        return $this->status === self::STATUS_IN_PROGRESS;
    }

    public function isPendingWorker(): bool
    {
        return $this->status === self::STATUS_PENDING_WORKER;
    }

    public function isPendingUnion(): bool
    {
        return $this->status === self::STATUS_PENDING_UNION;
    }

    public function isCompleted(): bool
    {
        return $this->status === self::STATUS_COMPLETED;
    }

    public function isCancelled(): bool
    {
        return $this->status === self::STATUS_CANCELLED;
    }

    public function isRejected(): bool
    {
        return $this->status === self::STATUS_REJECTED;
    }
}
