<?php

/*
* ===========================================================
* Nombre de la clase: MemberController.php
* Descripción de la clase: Controlador para la gestión de trabajadores (miembros)
* del sindicato. RF02.
* Fecha de creación: 02/11/2025
* Elaboró: Iker Piza
* Fecha de liberación: 02/11/2025
* Autorizó: Líder Técnico
* Versión: 2.1
*
* Fecha de mantenimiento: 10/11/2025
* Folio de mantenimiento: [Tu Folio]
* Tipo de mantenimiento: Perfectivo
* Descripción del mantenimiento: Refactorizado para eliminar 'usuarios_detalle'...
* Responsable: [Tu Nombre]
* Revisor: [Tu Revisor]
*
* Fecha de mantenimiento: 12/11/2025
* Folio de mantenimiento: [Tu Folio 2]
* Tipo de mantenimiento: Perfectivo (Traducción)
* Descripción del mantenimiento: Se traducen todos los campos (store/update) y
* consultas (where) para alinear con la migración 'users' en inglés.
* Responsable: [Tu Nombre]
* Revisor: Gemini
* ===========================================================
*/

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class MemberController extends Controller
{
    /**
    * Muestra el listado de trabajadores registrados (RF02).
    *
    * @return \Illuminate\View\View
    */
    public function index(): View
    {
        // Corregido: 'role' y 'worker'
        $workers = User::where('role', 'worker')
            ->orderBy('id', 'desc')
            ->get();
        
        return view('union.members.index', compact('workers'));
    }

    /**
    * Muestra el formulario de registro de trabajador (RF02).
    *
    * @return \Illuminate\View\View
    */
    public function create(): View
    {
        return view('union.members.create');
    }

    /**
    * Guarda un nuevo trabajador en la base de datos (RF02).
    *
    * @param \Illuminate\Http\Request $request
    * @return \Illuminate\Http\RedirectResponse
    */
    public function store(Request $request): RedirectResponse
    {
        // --- CAMPOS TRADUCIDOS ---
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:100|unique:users,email',
            'curp' => 'nullable|string|max:18',
            'rfc' => 'nullable|string|max:13',
            'gender' => 'nullable|in:H,M', // 'sexo'
            'budget_key' => 'nullable|string|max:50', // 'clave_presupuestal'
        ]);

        $username = strtolower(str_replace(' ', '', substr($request->name, 0, 8))) . rand(10, 99);

        // --- CAMPOS TRADUCIDOS ---
        User::create([
            'username' => $username, // 'usuario'
            'name' => $request->name,
            'email' => $request->email,
            'role' => 'worker', // 'rol'
            'curp' => $request->curp,
            'rfc' => $request->rfc,
            'gender' => $request->gender, // 'sexo'
            'budget_key' => $request->budget_key, // 'clave_presupuestal'
            'password' => Hash::make('12345678'), 
            'active' => true, // 'activo'
        ]);

        return redirect()->route('union.members.index')
            ->with('success', ' ✅  Trabajador registrado correctamente.');
    }

    /**
    * Muestra el formulario para editar un trabajador.
    *
    * @param string $id
    * @return \Illuminate\View\View
    */
    public function edit(string $id): View
    {
        // Corregido: 'role' y 'worker'
        $worker = User::where('role', 'worker')->findOrFail($id);
        
        return view('union.members.edit', compact('worker'));
    }

    /**
    * Actualiza los datos de un trabajador.
    *
    * @param \Illuminate\Http\Request $request
    * @param string $id
    * @return \Illuminate\Http\RedirectResponse
    */
    public function update(Request $request, string $id): RedirectResponse
    {
        $worker = User::findOrFail($id);

        // --- CAMPOS TRADUCIDOS ---
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email|max:100|unique:users,email,' . $worker->id,
            'curp' => 'nullable|string|max:18',
            'rfc' => 'nullable|string|max:13',
            'gender' => 'nullable|in:H,M', // 'sexo'
            'budget_key' => 'nullable|string|max:50', // 'clave_presupuestal'
            'active' => 'required|boolean', // 'activo'
        ]);

        // --- CAMPOS TRADUCIDOS ---
        $worker->update([
            'name' => $request->name,
            'email' => $request->email,
            'active' => $request->active, // 'activo'
            'curp' => $request->curp,
            'rfc' => $request->rfc,
            'gender' => $request->gender, // 'sexo'
            'budget_key' => $request->budget_key, // 'clave_presupuestal'
        ]);
        
        return redirect()->route('union.members.index')
            ->with('success', ' 📝  Datos del trabajador actualizados correctamente.');
    }

    /**
    * Elimina un trabajador de la base de datos.
    *
    * @param string $id
    * @return \Illuminate\Http\RedirectResponse
    */
    public function destroy(string $id): RedirectResponse
    {
        // Corregido: 'role' y 'worker'
        $worker = User::where('role', 'worker')->findOrFail($id);
        $worker->delete();
        
        return back()->with('success', ' 🗑️  Trabajador eliminado correctamente.');
    }
}