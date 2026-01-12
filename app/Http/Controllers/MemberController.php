<?php
/*
* ===========================================================
* Nombre de la clase: MemberController
* Descripción de la clase: Gestiona la administración de trabajadores 
* por parte del Sindicato (CRUD de miembros).
* Fecha de creación: 05/11/2025
* Elaboró: [Tu Nombre]
* Fecha de liberación: 05/11/2025
* Autorizó: Líder Técnico
* Versión: 1.0
*
* Fecha de mantenimiento: [DD/MM/AAAA]
* Folio de mantenimiento: [Folio]
* Tipo de mantenimiento: [Correctivo/Perfectivo/Adaptativo/Preventivo]
* Descripción del mantenimiento: [Descripción breve]
* Responsable: [Tu Nombre]
* Revisor: [Revisor]
* ===========================================================
*/

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class MemberController extends Controller
{
	public function index(Request $request): View
	{
		$workers = User::query()
			->where('role', 'worker')
			->when($request->name, function ($q, $name)
			{
				return $q->where('name', 'like', "%{$name}%");
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

	public function store(Request $request): RedirectResponse
	{
		$request->validate([
			'name' => 'required|string|max:255',
			'email' => 'required|email|max:100|unique:users,email',
			'curp' => 'nullable|string|max:18',
			'rfc' => 'nullable|string|max:13',
			'gender' => 'nullable|in:H,M,ND,X',
			'budget_key' => 'nullable|string|max:50',
		]);

		User::create([
			'name' => $request->name,
			'email' => $request->email,
			'role' => 'worker',
			'curp' => $request->curp,
			'rfc' => $request->rfc,
			'gender' => $request->gender,
			'budget_key' => $request->budget_key,
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

	public function update(Request $request, string $id): RedirectResponse
	{
		$worker = User::findOrFail($id);

		$request->validate([
			'name' => 'required|string|max:255',
			'email' => 'required|email|max:100|unique:users,email,' . $worker->id,
			'curp' => 'nullable|string|max:18',
			'rfc' => 'nullable|string|max:13',
			'gender' => 'nullable|in:H,M,ND,X',
			'budget_key' => 'nullable|string|max:50',
			'active' => 'required|boolean',
		]);

		$worker->update([
			'name' => $request->name,
			'email' => $request->email,
			'active' => $request->active,
			'curp' => $request->curp,
			'rfc' => $request->rfc,
			'gender' => $request->gender,
			'budget_key' => $request->budget_key,
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
