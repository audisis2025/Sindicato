<?php
/*
* Nombre de la clase           : ProcedureDocument.php
* Descripción de la clase      : Modelo Eloquent encargado de representar los documentos asociados a los pasos de una solicitud de trámite, incluyendo archivos cargados por el trabajador y su relación con el paso y la solicitud correspondiente.
* Fecha de creación            : 12/10/2025
* Elaboró                      : Iker Piza
* Fecha de liberación          : 14/12/2025
* Autorizó                     : Salvador Monroy
* Versión                      : 1.0
* Fecha de mantenimiento       :
* Folio de mantenimiento       :
* Tipo de mantenimiento        : 
* Descripción del mantenimiento: 
* Responsable                  :
* Revisor                      : 
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
