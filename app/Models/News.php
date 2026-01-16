<?php
/*
* Nombre de la clase           : News.php
* Descripción de la clase      : Modelo Eloquent encargado de la gestión de noticias, comunicados, convocatorias y eventos del sistema, incluyendo manejo de fechas de publicación/expiración, relaciones y scopes de visibilidad para trabajadores.
* Fecha de creación            : 06/10/2025
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
