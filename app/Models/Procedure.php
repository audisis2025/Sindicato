<?php
/*
* ===========================================================
* Nombre de la clase: Procedure
* Descripción de la clase: Modelo Eloquent para la gestión de trámites 
* creados por el Sindicato dentro del sistema.
* Fecha de creación: 10/11/2025
* Elaboró: [Tu Nombre]
* Fecha de liberación: 10/11/2025
* Autorizó: Líder Técnico
* Versión: 3.0
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
use Illuminate\Database\Eloquent\Relations\HasMany;

class Procedure extends Model
{
	use HasFactory;

	protected $table = 'procedures';

	protected $fillable = [
		'user_id',
		'name',
		'description',
		'steps_count',
		'opening_date',
		'closing_date',
		'estimated_days',
		'has_alternate_flow',
		'status',
	];

	public function user(): BelongsTo
	{
		return $this->belongsTo(User::class);
	}

	public function steps(): HasMany
	{
		return $this->hasMany(ProcedureStep::class, 'procedure_id')->orderBy('order');
	}

	public function requests(): HasMany
	{
		return $this->hasMany(ProcedureRequest::class, 'procedure_id');
	}
}
