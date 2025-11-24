<?php
/*
* ===========================================================
* Nombre de la clase: ProcedureDocument.php
* Descripción de la clase: Modelo Eloquent para la tabla 'procedure_documents'.
* Representa un archivo subido por un trabajador para una solicitud.
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

class ProcedureDocument extends Model // [cite: 298-301, 512, 532-536]
{
    use HasFactory;

    /**
     * Define la tabla asociada con el modelo.
     *
     * @var string
     */
    protected $table = 'procedure_documents';

    /**
     * Los atributos que se pueden asignar masivamente.
     *
     * @var array<int, string>
     */
    protected $fillable = [ // [cite: 532-536]
        'procedure_request_id',
        'file_name',
        'procedure_step_id',
        'file_path',
        'type',
        'year',
    ];

    /**
     * Relación: el archivo pertenece a una solicitud (ProcedureRequest)
     */
    public function request(): BelongsTo
    {
        return $this->belongsTo(ProcedureRequest::class, 'procedure_request_id');
    }

    /**
     * Relación: el archivo pertenece a un paso del trámite
     */
    public function step(): BelongsTo
    {
        return $this->belongsTo(ProcedureStep::class, 'procedure_step_id');
    }
}
