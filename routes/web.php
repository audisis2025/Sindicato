<?php
/*
* Nombre del archivo          : web.php
* Descripción del archivo     : Archivo principal de definición de rutas del sistema.
*                               Gestiona el acceso general, autenticación, sesión,
*                               redirecciones por rol, configuración de usuario y
*                               carga de los módulos de administración, sindicato
*                               y trabajador.
* Fecha de creación           : 29/09/2025
* Elaboró                     : Iker Piza
* Fecha de liberación         : 14/12/2025
* Autorizó                   : Salvador Monroy
* Versión                     : 1.0
* Fecha de mantenimiento     :
* Folio de mantenimiento     :
* Tipo de mantenimiento      :
* Descripción del mantenimiento:
* Responsable                :
* Revisor                    :
*/

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Laravel\Fortify\Features;
use Livewire\Volt\Volt;
use App\Http\Controllers\WorkerNotificationController;

Route::get('/', function () {
    return view('welcome');
})->name('home');


Volt::route('login', 'auth.login')->name('login');
Volt::route('forgot-password', 'auth.forgot-password')->name('password.request');

Volt::route('reset-password/{token}', 'auth.reset-password')->name('password.reset');

Route::post('/logout', function () 
{
    Auth::logout();
    request()->session()->invalidate();
    request()->session()->regenerateToken();
    return redirect()->route('login')->with('status', 'Sesión cerrada correctamente.');
})->name('logout');



Route::middleware(['auth', 'verified'])->get('/dashboard', function () 
{
    $user = Auth::user();
    $rol  = $user->role;

    if ($rol === 'worker') {
        return redirect()->route('worker.catalog.index');
    }

    return view('dashboard');
})->name('dashboard');



Route::middleware(['auth'])->group(function () 
{

    Route::redirect('settings', '/settings/profile');

    Volt::route('settings/profile',     'settings.profile')->name('profile.edit');
    Volt::route('settings/password',    'settings.password')->name('user-password.edit');
    Volt::route('settings/appearance',  'settings.appearance')->name('appearance.edit');
    Route::get('/worker/notifications', [WorkerNotificationController::class, 'index'])
        ->name('worker.notifications.index');

    Route::patch('/worker/notifications/{id}/read', [WorkerNotificationController::class, 'markAsRead'])
        ->name('worker.notifications.read');

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


require __DIR__ . '/admin.php';
require __DIR__ . '/union.php';
require __DIR__ . '/worker.php';
