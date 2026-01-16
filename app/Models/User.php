<?php
/*
* Nombre de la clase           : User.php
* Descripción de la clase      : Modelo Eloquent principal del sistema encargado de representar a los usuarios, incluyendo autenticación, roles, relaciones con solicitudes de trámites, bitácora de actividades, notificaciones del sistema y utilidades auxiliares.
* Fecha de creación            : 21/10/2025
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
