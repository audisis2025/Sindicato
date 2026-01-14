<?php
/*
* Nombre de la clase           : AdminProfileController.php
* Descripción de la clase      : Controlador encargado de la administración y actualización del perfil del administrador.
* Fecha de creación            : 04/11/2025
* Elaboró                      : Iker Piza
* Fecha de liberación          : 12/12/2025
* Autorizó                     :
* Versión                      : 1.0
* Fecha de mantenimiento       :
* Folio de mantenimiento       :
* Tipo de mantenimiento        :
* Descripción del mantenimiento:
* Responsable                  :
* Revisor                      :
*/

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class AdminProfileController extends Controller
{
	public function __construct()
	{
		$this->middleware('auth');
		$this->middleware('isAdmin');
	}

	public function edit(): View
	{
		$user = Auth::user();

		return view('admin.edit', compact('user'));
	}

	public function update(Request $request): RedirectResponse
	{
		/** @var User $user */
		$user = Auth::user();

		$validated = $request->validate([
			'name' => 'required|string|max:120',
			'email' => 'nullable|email|max:120|unique:users,email,' . $user->id,

			'curp' => ['nullable', 'string', 'size:18', 'regex:/^[A-Z0-9]{18}$/'],
			'rfc' => ['nullable', 'string', 'min:12', 'max:13', 'regex:/^[A-Z0-9]{12,13}$/'],
			'gender' => 'nullable|in:H,M,ND,X',
			'budget_key' => 'nullable|string|max:50',

			'password' => 'nullable|string|min:8|confirmed',
		], [
			'username.required' => 'El usuario es obligatorio.',
			'username.unique' => 'El usuario ya está en uso.',
			'username.alpha_dash' => 'El usuario solo puede contener letras, números, guiones y guion bajo.',
			'name.required' => 'El nombre es obligatorio.',
			'email.email' => 'El correo no tiene un formato válido.',
			'email.unique' => 'El correo ya está en uso.',

			'curp.size' => 'La CURP debe tener 18 caracteres.',
			'curp.regex' => 'La CURP debe estar en mayúsculas y sin espacios.',
			'rfc.min' => 'El RFC debe tener 12 o 13 caracteres.',
			'rfc.max' => 'El RFC debe tener 12 o 13 caracteres.',
			'rfc.regex' => 'El RFC debe estar en mayúsculas y sin espacios.',

			'password.min' => 'La contraseña debe tener al menos 8 caracteres.',
			'password.confirmed' => 'La confirmación de contraseña no coincide.',
		]);

		$validated['curp'] = isset($validated['curp']) ? strtoupper(trim($validated['curp'])) : null;
		$validated['rfc'] = isset($validated['rfc']) ? strtoupper(trim($validated['rfc'])) : null;
		$user->name = $validated['name'];
		$user->email = $validated['email'] ?? null;
		$user->curp = $validated['curp'] ?? null;
		$user->rfc = $validated['rfc'] ?? null;
		$user->gender = $validated['gender'] ?? null;
		$user->budget_key = $validated['budget_key'] ?? null;

		if (!empty($validated['password']))
		{
			$user->password = Hash::make($validated['password']);
		}

		$user->save();

		return back()->with('success', 'Perfil actualizado correctamente.');
	}
}
