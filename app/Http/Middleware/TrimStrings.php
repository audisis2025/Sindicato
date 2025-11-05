<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\TrimStrings as Middleware;

class TrimStrings extends Middleware
{
    /**
     * Campos que no deben recortarse.
     */
    protected $except = [
        'password',
        'password_confirmation',
    ];
}
