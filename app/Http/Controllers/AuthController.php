<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * Maneja el inicio de sesión del usuario y redirige según su rol.
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'usuario' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            // 🔹 Obtener el rol actual del usuario autenticado
            $rol = Auth::user()->rol;

            // 🔸 Redirección al dashboard con mensaje personalizado
            switch ($rol) {
                case 'administrador':
                    return redirect()
                        ->route('dashboard')
                        ->with('status', 'Bienvenido Administrador del sistema.');
                case 'sindicato':
                    return redirect()
                        ->route('dashboard')
                        ->with('status', 'Bienvenido Usuario Sindicato.');
                case 'trabajador':
                default:
                    return redirect()
                        ->route('dashboard')
                        ->with('status', 'Bienvenido Usuario Trabajador.');
            }
        }

        // ❌ Credenciales incorrectas
        return back()->withErrors([
            'usuario' => 'Usuario o contraseña incorrectos.',
        ])->onlyInput('usuario');
    }

    /**
     * Cierra sesión y destruye la sesión activa.
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')
            ->with('status', 'Sesión cerrada correctamente.');
    }
}
