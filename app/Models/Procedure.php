<?php
/*
* Nombre de la clase           : Procedure.php
* Descripción de la clase      : Modelo Eloquent encargado de representar los trámites del sistema, incluyendo información general, relaciones con pasos, solicitudes y usuario creador.
* Fecha de creación            : 09/10/2025
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
