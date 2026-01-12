<?php
/*
* ===========================================================
* Nombre de la clase: UserController
* Descripción de la clase: Gestión administrativa de usuarios,
* incluyendo creación, edición, eliminación y activación.
* Fecha de creación: 05/11/2025
* Elaboró: [Tu Nombre]
* Fecha de liberación: 10/11/2025
* Autorizó: Líder Técnico
* Versión: 2.0
*
* Fecha de mantenimiento: [DD/MM/AAAA]
* Folio de mantenimiento: [Folio]
* Tipo de mantenimiento: Correctivo y Perfectivo
* Descripción del mantenimiento: Revisión de roles, 
* validaciones y estandarización del controlador.
* Responsable: [Tu Nombre]
* Revisor: QA SINDISOFT
* ===========================================================
*/

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use App\Services\SystemLogger;

class UserController extends Controller
{
	public function __construct()
	{
		$this->middleware('auth');
		$this->middleware('isAdmin');
	}

	public function index(Request $request): View
	{
		$query = User::query();

		if ($request->filled('role'))
		{
			$query->where('role', $request->role);
		}

		$users = $query->orderBy('id', 'desc')->get();

		return view('admin.users.index', compact('users'));
	}

	public function create(): View
	{
		return view('admin.users.create');
	}

	public function store(Request $request): RedirectResponse
	{
		$validatedData = $request->validate([
			'name' => 'required|string|max:100',
			'email' => 'required|email|max:100|unique:users,email',
			'password' => 'required|string|min:8',
			'role' => 'required|in:union,worker',
			'curp' => 'nullable|string|max:18',
			'rfc' => 'nullable|string|max:13',
			'gender' => 'nullable|in:H,M,ND,X',
			'budget_key' => 'nullable|string|max:50',
		]);

		$user = User::create([
			'name' => $validatedData['name'],
			'email' => $validatedData['email'],
			'password' => Hash::make($validatedData['password']),
			'role' => $validatedData['role'],
			'curp' => $validatedData['curp'] ?? null,
			'rfc' => $validatedData['rfc'] ?? null,
			'gender' => $validatedData['gender'] ?? null,
			'budget_key' => $validatedData['budget_key'] ?? null,
			'active' => true,
		]);

		app(SystemLogger::class)->log(
			'Crear usuario',
			"El administrador creó al usuario ID: {$user->id}"
		);

		return redirect()->route('users.index')
			->with('success', 'Usuario creado correctamente.');
	}

	public function edit(User $user): View
	{
		return view('admin.users.edit', compact('user'));
	}

	public function update(Request $request, User $user): RedirectResponse
	{
		$validatedData = $request->validate([
			'name' => 'required|string|max:100',
			'email' => 'required|email|max:100|unique:users,email,' . $user->id,
			'role' => 'required|in:union,worker',
			'curp' => 'nullable|string|max:18',
			'rfc' => 'nullable|string|max:13',
			'gender' => 'nullable|in:H,M,ND,X',
			'budget_key' => 'nullable|string|max:50',
			'active' => 'required|boolean',
		]);

		$user->update($validatedData);

		app(SystemLogger::class)->log(
			'Actualizar usuario',
			"El administrador actualizó al usuario ID: {$user->id}"
		);

		return redirect()->route('users.index')
			->with('success', 'Usuario actualizado correctamente.');
	}

	public function destroy(User $user): RedirectResponse
	{
		$id = $user->id;

		$user->delete();

		app(SystemLogger::class)->log(
			'Eliminar usuario',
			"El administrador eliminó al usuario ID: {$id}"
		);

		return back()->with('success', 'Usuario eliminado correctamente.');
	}

	public function toggle($id): RedirectResponse
	{
		$user = User::findOrFail($id);

		$user->active = !$user->active;
		$user->save();

		return back()->with('success', 'Estado del usuario actualizado correctamente.');
	}
}
