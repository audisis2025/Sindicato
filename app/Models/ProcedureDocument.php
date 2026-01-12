<?php
/*
* ===========================================================
* Nombre de la clase: ProcedureDocument
* Descripción de la clase: Modelo Eloquent para la tabla 
* 'procedure_documents', representa documentos cargados por 
* el trabajador en una solicitud de trámite.
* Fecha de creación: 10/11/2025
* Elaboró: [Tu Nombre]
* Fecha de liberación: 10/11/2025
* Autorizó: Líder Técnico
* Versión: 1.0
*
* Fecha de mantenimiento: [DD/MM/AAAA]
* Folio de mantenimiento: [Folio]
* Tipo de mantenimiento: [Correctivo/Perfectivo/Adaptativo/Preventivo]
* Descripción del mantenimiento: [Descripción breve del cambio]
* Responsable: [Tu Nombre]
* Revisor: [Revisor]
* ===========================================================
*/

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProcedureDocument extends Model
{
	use HasFactory;

	protected $table = 'procedure_documents';

	protected $fillable = [
		'procedure_request_id',
		'file_name',
		'procedure_step_id',
		'file_path',
		'type',
		'year',
	];

	public function request(): BelongsTo
	{
		return $this->belongsTo(ProcedureRequest::class, 'procedure_request_id');
	}

	public function step(): BelongsTo
	{
		return $this->belongsTo(ProcedureStep::class, 'procedure_step_id');
	}
}
