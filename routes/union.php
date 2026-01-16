<?php
/*
* Nombre del archivo          : union.php
* Descripción del archivo     : Archivo de definición de rutas para el módulo del sindicato.
*                               Gestiona las funcionalidades exclusivas del rol sindicato,
*                               incluyendo la administración de trabajadores (miembros),
*                               creación y gestión de trámites, revisión y seguimiento de
*                               solicitudes, aprobación o corrección de pasos, publicación
*                               de noticias y generación de reportes estadísticos.
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
use App\Http\Controllers\MemberController;
use App\Http\Controllers\ProcedureController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\UnionRequestController;
use App\Http\Controllers\NewsController;

Route::prefix('union')->middleware(['auth', 'isUnion'])->group(function () 
{


   Route::get('/members', [MemberController::class, 'index'])->name('union.members.index');
   Route::get('/members/create', [MemberController::class, 'create'])->name('union.members.create');
   Route::post('/members', [MemberController::class, 'store'])->name('union.members.store');
   Route::get('/members/{id}/edit', [MemberController::class, 'edit'])->name('union.members.edit');
   Route::put('/members/{id}', [MemberController::class, 'update'])->name('union.members.update');
   Route::delete('/members/{id}', [MemberController::class, 'destroy'])->name('union.members.destroy');
   Route::patch('/members/{id}/notify-error', [MemberController::class, 'notifyError'])->name('union.members.notify-error');

   Route::get('/procedures', [ProcedureController::class, 'index'])->name('union.procedures.index');
   Route::get('/procedures/create', [ProcedureController::class, 'create'])->name('union.procedures.create');
   Route::post('/procedures', [ProcedureController::class, 'store'])->name('union.procedures.store');
   Route::get('/procedures/{id}/edit', [ProcedureController::class, 'edit'])->name('union.procedures.edit');
   Route::get('/procedures/{id}', [ProcedureController::class, 'show'])->name('union.procedures.show');
   Route::put('/procedures/{id}', [ProcedureController::class, 'update'])->name('union.procedures.update');
   Route::delete('/procedures/{id}', [ProcedureController::class, 'destroy'])->name('union.procedures.destroy');
   Route::patch('/union/procedures/{id}/toggle-status', [ProcedureController::class, 'toggleStatus'])
      ->name('union.procedures.toggle');

   Route::get('/requests',                [UnionRequestController::class, 'index'])->name('union.requests.index');
   Route::get('/requests/{id}',           [UnionRequestController::class, 'show'])->name('union.requests.show');

   Route::post(
      '/requests/{id}/steps/{order}/approve',
      [UnionRequestController::class, 'approveStep']
   )
      ->name('union.requests.approve-step');

   Route::post(
      '/requests/{id}/steps/{order}/notify-error',
      [UnionRequestController::class, 'notifyError']
   )
      ->name('union.requests.notify-error');

   Route::post(
      '/requests/{id}/finalize/{status}',
      [UnionRequestController::class, 'finalize']
   )
      ->name('union.requests.finalize');

   Route::get('/news', [NewsController::class, 'index'])->name('union.news.index');
   Route::get('/news/create', [NewsController::class, 'create'])->name('union.news.create');
   Route::post('/news', [NewsController::class, 'store'])->name('union.news.store');
   Route::get('/news/{news}/edit', [NewsController::class, 'edit'])->name('union.news.edit');
   Route::put('/news/{news}', [NewsController::class, 'update'])->name('union.news.update');
   Route::delete('/news/{news}', [NewsController::class, 'destroy'])->name('union.news.destroy');


   Route::get('/reports', [ReportController::class, 'index'])->name('union.reports.index');
   Route::get('/reports/data', [ReportController::class, 'getChartData'])->name('union.reports.data');

   Route::get('/reports/export/pdf',   [ReportController::class, 'exportPdf'])->name('union.reports.export-pdf');
   Route::get('/reports/export/excel', [ReportController::class, 'exportExcel'])->name('union.reports.export-excel');
   Route::get('/reports/export/word',  [ReportController::class, 'exportWord'])->name('union.reports.export-word');
});
