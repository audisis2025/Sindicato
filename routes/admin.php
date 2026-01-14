<?php

/*
* ===========================================================
* Nombre del archivo        : admin.php
* Descripción de la clase : Define las rutas del módulo de Administración.
* Fecha de creación       : 14/11/2025
* Elaboró                 : Iker Piza
* Fecha de liberación     : 14/11/2025
* Autorizó                : Technical Lead
* Versión                 : 1.1
*
* Fecha de mantenimiento    : 14/11/2025
* Folio de mantenimiento  : 001
* Tipo de mantenimiento   : Correctivo
* Descripción del mantenimiento: Se corrige el namespace de AdminConfigurationController
* y se aplica la cabecera del manual (PRO-Laravel V3.3).
* Responsable             : 
* Revisor                 : Iker Piza
* ===========================================================
*/

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminConfigurationController;
use App\Http\Controllers\AdminProfileController;

Route::middleware(['auth', 'isAdmin'])->group(function () {

    Route::get('/admin-profile', [AdminProfileController::class, 'edit'])->name('admin.profile.edit');
    Route::put('/admin-profile', [AdminProfileController::class, 'update'])->name('admin.profile.update');

    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
    Route::post('/users', [UserController::class, 'store'])->name('users.store');
    Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
    Route::patch('/users/{user}/toggle', [UserController::class, 'toggle'])->name('users.toggle');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');

    Route::get('/admin/reminders', [AdminConfigurationController::class, 'reminders'])
        ->name('admin.reminders');

    Route::post('/admin/reminders/update', [AdminConfigurationController::class, 'updateReminders'])
        ->name('admin.reminders.update');

    Route::delete('/admin/logs/clear', [AdminConfigurationController::class, 'clearLogs'])
        ->name('admin.configuration.logs.clear');

    Route::get('/admin/logs/export-word', [AdminConfigurationController::class, 'exportWord'])
        ->name('admin.configuration.logs.exportWord');

    Route::get('/admin/configuration', [AdminConfigurationController::class, 'index'])
        ->name('admin.configuration');

    Route::put('/admin/configuration', [AdminConfigurationController::class, 'update'])
        ->name('admin.configuration.update');

    Route::post('/admin/configuration/backup', [AdminConfigurationController::class, 'backup'])
        ->name('admin.configuration.backup');

    Route::get('/admin/configuration/logs', [AdminConfigurationController::class, 'logs'])
        ->name('admin.configuration.logs');
});
