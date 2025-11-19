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
        'file_path',
        'type',
        'year',
    ];

    /**
     * Obtiene la solicitud a la que pertenece este documento.
     * (Relación Uno a Muchos Inversa)
     */
    public function procedureRequest(): BelongsTo // [cite: 588-592]
    {
        return $this->belongsTo(ProcedureRequest::class);
    }
}