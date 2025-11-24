<?php

/**
 * ===========================================================
 * File name: web.php
 * Descripción: Rutas públicas, login con Livewire Volt,
 * dashboard por roles y módulos institucionales SINDISOFT.
 * Versión: 4.0
 * ===========================================================
 */

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Laravel\Fortify\Features;
use Livewire\Volt\Volt;

/*
|--------------------------------------------------------------------------
| Página principal
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
})->name('home');


/*
|--------------------------------------------------------------------------
| LOGIN / LOGOUT — LIVEWIRE VOLT
|--------------------------------------------------------------------------
| SECCIÓN CRÍTICA PARA SOLUCIONAR TU ERROR
| "Using $this when not in object context"
|--------------------------------------------------------------------------
*/

// LOGIN (Volt)
Volt::route('login', 'auth.login')->name('login');

// LOGOUT tradicional de Laravel
Route::post('/logout', function () {
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect()->route('login')->with('status', 'Sesión cerrada correctamente.');
})->name('logout');



/*
|--------------------------------------------------------------------------
| DASHBOARD GENERAL (con roles)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'verified'])->get('/dashboard', function () {
    $user = Auth::user();
    $rol  = $user->role;

    if ($rol === 'worker') {
        return redirect()->route('worker.catalog.index');
    }

    return view('dashboard');
})->name('dashboard');





/*
|--------------------------------------------------------------------------
| AJUSTES / SETTINGS (Livewire Volt)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->group(function () {

    Route::redirect('settings', '/settings/profile');

    Volt::route('settings/profile',     'settings.profile')->name('profile.edit');
    Volt::route('settings/password',    'settings.password')->name('user-password.edit');
    Volt::route('settings/appearance',  'settings.appearance')->name('appearance.edit');

    Volt::route('settings/two-factor', 'settings.two-factor')
        ->middleware(
            when(
                Features::canManageTwoFactorAuthentication()
                    && Features::optionEnabled(Features::twoFactorAuthentication(), 'confirmPassword'),
                ['password.confirm'],
                []
            )
        )
        ->name('two-factor.show');
});



/*
|--------------------------------------------------------------------------
| RUTAS DE MÓDULOS SINDICALES
|--------------------------------------------------------------------------
*/

require __DIR__ . '/union.php';
require __DIR__ . '/admin.php';
require __DIR__ . '/worker.php';
