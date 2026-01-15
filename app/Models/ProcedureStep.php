<?php
/*
* Nombre de la clase           : ProcedureStep.php
* Descripción de la clase      : Modelo Eloquent encargado de representar los pasos que conforman un trámite, incluyendo orden, descripción, flujo alterno en caso de error, requerimiento de archivos y relación con el trámite.
* Fecha de creación            : 13/11/2025
* Elaboró                      : Iker Piza
* Fecha de liberación          : 18/12/2025
* Autorizó                     :
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

class ProcedureStep extends Model
{
	use HasFactory;

	protected $table = 'procedure_steps';

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

	public function procedure(): BelongsTo
	{
		return $this->belongsTo(Procedure::class);
	}
}
