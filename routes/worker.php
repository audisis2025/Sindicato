<?php

/*
* ===========================================================
* Nombre del archivo        : worker.php
* Descripción               : Rutas del módulo Trabajador (Worker).
* Fecha de creación         : 14/11/2025
* Elaboró                   : Iker Piza
* Versión                   : 1.3 (Corrección de rutas duplicadas y vista de trámites)
* ===========================================================
*/

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WorkerProcedureController;
use App\Http\Controllers\WorkerNotificationController;
use App\Http\Controllers\WorkerNewsController;

Route::prefix('worker')->middleware(['auth', 'isWorker'])->group(function () {

    /* -------------------------------------------------------
       DASHBOARD
    ------------------------------------------------------- */
    Route::get('/', [WorkerProcedureController::class, 'index'])
        ->name('worker.index');

    /* -------------------------------------------------------
       NOTICIAS Y CONVOCATORIAS
    ------------------------------------------------------- */
    Route::get('/news', [WorkerNewsController::class, 'index'])
        ->name('worker.news.index');

    Route::get('/news/{id}', [WorkerNewsController::class, 'show'])
        ->name('worker.news.show');

    /* -------------------------------------------------------
       NOTIFICACIONES
    ------------------------------------------------------- */
    Route::get('/notifications', [WorkerNotificationController::class, 'index'])
        ->name('worker.notifications.index');

    Route::patch('/notifications/{id}/read', [WorkerNotificationController::class, 'markAsRead'])
        ->name('worker.notifications.read');

    /* -------------------------------------------------------
       TRÁMITES DEL TRABAJADOR
    ------------------------------------------------------- */
    Route::post('/procedures/start/{id}', [WorkerProcedureController::class, 'start'])
        ->name('worker.procedures.start');

    Route::delete('/procedures/{id}/cancel', [WorkerProcedureController::class, 'cancel'])
        ->name('worker.procedures.cancel');

    Route::get('/procedures/{id}', [WorkerProcedureController::class, 'show'])
        ->name('worker.procedures.show');

    Route::post(
        '/procedures/{solicitudId}/steps/{pasoId}/complete',
        [WorkerProcedureController::class, 'completeStep']
    )->name('worker.procedures.complete-step');

    Route::post(
        '/procedures/{solicitudId}/steps/{pasoId}/upload',
        [WorkerProcedureController::class, 'upload']
    )->name('worker.procedures.upload');
    Route::post(
        '/procedures/{requestId}/steps/{stepId}/send',
        [WorkerProcedureController::class, 'sendStep']
    )
        ->name('worker.procedures.send-step');


    /* -------------------------------------------------------
       SOLICITUD DETALLADA (usa show(), NO showDetail())
    ------------------------------------------------------- */
    Route::get('/requests/{id}', [WorkerProcedureController::class, 'show'])
        ->name('worker.requests.show');

    /* -------------------------------------------------------
       CATÁLOGO
    ------------------------------------------------------- */
    Route::get('/catalog', [WorkerProcedureController::class, 'catalog'])
        ->name('worker.catalog.index');

    Route::get('/catalog/{id}', [WorkerProcedureController::class, 'catalogDetail'])
        ->name('worker.catalog.detail');

    /* -------------------------------------------------------
       SOPORTE
    ------------------------------------------------------- */
    Route::view('/support', 'worker.support')
        ->name('worker.support');
});
