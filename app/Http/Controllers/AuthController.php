<?php

/*
* ===========================================================
* Nombre de la clase: AuthController.php
* Descripción de la clase: Controlador para manejar la autenticación (login/logout)
* y la redirección por roles del sistema.
* Fecha de creación: 05/11/2025
* Elaboró: Iker Piza
* Fecha de liberación: 10/11/2025
* Autorizó: Líder Técnico
* Versión: 1.2
*
* Fecha de mantenimiento: 10/11/2025
* Folio de mantenimiento: [Tu Folio]
* Tipo de mantenimiento: Perfectivo
* Descripción del mantenimiento: Se añaden tipos de retorno, prólogo y DocBlocks...
* Responsable: [Tu Nombre]
* Revisor: [Tu Revisor]
*
* Fecha de mantenimiento: 12/11/2025
* Folio de mantenimiento: [Tu Folio 2]
* Tipo de mantenimiento: Perfectivo (Traducción)
* Descripción del mantenimiento: Se traducen los campos de 'usuario' a 'username'
* y los valores de 'rol' a 'role' (admin, union, worker) para alinear
* con la nueva migración de BD.
* Responsable: [Tu Nombre]
* Revisor: Gemini
* ===========================================================
*/

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View; // Importar View para el formulario de login

class AuthController extends Controller
{
    /**
     * Muestra la vista de login.
     *
     * @return \Illuminate\View\View
     */
    public function showLoginForm(): View
    {
        return view('auth.login'); // Asumiendo que la vista está en auth/login.blade.php
    }

    /**
    * Maneja el inicio de sesión del usuario y redirige según su rol.
    *
    * @param \Illuminate\Http\Request $request
    * @return \Illuminate\Http\RedirectResponse
    */
    public function login(Request $request): RedirectResponse
    {
        // Corregido: 'username' en lugar de 'usuario'
        $credentials = $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        // Corregido: 'username' en lugar de 'usuario'
        if (Auth::attempt(['username' => $credentials['username'], 'password' => $credentials['password']], $request->boolean('remember'))) {
            $request->session()->regenerate();

            // Corregido: 'role' en lugar de 'rol'
            $role = Auth::user()->role;

            // Corregido: valores del switch en inglés
            switch ($role) {
                case 'admin': // 'administrador'
                    return redirect()
                        ->route('dashboard') // O 'admin.dashboard'
                        ->with('status', 'Bienvenido Administrador del sistema.');
                case 'union': // 'sindicato'
                    return redirect()
                        ->route('dashboard') // O 'union.dashboard'
                        ->with('status', 'Bienvenido Usuario Sindicato.');
                case 'worker': // 'trabajador'
                default:
                    return redirect()
                        ->route('dashboard') // O 'worker.dashboard'
                        ->with('status', 'Bienvenido Usuario Trabajador.');
            }
        }

        // Corregido: 'username' en lugar de 'usuario'
        return back()->withErrors([
            'username' => 'Usuario o contraseña incorrectos.',
        ])->onlyInput('username');
    }

    /**
    * Cierra sesión y destruye la sesión activa.
    *
    * @param \Illuminate\Http\Request $request
    * @return \Illuminate\Http\RedirectResponse
    */
    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')
            ->with('status', 'Sesión cerrada correctamente.');
    }
}