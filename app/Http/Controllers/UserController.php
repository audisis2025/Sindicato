<?php
/*
* Nombre de la clase           : UserController.php
* Descripción de la clase      : Controlador encargado de la administración de usuarios del sistema: listado, alta, edición, actualización, eliminación y cambio de estado.
* Fecha de creación            : 05/11/2025
* Elaboró                      : Iker Piza
* Fecha de liberación          : 17/12/2025
* Autorizó                     :
* Versión                      : 1.1
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
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use App\Services\SystemLogger;
use App\Http\Requests\Users\UserStoreRequest;
use App\Http\Requests\Users\UserUpdateRequest;

class UserController extends Controller
{
	public function __construct()
	{
		$this->middleware('auth');
		$this->middleware('isAdmin');
	}

	public function index(Request $request): View
	{
		 $query = User::query()
        ->where('role', '!=', 'admin');

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


	public function store(UserStoreRequest $request): RedirectResponse
	{
		$data = $request->validated();

		$user = User::create([
			'name' => $data['name'],
			'email' => $data['email'],
			'password' => Hash::make($data['password']),
			'role' => $data['role'],
			'curp' => $data['curp'],
			'rfc' => $data['rfc'],
			'gender' => $data['gender'],
			'budget_key' => $data['budget_key'],
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

	public function update(UserUpdateRequest $request, User $user): RedirectResponse
	{
		$data = $request->validated();

		if (!empty($data['password']))
		{
			$data['password'] = Hash::make($data['password']);
		}
		else
		{
			unset($data['password']);
		}

		$user->update($data);

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
