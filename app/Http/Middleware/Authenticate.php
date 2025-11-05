<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Authenticate extends Middleware
{
    /**
     * Redirige al usuario a la ruta de login si no está autenticado.
     */
    protected function redirectTo($request): ?string
    {
        if (! $request->expectsJson()) {
            // 🔒 Aquí defines la ruta del login (Fortify la maneja automáticamente)
            return route('login');
        }

        return null;
    }
}
