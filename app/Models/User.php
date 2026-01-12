<?php
/*
* ===========================================================
* Nombre de la clase: User
* Descripción de la clase: Modelo de autenticación que 
* representa a los usuarios del sistema.
* Fecha de creación: 01/11/2025
* Elaboró: [Tu Nombre]
* Fecha de liberación: 01/11/2025
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
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\ProcedureRequest;
use App\Models\ActivityLog;
use App\Models\SystemNotification;

class User extends Authenticatable
{
	use HasFactory, Notifiable;

	protected $fillable = [
		'name',
		'email',
		'password',
		'curp',
		'rfc',
		'gender',
		'budget_key',
		'role',
		'active',
	];

	protected $hidden = [
		'password',
		'remember_token',
	];

	protected $casts = [
		'email_verified_at' => 'datetime',
		'active' => 'boolean',
	];

	public function initials(): string
	{
		$name = $this->name ?? '';

		return collect(explode(' ', $name))
			->filter()
			->take(2)
			->map(fn($word) => strtoupper(mb_substr($word, 0, 1)))
			->implode('');
	}

	public function procedureRequests(): HasMany
	{
		return $this->hasMany(ProcedureRequest::class);
	}

	public function activityLogs(): HasMany
	{
		return $this->hasMany(ActivityLog::class);
	}

	public function systemNotifications(): HasMany
	{
		return $this->hasMany(SystemNotification::class, 'user_id');
	}

	public function sendPasswordResetNotification($token)
	{
		$this->notify(new \App\Notifications\ResetPasswordByRole($token));
	}
}
