<?php
/*
* ===========================================================
* Nombre de la clase: ReminderLog
* Descripción de la clase: Modelo Eloquent para registrar 
* los recordatorios enviados a los usuarios respecto a un trámite.
* Fecha de creación: 24/11/2025
* Elaboró: [Tu Nombre]
* Fecha de liberación: 24/11/2025
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

class ReminderLog extends Model
{
	use HasFactory;

	protected $table = 'reminder_logs';

	protected $fillable = [
		'user_id',
		'procedure_id',
		'channel',
		'message',
		'sent_at',
	];
}
