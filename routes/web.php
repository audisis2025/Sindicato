<?php

/**
 * ===========================================================
 * File name: web.php
 * Description: Main routes of the SINDISOFT system.
 * Creation date: 01/11/2025
 * Author: Iker Piza
 * Release date: 01/11/2025
 * Approved by: Technical Lead
 * Version: 1.7
 * Maintenance type: Extension.
 * Change description: Integrated worker notifications into dashboard load.
 * Responsible: Iker Piza
 * Reviewer: QA SINDISOFT
 * ===========================================================
 */

use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;
use Livewire\Volt\Volt;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminConfigurationController;
use App\Http\Controllers\Union\MemberController;
use App\Http\Controllers\Union\ProcedureController;
use App\Http\Controllers\Union\DocumentController;
use App\Http\Controllers\Union\ReportController;
use App\Http\Controllers\Union\NewsController;
use App\Http\Controllers\Union\WorkerRequestController;
use App\Http\Controllers\Union\WorkerProcedureController;
use App\Models\Notification;
use Illuminate\Support\Facades\Auth;
// ===============================
// 🔸 Public Home Page
// ===============================
Route::get('/', function () {
    return view('welcome');
})->name('home');

// ===============================
// 🔸 Main Dashboard (custom logic)
// ===============================
Route::get('/dashboard', function () {
    $user = Auth::user();
    $rol  = $user?->rol ?? 'trabajador';


    $notificaciones = collect();

    if ($rol === 'trabajador') {
        $notificaciones = Notification::where('user_id', Auth::id())
            ->where('estado', 'no_leida')
            ->latest()
            ->take(5)
            ->get();
    }

    return view('dashboard', compact('rol', 'notificaciones'));
})
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

// ===============================
// 🔸 Authenticated Routes
// ===============================
Route::middleware(['auth'])->group(function () {

    // ⚙️ User Settings
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

    // ===============================
    // 👑 Administrator Module
    // ===============================
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
    Route::post('/users', [UserController::class, 'store'])->name('users.store');
    Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
    Route::patch('/users/{user}/toggle', [UserController::class, 'toggle'])->name('users.toggle');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');

    Route::get('/admin/configuration', [AdminConfigurationController::class, 'index'])->name('admin.configuration');
    Route::put('/admin/configuration', [AdminConfigurationController::class, 'update'])->name('admin.configuration.update');
    Route::post('/admin/configuration/backup', [AdminConfigurationController::class, 'backup'])->name('admin.configuration.backup');
    Route::get('/admin/configuration/logs', [AdminConfigurationController::class, 'logs'])->name('admin.configuration.logs');

    // ===============================
    // 🏛️ UNION MODULE (SINDICATO)
    // ===============================
    Route::prefix('union')->middleware(['auth'])->group(function () {

        // 👷 Gestión de Trabajadores (RF02–RF13)
        Route::get('/members', [MemberController::class, 'index'])->name('union.members.index');
        Route::get('/members/create', [MemberController::class, 'create'])->name('union.members.create');
        Route::post('/members', [MemberController::class, 'store'])->name('union.members.store');
        Route::get('/members/{id}/edit', [MemberController::class, 'edit'])->name('union.members.edit');
        Route::put('/members/{id}', [MemberController::class, 'update'])->name('union.members.update');
        Route::delete('/members/{id}', [MemberController::class, 'destroy'])->name('union.members.destroy');
        Route::patch('/members/{id}/notify-error', [MemberController::class, 'notifyError'])->name('union.members.notify-error');

        // 🧾 Gestión de Trámites (RF06–RF14)
        Route::get('/procedures', [ProcedureController::class, 'index'])->name('union.procedures.index');
        Route::get('/procedures/create', [ProcedureController::class, 'create'])->name('union.procedures.create');
        Route::post('/procedures', [ProcedureController::class, 'store'])->name('union.procedures.store');
        Route::get('/procedures/{id}/edit', [ProcedureController::class, 'edit'])->name('union.procedures.edit');
        Route::get('/procedures/{id}', [ProcedureController::class, 'show'])->name('union.procedures.show');
        Route::put('/procedures/{id}', [ProcedureController::class, 'update'])->name('union.procedures.update');
        Route::delete('/procedures/{id}', [ProcedureController::class, 'destroy'])->name('union.procedures.destroy');

        // 🧩 Seguimiento de solicitudes individuales
        Route::get('/requests', [WorkerRequestController::class, 'index'])->name('union.workers.requests.index');
        Route::get('/procedures/requests/{id}', [ProcedureController::class, 'showRequest'])->name('union.procedures.requests.show');
        Route::post('/procedures/{id}/steps/{step}/notify-error', [ProcedureController::class, 'notifyError'])->name('union.procedures.notify-error');
        Route::post('/procedures/{id}/steps/{step}/approve', [ProcedureController::class, 'approveStep'])->name('union.procedures.approve-step');
        Route::post('/procedures/{id}/finalize/{estado}', [ProcedureController::class, 'finalize'])->name('union.procedures.finalize');

        // 📂 Documentos y formatos
        Route::get('/documents', [DocumentController::class, 'index'])->name('union.documents.index');
        Route::post('/documents/upload', [DocumentController::class, 'upload'])->name('union.documents.upload');
        Route::get('/documents/download/{id}', [DocumentController::class, 'download'])->name('union.documents.download');
        Route::delete('/documents/{id}', [DocumentController::class, 'destroy'])->name('union.documents.destroy');

        // 📊 Reportes
        Route::get('/reports', [ReportController::class, 'index'])->name('union.reports.index');
        Route::get('/reports/data', [ReportController::class, 'getChartData'])->name('union.reports.data');
        Route::get('/reports/export/pdf', [ReportController::class, 'exportPdf'])->name('union.reports.export-pdf');
        Route::get('/reports/export/excel', [ReportController::class, 'exportExcel'])->name('union.reports.export-excel');
        Route::get('/reports/export/csv', [ReportController::class, 'exportCsv'])->name('union.reports.export-csv');

        // 📢 Noticias y convocatorias
        Route::get('/news', [NewsController::class, 'index'])->name('union.news.index');
        Route::get('/news/create', [NewsController::class, 'create'])->name('union.news.create');
        Route::post('/news', [NewsController::class, 'store'])->name('union.news.store');
        Route::delete('/news/{id}', [NewsController::class, 'destroy'])->name('union.news.destroy');

        // ===============================
        // 👷 WORKER MODULE (TRABAJADOR)
        // ===============================
        Route::prefix('worker')->middleware(['auth'])->group(function () {

            // 🏠 Panel principal
            Route::get('/', [WorkerProcedureController::class, 'index'])->name('worker.index');

            // 🚀 Iniciar trámite
            Route::post('/procedures/start/{id}', [WorkerProcedureController::class, 'start'])
                ->name('worker.procedures.start');

            // ❌ Cancelar trámite
            Route::delete('/procedures/{id}/cancel', [WorkerProcedureController::class, 'cancel'])
                ->name('worker.procedures.cancel');

            // 👣 Ver pasos
            Route::get('/procedures/{id}', [WorkerProcedureController::class, 'show'])
                ->name('worker.procedures.show');

            // ✅ Completar paso
            Route::post(
                '/procedures/{solicitudId}/steps/{pasoId}/complete',
                [WorkerProcedureController::class, 'completeStep']
            )
                ->name('worker.procedures.complete-step');

            // 👁️ Ver detalle completo
            Route::get('/requests/{id}', [WorkerProcedureController::class, 'showDetail'])
                ->name('worker.requests.show');

            // 📤 Subir archivo de paso
            Route::post(
                '/procedures/{solicitudId}/steps/{pasoId}/upload',
                [WorkerProcedureController::class, 'upload']
            )
                ->name('worker.procedures.upload');

            // 📰 Noticias / convocatorias visibles para trabajador
            Route::get('/news', [WorkerProcedureController::class, 'showNews'])
                ->name('worker.news.index');

            // 🔔 Notificaciones del trabajador
            Route::get('/notifications', [WorkerProcedureController::class, 'showNotifications'])
                ->name('worker.notifications.index');
            Route::post('/notifications/read/{id}', [WorkerProcedureController::class, 'markAsRead'])
                ->name('worker.notifications.read');

            // 🛠️ Soporte
            Route::view('/support', 'worker.support')->name('worker.support');
        });
    });
});
