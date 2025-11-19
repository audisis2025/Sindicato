<?php
/*
* ===========================================================
* Nombre de la clase: ProcedureRequest.php
* Descripción de la clase: Modelo Eloquent para la tabla 'procedure_requests'.
* Representa la solicitud de un trabajador para un trámite.
* Fecha de creación: 10/11/2025
* Elaboró: [Tu Nombre]
* Fecha de liberación: 10/11/2025
* Autorizó: Líder Técnico
* Versión: 1.0
* ===========================================================
*/

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProcedureRequest extends Model // [cite: 298-301, 512, 532-536]
{
    use HasFactory;

    /**
     * Define la tabla asociada con el modelo.
     *
     * @var string
     */
    protected $table = 'procedure_requests';

    /**
     * Los atributos que se pueden asignar masivamente.
     *
     * @var array<int, string>
     */
    protected $fillable = [ // [cite: 532-536]
        'user_id',
        'procedure_id',
        'current_step',
        'status',
    ];

    /**
     * Obtiene el trámite (plantilla) al que pertenece esta solicitud.
     * (Relación Uno a Muchos Inversa)
     */
    public function procedure(): BelongsTo // [cite: 588-592]
    {
        return $this->belongsTo(Procedure::class);
    }

    /**
     * Obtiene el usuario (trabajador) que realizó esta solicitud.
     * (Relación Uno a Muchos Inversa)
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Obtiene todos los documentos adjuntos a esta solicitud.
     * (Relación Uno a Muchos)
     */
    public function documents(): HasMany // [cite: 588-592, 609-611]
    {
        return $this->hasMany(ProcedureDocument::class);
    }
}