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
    protected $fillable = [
        'procedure_id',
        'order',
        'step_name',
        'step_description',
        'next_step_if_fail',
        'requires_file',
        'file_path',
    ];


    protected $casts = [
        'requires_file' => 'boolean',
    ];

    public function procedure()
    {
        return $this->belongsTo(Procedure::class);
    }
}
