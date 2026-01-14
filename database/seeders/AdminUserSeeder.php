<?php

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
