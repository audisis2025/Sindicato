<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\ProcedureController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\WorkerRequestController;
use App\Http\Controllers\NewsController;

Route::prefix('union')->middleware(['auth'])->group(function () {

    // Members
    Route::get('/members', [MemberController::class, 'index'])->name('union.members.index');
    Route::get('/members/create', [MemberController::class, 'create'])->name('union.members.create');
    Route::post('/members', [MemberController::class, 'store'])->name('union.members.store');
    Route::get('/members/{id}/edit', [MemberController::class, 'edit'])->name('union.members.edit');
    Route::put('/members/{id}', [MemberController::class, 'update'])->name('union.members.update');
    Route::delete('/members/{id}', [MemberController::class, 'destroy'])->name('union.members.destroy');
    Route::patch('/members/{id}/notify-error', [MemberController::class, 'notifyError'])->name('union.members.notify-error');

    // Procedures templates
    Route::get('/procedures', [ProcedureController::class, 'index'])->name('union.procedures.index');
    Route::get('/procedures/create', [ProcedureController::class, 'create'])->name('union.procedures.create');
    Route::post('/procedures', [ProcedureController::class, 'store'])->name('union.procedures.store');
    Route::get('/procedures/{id}/edit', [ProcedureController::class, 'edit'])->name('union.procedures.edit');
    Route::get('/procedures/{id}', [ProcedureController::class, 'show'])->name('union.procedures.show');
    Route::put('/procedures/{id}', [ProcedureController::class, 'update'])->name('union.procedures.update');
    Route::delete('/procedures/{id}', [ProcedureController::class, 'destroy'])->name('union.procedures.destroy');

    // Worker Requests
    Route::get('/requests', [WorkerRequestController::class, 'index'])->name('union.workers.requests.index');
    Route::get('/procedures/requests/{id}', [ProcedureController::class, 'showRequest'])->name('union.procedures.requests.show');
    Route::post('/procedures/{id}/steps/{step}/notify-error', [ProcedureController::class, 'notifyError'])->name('union.procedures.notify-error');
    Route::post('/procedures/{id}/steps/{step}/approve', [ProcedureController::class, 'approveStep'])->name('union.procedures.approve-step');
    Route::post('/procedures/{id}/finalize/{estado}', [ProcedureController::class, 'finalize'])->name('union.procedures.finalize');

    // News
    Route::get('/news', [NewsController::class, 'index'])->name('union.news.index');
    Route::get('/news/create', [NewsController::class, 'create'])->name('union.news.create');
    Route::post('/news', [NewsController::class, 'store'])->name('union.news.store');
    Route::get('/news/{news}/edit', [NewsController::class, 'edit'])->name('union.news.edit');
    Route::put('/news/{news}', [NewsController::class, 'update'])->name('union.news.update');
    Route::delete('/news/{news}', [NewsController::class, 'destroy'])->name('union.news.destroy');

    // Reports
    Route::get('/reports', [ReportController::class, 'index'])->name('union.reports.index');
    Route::get('/reports/data', [ReportController::class, 'getChartData'])->name('union.reports.data');

    Route::get('/reports/export/pdf',   [ReportController::class, 'exportPdf'])->name('union.reports.export-pdf');
    Route::get('/reports/export/excel', [ReportController::class, 'exportExcel'])->name('union.reports.export-excel');

    // NUEVO: Exportar Word
    Route::get('/reports/export/word',  [ReportController::class, 'exportWord'])->name('union.reports.export-word');

});
