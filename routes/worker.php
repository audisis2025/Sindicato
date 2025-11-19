<?php

/*
* ===========================================================
* Nombre del archivo        : worker.php
* Descripción de la clase : Define las rutas del módulo de Trabajador (Worker).
* Fecha de creación       : 14/11/2025
* Elaboró                 : Iker Piza
* Fecha de liberación     : 14/11/2025
* Autorizó                : 
* Versión                 : 1.1
*
* Fecha de mantenimiento    : 14/11/2025
* Folio de mantenimiento  : 001
* Tipo de mantenimiento   : Correctivo
* Descripción del mantenimiento: Se corrige el namespace de WorkerProcedureController
* y se aplica la cabecera del manual (PRO-Laravel V3.3).
* Responsable             : 
* Revisor                 : Iker Piza
* ===========================================================
*/

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Worker\WorkerProcedureController;

Route::prefix('worker')->middleware(['auth'])->group(function () {

    Route::get('/', [WorkerProcedureController::class, 'index'])->name('worker.index');

    Route::post('/procedures/start/{id}', [WorkerProcedureController::class, 'start'])
        ->name('worker.procedures.start');

    Route::delete('/procedures/{id}/cancel', [WorkerProcedureController::class, 'cancel'])
        ->name('worker.procedures.cancel');
    Route::get('/procedures/{id}', [WorkerProcedureController::class, 'show'])
        ->name('worker.procedures.show');

    Route::post(
        '/procedures/{solicitudId}/steps/{pasoId}/complete',
        [WorkerProcedureController::class, 'completeStep']
    )
        ->name('worker.procedures.complete-step');


    Route::get('/requests/{id}', [WorkerProcedureController::class, 'showDetail'])
        ->name('worker.requests.show');


    Route::post(
        '/procedures/{solicitudId}/steps/{pasoId}/upload',
        [WorkerProcedureController::class, 'upload']
    )
        ->name('worker.procedures.upload');


    Route::get('/news', [WorkerProcedureController::class, 'showNews'])
        ->name('worker.news.index');


    Route::get('/notifications', [WorkerProcedureController::class, 'showNotifications'])
        ->name('worker.notifications.index');
    Route::post('/notifications/read/{id}', [WorkerProcedureController::class, 'markAsRead'])
        ->name('worker.notifications.read');

    Route::post(
        '/notifications/read-all',
        [WorkerProcedureController::class, 'markAllAsRead']
    )->name('worker.notifications.readAll');

    Route::get('/catalog', [WorkerProcedureController::class, 'catalog'])
        ->name('worker.catalog.index');

    Route::get('/catalog/{id}', [WorkerProcedureController::class, 'catalogDetail'])
        ->name('worker.catalog.detail');


    Route::view('/support', 'worker.support')->name('worker.support');
});
