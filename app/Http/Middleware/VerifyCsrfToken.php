<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * URIs que deben excluirse de la verificación CSRF (si lo deseas).
     */
    protected $except = [
        // 'webhook/*',
    ];
}
