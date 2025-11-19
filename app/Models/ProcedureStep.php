<?php
/*
* ===========================================================
* Nombre de la clase: ProcedureStep.php
* Descripción de la clase: Modelo Eloquent para la tabla 'procedure_steps'.
* Representa un paso individual dentro de una plantilla de trámite.
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

class ProcedureStep extends Model // [cite: 298-301, 512, 532-536]
{
    use HasFactory;

    /**
     * Define la tabla asociada con el modelo.
     *
     * @var string
     */
    protected $table = 'procedure_steps';

    /**
     * Los atributos que se pueden asignar masivamente.
     *
     * @var array<int, string>
     */
    protected $fillable = [ // [cite: 532-536]
        'procedure_id',
        'order',
        'step_name',
        'step_description',
        'file_path',
        'next_step_if_fail',
        'estimated_days',
    ];

    /**
     * Obtiene el trámite (plantilla) al que pertenece este paso.
     * (Relación Uno a Muchos Inversa)
     */
    public function procedure(): BelongsTo // [cite: 588-592]
    {
        return $this->belongsTo(Procedure::class);
    }
}