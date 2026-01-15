<?php
/*
* Nombre de la clase         : AdminUserSeeder.php
* Descripción de la clase    : Seeder encargado de la creación o actualización del usuario administrador del sistema,
*                              estableciendo credenciales iniciales, rol administrativo y datos generales de control.
* Fecha de creación          : 14/01/2026
* Elaboró                    : Iker Piza
* Fecha de liberación        : 14/01/2026
* Autorizó                   :
* Versión                    : 1.0
* Fecha de mantenimiento     :
* Folio de mantenimiento     :
* Tipo de mantenimiento      :
* Descripción del mantenimiento :
* Responsable                :
* Revisor                    :
*/

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
	public function run(): void
	{
		User::updateOrCreate(
			['username' => 'admin'],
			[
				'name' => 'Administrador del Sistema',
				'email' => 'admin@sindisoft.com',
				'password' => Hash::make('Admin123*'),

				'role' => 'admin',
				'active' => true,
				'email_verified_at' => now(),

				'curp' => 'AAAA000000HDFXXX00',
				'rfc' => 'AAAA000000XXX',
				'gender' => 'H',
				'budget_key' => 'ADMIN-000',
			]
		);
	}
}
