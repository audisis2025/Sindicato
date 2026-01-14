<?php
/*
* Nombre de la clase           : MemberController.php
* Descripción de la clase      : Controlador encargado de la gestión de trabajadores (miembros): listado, alta, edición, actualización y eliminación.
* Fecha de creación            : 08/11/2025
* Elaboró                      : Iker Piza
* Fecha de liberación          : 16/12/2025
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

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

use App\Http\Requests\Members\UnionMemberStoreRequest;
use App\Http\Requests\Members\MemberUpdateRequest;

class MemberController extends Controller
{
	public function index(Request $request): View
	{
		$workers = User::query()
			->where('role', 'worker')
			->when($request->name, function ($q, $name)
			{
				return $q->where('name', 'like', "%{$name}%")
					     ->orWhere('email', 'like', "%{$name}%");
			})
			->when($request->gender, function ($q, $gender)
			{
				return $q->where('gender', $gender);
			})
			->orderBy('name')
			->get();

		return view('union.members.index', compact('workers'));
	}

	public function create(): View
	{
		return view('union.members.create');
	}

	public function store(UnionMemberStoreRequest $request): RedirectResponse
	{
		$data = $request->validated();

		User::create([
			'name' => $data['name'],
			'email' => $data['email'],
			'role' => 'worker',
			'curp' => $data['curp'],
			'rfc' => $data['rfc'],
			'gender' => $data['gender'],
			'budget_key' => $data['budget_key'],
			'password' => Hash::make('12345678'),
			'active' => true,
		]);

		return redirect()
			->route('union.members.index')
			->with('success', 'Trabajador registrado correctamente.');
	}

	public function edit(string $id): View
	{
		$worker = User::where('role', 'worker')->findOrFail($id);

		return view('union.members.edit', compact('worker'));
	}

	public function update(MemberUpdateRequest $request, string $id): RedirectResponse
	{
		$worker = User::where('role', 'worker')->findOrFail($id);

		$data = $request->validated();

		$worker->update([
			'name' => $data['name'],
			'email' => $data['email'],
			'active' => $data['active'],
			'curp' => $data['curp'],
			'rfc' => $data['rfc'],
			'gender' => $data['gender'],
			'budget_key' => $data['budget_key'],
		]);

		return redirect()
			->route('union.members.index')
			->with('success', 'Datos del trabajador actualizados correctamente.');
	}

	public function destroy(string $id): RedirectResponse
	{
		$worker = User::where('role', 'worker')->findOrFail($id);
		$worker->delete();

		return back()->with('success', 'Trabajador eliminado correctamente.');
	}
}
