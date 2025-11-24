<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class MemberController extends Controller
{
    /**
     * Listado de trabajadores
     */
    public function index(Request $request): View
    {
        $workers = User::query()
            ->where('role', 'worker')
            ->when($request->name, fn($q, $name) => $q->where('name', 'like', "%{$name}%"))
            ->when($request->gender, fn($q, $gender) => $q->where('gender', $gender))
            ->orderBy('name')
            ->get();

        return view('union.members.index', compact('workers'));
    }

    /**
     * Formulario crear trabajador
     */
    public function create(): View
    {
        return view('union.members.create');
    }

    /**
     * Guardar trabajador
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name'       => 'required|string|max:255',
            'email'      => 'required|email|max:100|unique:users,email',
            'curp'       => 'nullable|string|max:18',
            'rfc'        => 'nullable|string|max:13',
            'gender' => 'nullable|in:H,M,ND,X',
            'budget_key' => 'nullable|string|max:50',
        ]);

        User::create([
            'name'       => $request->name,
            'email'      => $request->email,
            'role'       => 'worker',
            'curp'       => $request->curp,
            'rfc'        => $request->rfc,
            'gender'     => $request->gender,
            'budget_key' => $request->budget_key,
            'password'   => Hash::make('12345678'), // Contraseña predeterminada
            'active'     => true,
        ]);

        return redirect()->route('union.members.index')
            ->with('success', 'Trabajador registrado correctamente.');
    }

    /**
     * Formulario editar trabajador
     */
    public function edit(string $id): View
    {
        $worker = User::where('role', 'worker')->findOrFail($id);

        return view('union.members.edit', compact('worker'));
    }

    /**
     * Actualizar trabajador
     */
    public function update(Request $request, string $id): RedirectResponse
    {
        $worker = User::findOrFail($id);

        $request->validate([
            'name'       => 'required|string|max:255',
            'email'      => 'required|email|max:100|unique:users,email,' . $worker->id,
            'curp'       => 'nullable|string|max:18',
            'rfc'        => 'nullable|string|max:13',
            'gender' => 'nullable|in:H,M,ND,X',
            'budget_key' => 'nullable|string|max:50',
            'active'     => 'required|boolean',
        ]);

        $worker->update([
            'name'       => $request->name,
            'email'      => $request->email,
            'active'     => $request->active,
            'curp'       => $request->curp,
            'rfc'        => $request->rfc,
            'gender'     => $request->gender,
            'budget_key' => $request->budget_key,
        ]);

        return redirect()->route('union.members.index')
            ->with('success', 'Datos del trabajador actualizados correctamente.');
    }

    /**
     * Eliminar trabajador
     */
    public function destroy(string $id): RedirectResponse
    {
        $worker = User::where('role', 'worker')->findOrFail($id);
        $worker->delete();

        return back()->with('success', 'Trabajador eliminado correctamente.');
    }
}
