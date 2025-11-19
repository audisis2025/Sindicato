<?php

/*
* ===========================================================
* Nombre de la clase: UserController.php
* Descripción de la clase: Controlador para gestionar la creación y listado de usuarios
* por el Administrador.
* Fecha de creación: 01/11/2025
* Elaboró: Iker Piza
* Fecha de liberación: 01/11/2025
* Autorizó: Líder Técnico
* Versión: 2.1
*
* Fecha de mantenimiento: 12/11/2025
* Folio de mantenimiento: [Tu Folio 2]
* Tipo de mantenimiento: Perfectivo (Traducción)
* Descripción del mantenimiento: Se traducen todos los campos (store/update) para
* alinear con la migración de 'users' en inglés (username, role, gender, etc.).
* Responsable: [Tu Nombre]
* Revisor: Gemini
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
    /**
     * Aplica el middleware de administrador a todos los métodos.
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('isAdmin');
    }

    /**
     * Muestra todos los usuarios registrados.
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        $query = User::query();

        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        $users = $query->orderBy('id', 'desc')->get();

        return view('admin.users.index', compact('users'));
    }


    /**
     * Muestra el formulario para crear un nuevo usuario.
     *
     * @return \Illuminate\View\View
     */
    public function create(): View
    {
        return view('admin.users.create');
    }

    /**
     * Almacena un nuevo usuario en la base de datos.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        $validatedData = $request->validate([
            'username' => 'required|string|max:50|unique:users,username',
            'name' => 'required|string|max:100',
            'email' => 'nullable|email|max:100|unique:users,email',
            'password' => 'required|string|min:8',
            'role' => 'required|in:union,worker',
            'curp' => 'nullable|string|max:18',
            'rfc' => 'nullable|string|max:13',
            'gender' => 'nullable|in:H,M',
            'budget_key' => 'nullable|string|max:50',
        ]);

        $user = User::create([
            'username' => $validatedData['username'],
            'name' => $validatedData['name'],
            'email' => $validatedData['email'] ?? null,
            'password' => Hash::make($validatedData['password']),
            'role' => $validatedData['role'],
            'curp' => $validatedData['curp'] ?? null,
            'rfc' => $validatedData['rfc'] ?? null,
            'gender' => $validatedData['gender'] ?? null,
            'budget_key' => $validatedData['budget_key'] ?? null,
            'active' => true,
        ]);

        app(\App\Services\SystemLogger::class)->log(
            'Crear usuario',
            'El administrador creó al usuario: ' . $user->username
        );

        return redirect()->route('users.index')->with('success', 'Usuario creado correctamente.');
    }
    /**
     * Muestra el formulario de edición del usuario.
     *
     * @param \App\Models\User $user
     * @return \Illuminate\View\View
     */
    public function edit(User $user): View
    {
        return view('admin.users.edit', compact('user'));
    }

    /**
     * Actualiza los datos del usuario.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\User $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, User $user): RedirectResponse
    {
        // Lógica corregida: Se validan todos los campos en inglés
        $validatedData = $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'nullable|email|max:100|unique:users,email,' . $user->id,
            'role' => 'required|in:union,worker', // 'rol' y valores
            'curp' => 'nullable|string|max:18',
            'rfc' => 'nullable|string|max:13',
            'gender' => 'nullable|in:H,M', // 'sexo'
            'budget_key' => 'nullable|string|max:50', // 'clave_presupuestal'
            'active' => 'required|boolean', // 'activo'
        ]);

        $user->update($validatedData);
        app(\App\Services\SystemLogger::class)->log(
            'Actualizar usuario',
            'El administrador actualizó al usuario: ' . $user->username
        );

        return redirect()->route('users.index')->with('success', ' ✅  Usuario actualizado correctamente.');
    }

    /**
     * Elimina un usuario de la base de datos.
     *
     * @param \App\Models\User $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(User $user): RedirectResponse
    {
        $user->delete();
        app(\App\Services\SystemLogger::class)->log(
            'Eliminar usuario',
            'El administrador eliminó al usuario: ' . $user->username
        );

        return back()->with('success', ' 🗑️  Usuario eliminado correctamente.');
    }
    public function toggle($id)
    {
        $user = User::findOrFail($id);

        $user->active = !$user->active;
        $user->save();

        return back()->with('success', 'Estado del usuario actualizado correctamente.');
    }
}
