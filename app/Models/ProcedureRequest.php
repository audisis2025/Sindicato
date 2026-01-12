<?php
/*
* ===========================================================
* Nombre de la clase: ProcedureRequest
* Descripción de la clase: Modelo Eloquent para gestionar 
* las solicitudes de trámites realizados por los trabajadores.
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

class ProcedureRequest extends Model
{
	use HasFactory;

	protected $table = 'procedure_requests';

	public const STATUS_INITIATED = 'initiated';
	public const STATUS_IN_PROGRESS = 'in_progress';
	public const STATUS_PENDING_WORKER = 'pending_worker';
	public const STATUS_PENDING_UNION = 'pending_union';
	public const STATUS_COMPLETED = 'completed';
	public const STATUS_CANCELLED = 'cancelled';
	public const STATUS_REJECTED = 'rejected';

	public static function validStatuses(): array
	{
		return [
			self::STATUS_INITIATED,
			self::STATUS_IN_PROGRESS,
			self::STATUS_PENDING_WORKER,
			self::STATUS_PENDING_UNION,
			self::STATUS_COMPLETED,
			self::STATUS_CANCELLED,
			self::STATUS_REJECTED,
		];
	}

	protected $fillable = [
		'user_id',
		'procedure_id',
		'current_step',
		'status',
	];

	public function procedure(): BelongsTo
	{
		return $this->belongsTo(Procedure::class);
	}

	public function user(): BelongsTo
	{
		return $this->belongsTo(User::class);
	}

	public function documents(): HasMany
	{
		return $this->hasMany(ProcedureDocument::class);
	}

	public function isInitiated(): bool
	{
		return $this->status === self::STATUS_INITIATED;
	}

	public function isInProgress(): bool
	{
		return $this->status === self::STATUS_IN_PROGRESS;
	}

	public function isPendingWorker(): bool
	{
		return $this->status === self::STATUS_PENDING_WORKER;
	}

	public function isPendingUnion(): bool
	{
		return $this->status === self::STATUS_PENDING_UNION;
	}

	public function isCompleted(): bool
	{
		return $this->status === self::STATUS_COMPLETED;
	}

	public function isCancelled(): bool
	{
		return $this->status === self::STATUS_CANCELLED;
	}

	public function isRejected(): bool
	{
		return $this->status === self::STATUS_REJECTED;
	}

	public function currentStep()
	{
		return $this->procedure
			->steps
			->where('order', $this->current_step)
			->first();
	}

	public function nextStep()
	{
		return $this->procedure
			->steps
			->where('order', $this->current_step + 1)
			->first();
	}

	public function advance()
	{
		$step = $this->currentStep();

		if (!$step)
		{
			return;
		}

		if ($step->next_step_if_fail)
		{
			$this->current_step = $step->next_step_if_fail;
		}
		else
		{
			$this->current_step++;
		}

		if ($this->current_step > $this->procedure->steps->max('order'))
		{
			$this->status = self::STATUS_COMPLETED;
		}
		else
		{
			$this->status = self::STATUS_IN_PROGRESS;
		}

		$this->save();
	}

	public function failStep()
	{
		$this->status = self::STATUS_REJECTED;
		$this->save();
	}
}
