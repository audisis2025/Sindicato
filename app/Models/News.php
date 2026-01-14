<?php
/*
* ===========================================================
* Nombre de la clase: News
* Descripción de la clase: Modelo Eloquent para la tabla 'news'.
* Fecha de creación: 12/11/2025
* Elaboró: [Tu Nombre]
* Fecha de liberación: 12/11/2025
* Autorizó: Líder Técnico
* Versión: 3.0
*
* Fecha de mantenimiento: [DD/MM/AAAA]
* Folio de mantenimiento: [Folio]
* Tipo de mantenimiento: [Correctivo/Perfectivo/Adaptativo/Preventivo]
* Descripción del mantenimiento: [Descripción]
* Responsable: [Tu Nombre]
* Revisor: [Revisor]
* ===========================================================
*/

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;
use Carbon\Carbon;

class News extends Model
{
	use HasFactory;

	protected $table = 'news';

	protected $fillable = [
		'title',
		'content',
		'type',
		'status',
		'image_path',
		'file_path',
		'publication_date',
		'expiration_date',
		'user_id',
	];

	protected $casts = [
		'publication_date' => 'date',
		'expiration_date' => 'date',
	];

	public function getPublicationDateFormattedAttribute()
	{
		return $this->publication_date
			? $this->publication_date->format('d/m/Y')
			: null;
	}

	public function user(): BelongsTo
	{
		return $this->belongsTo(User::class, 'user_id');
	}


	public function scopeVisibleForWorker(Builder $query): Builder
	{
		$today = Carbon::today('America/Mexico_City')->toDateString();

		return $query->where('status', 'published')
			->whereDate('publication_date', '<=', $today)
			->where(function ($q) use ($today) {
				$q->whereNull('expiration_date')
				->orWhereDate('expiration_date', '>=', $today);
			});
	}

}
