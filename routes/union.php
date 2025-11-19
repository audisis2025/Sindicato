<?php

/*
* ===========================================================
* Nombre del archivo        : union.php
* Descripción de la clase : Define las rutas del módulo de Sindicato (Union).
* Fecha de creación       : 14/11/2025
* Elaboró                 : Iker Piza
* Fecha de liberación     : 14/11/2025
* Autorizó                : Technical Lead
* Versión                 : 1.2
*
* Fecha de mantenimiento    : 14/11/2025
* Folio de mantenimiento  : 002
* Tipo de mantenimiento   : Correctivo
* Descripción del mantenimiento: Se comentan las rutas de DocumentController y
* NewsController ya que los archivos no existen en 'controladores.docx'.
* Responsable             : 
* Revisor                 : Iker Piza
* ===========================================================
*/

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\ProcedureController;
// use App\Http\Controllers\Union\DocumentController; // Comentado porque no existe
use App\Http\Controllers\ReportController;
// use App\Http\Controllers\Union\NewsController; // Comentado porque no existe
use App\Http\Controllers\WorkerRequestController;

/*
|--------------------------------------------------------------------------
| Rutas de Sindicato (Union)
|--------------------------------------------------------------------------
|
| Estas rutas son cargadas por el RouteServiceProvider.
| Mantenemos el prefijo y middleware originales de tu web.php.
|
*/

Route::prefix('union')->middleware(['auth'/*, 'isUnion'*/])->group(function () {

    // Miembros (Sección OK)
    Route::get('/members', [MemberController::class, 'index'])->name('union.members.index');
    Route::get('/members/create', [MemberController::class, 'create'])->name('union.members.create');
    Route::post('/members', [MemberController::class, 'store'])->name('union.members.store');
    Route::get('/members/{id}/edit', [MemberController::class, 'edit'])->name('union.members.edit');
    Route::put('/members/{id}', [MemberController::class, 'update'])->name('union.members.update');
    Route::delete('/members/{id}', [MemberController::class, 'destroy'])->name('union.members.destroy');
    Route::patch('/members/{id}/notify-error', [MemberController::class, 'notifyError'])->name('union.members.notify-error');

    // Plantillas de Trámites (Sección OK)
    Route::get('/procedures', [ProcedureController::class, 'index'])->name('union.procedures.index');
    Route::get('/procedures/create', [ProcedureController::class, 'create'])->name('union.procedures.create');
    Route::post('/procedures', [ProcedureController::class, 'store'])->name('union.procedures.store');
    Route::get('/procedures/{id}/edit', [ProcedureController::class, 'edit'])->name('union.procedures.edit');
    Route::get('/procedures/{id}', [ProcedureController::class, 'show'])->name('union.procedures.show');
    Route::put('/procedures/{id}', [ProcedureController::class, 'update'])->name('union.procedures.update');
    Route::delete('/procedures/{id}', [ProcedureController::class, 'destroy'])->name('union.procedures.destroy');

    // Solicitudes de Trabajadores (Sección OK)
    Route::get('/requests', [WorkerRequestController::class, 'index'])->name('union.workers.requests.index');
    Route::get('/procedures/requests/{id}', [ProcedureController::class, 'showRequest'])->name('union.procedures.requests.show');
    Route::post('/procedures/{id}/steps/{step}/notify-error', [ProcedureController::class, 'notifyError'])->name('union.procedures.notify-error');
    Route::post('/procedures/{id}/steps/{step}/approve', [ProcedureController::class, 'approveStep'])->name('union.procedures.approve-step');
    Route::post('/procedures/{id}/finalize/{estado}', [ProcedureController::class, 'finalize'])->name('union.procedures.finalize');


    // TODO: Descomentar cuando existan los controladores DocumentController y NewsController
    /*
    // Documentos
    Route::get('/documents', [DocumentController::class, 'index'])->name('union.documents.index');
    Route::post('/documents/upload', [DocumentController::class, 'upload'])->name('union.documents.upload');
    Route::get('/documents/download/{id}', [DocumentController::class, 'download'])->name('union.documents.download');
    Route::delete('/documents/{id}', [DocumentController::class, 'destroy'])->name('union.documents.destroy');
    */
    Route::get('/news', function () {
        return "Módulo de Noticias del Sindicato en construcción.";
    })->name('union.news.index');

    // Reportes (Sección OK)
    Route::get('/reports', [ReportController::class, 'index'])->name('union.reports.index');
    Route::get('/reports/data', [ReportController::class, 'getChartData'])->name('union.reports.data');
    Route::get('/reports/export/pdf', [ReportController::class, 'exportPdf'])->name('union.reports.export-pdf');
    Route::get('/reports/export/excel', [ReportController::class, 'exportExcel'])->name('union.reports.export-excel');
    Route::get('/reports/export/csv', [ReportController::class, 'exportCsv'])->name('union.reports.export-csv');

    /*
    // Noticias
    Route::get('/news', [NewsController::class, 'index'])->name('union.news.index');
    Route::get('/news/create', [NewsController::class, 'create'])->name('union.news.create');
    Route::post('/news', [NewsController::class, 'store'])->name('union.news.store');
    Route::delete('/news/{id}', [NewsController::class, 'destroy'])->name('union.news.destroy');
    */
});
