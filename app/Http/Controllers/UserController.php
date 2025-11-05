<?php

/**
 * ===========================================================
 * File name: UserController.php
 * Description: Controller to manage user creation and listing in SINDISOFT.
 * Creation date: 01/11/2025
 * Author: Iker Piza
 * Release date: 01/11/2025
 * Approved by: Technical Lead
 * Version: 1.0
 * Maintenance type: Creation.
 * Maintenance description: Enables administrator to create users for the
 * Union and Workers modules, with validation and role assignment.
 * Responsible: Iker Piza
 * Reviewer: QA SINDISOFT
 * ===========================================================
 */

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display all registered users.
     */
    public function index()
    {
        $users = User::orderBy('id', 'desc')->get();

        return view('admin.users.index', compact('users'));
    }

    /**
     * Show form to create a new user.
     */
    public function create()
    {
        return view('admin.users.create');
    }

    /**
     * Store a newly created user in database.
     */
    public function store(Request $request)
    {
        $request->validate([
            'usuario' => 'required|string|max:50|unique:users,usuario',
            'name' => 'required|string|max:100',
            'email' => 'nullable|email|max:100|unique:users,email',
            'password' => 'required|string|min:8',
            'rol' => 'required|in:sindicato,trabajador',
            'curp' => 'nullable|string|max:18',
            'rfc' => 'nullable|string|max:13',
            'sexo' => 'nullable|in:H,M',
            'clave_presupuestal' => 'nullable|string|max:50',
        ]);

        $user = User::create([
            'usuario' => $request->usuario,
            'name' => $request->name,
            'email' => $request->email ?? null,
            'password' => Hash::make($request->password),
            'rol' => $request->rol,
            'activo' => true,
        ]);

        $user->detalle()->create([
            'curp' => $request->curp,
            'rfc' => $request->rfc,
            'sexo' => $request->sexo,
            'clave_presupuestal' => $request->clave_presupuestal,
            'activo' => true,
        ]);

        return redirect()->route('users.index')->with('success', '✅ Usuario creado correctamente.');
    }
    /**
     * Show edit form.
     */
    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    /**
     * Update user data.
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'nullable|email|max:100|unique:users,email,' . $user->id,
            'rol' => 'required|in:sindicato,trabajador',
        ]);

        $user->update($request->only('name', 'email', 'rol'));

        return redirect()->route('users.index')->with('success', '✅ Usuario actualizado correctamente.');
    }

    /**
     * Toggle activation status.
     */
    public function toggle(User $user)
    {
        $user->activo = !$user->activo;
        $user->save();

        return back()->with('success', '⚙️ Estado del usuario actualizado.');
    }

    /**
     * Delete user (logical or real).
     */
    public function destroy(User $user)
    {
        $user->delete();

        return back()->with('success', '🗑️ Usuario eliminado correctamente.');
    }
}
