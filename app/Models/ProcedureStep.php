<?php
/*
* ===========================================================
* Nombre de la clase: ProcedureStep
* Descripción de la clase: Modelo Eloquent para la tabla 
* 'procedure_steps', representa un paso individual dentro 
* de un trámite.
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
