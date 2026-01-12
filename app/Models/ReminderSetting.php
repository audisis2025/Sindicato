<?php
/*
* ===========================================================
* Nombre de la clase: ReminderSetting
* Descripción de la clase: Modelo Eloquent para la tabla 
* 'reminder_settings', encargado de almacenar la configuración 
* general de recordatorios del sistema.
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

class ReminderSetting extends Model
{
	use HasFactory;

	protected $table = 'reminder_settings';

	protected $fillable = [
		'enabled',
		'channel',
		'interval_days',
		'base_message',
	];
}
