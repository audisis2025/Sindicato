<?php

/**
 * ===========================================================
 * File name: web.php
 * Description: Rutas públicas y de autenticación principales.
 * (Actualizado para delegar rutas de módulos)
 * ===========================================================
 */

use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;
use Livewire\Volt\Volt;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;


Route::get('/', function () 
{
    return view('welcome');
})->name('home');


Route::get('/dashboard', function ()
{
    $user = Auth::user();

    $rol = $user->role;

    $notificaciones = collect();

    if ($rol === 'worker') {
        $notificaciones = \App\Models\Notification::where('user_id', $user->id)
            ->where('status', 'unread')
            ->latest()
            ->take(5)
            ->get();
    }

    return view('dashboard', compact('rol', 'notificaciones'));
})
    ->middleware(['auth', 'verified'])
    ->name('dashboard');


Route::middleware(['auth'])->group(function () 
{
    Route::redirect('settings', 'settings/profile');

    Volt::route('settings/profile', 'settings.profile')->name('profile.edit');
    Volt::route('settings/password', 'settings.password')->name('user-password.edit');
    Volt::route('settings/appearance', 'settings.appearance')->name('appearance.edit');

    Volt::route('settings/two-factor', 'settings.two-factor')
        ->middleware(
            when(
                Features::canManageTwoFactorAuthentication()
                    && Features::optionEnabled(Features::twoFactorAuthentication(), 'confirmPassword'),
                ['password.confirm'],
                [],
            ),
        )
        ->name('two-factor.show');
});
