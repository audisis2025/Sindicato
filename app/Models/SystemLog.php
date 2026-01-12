<?php
/*
* ===========================================================
* Nombre de la clase: SystemLog
* Descripción de la clase: Modelo Eloquent para la tabla 
* 'system_logs', utilizada para almacenar los registros 
* internos del sistema.
* Fecha de creación: 15/11/2025
* Elaboró: [Tu Nombre]
* Fecha de liberación: 15/11/2025
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

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SystemLog extends Model
{
	protected $table = 'system_logs';

	protected $fillable = [
		'user_id',
		'action',
		'description',
	];

	public function user(): BelongsTo
	{
		return $this->belongsTo(User::class);
	}
}
