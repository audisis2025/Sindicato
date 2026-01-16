<?php
/*
* Nombre del archivo          : worker.php
* Descripción del archivo     : Archivo de definición de rutas para el módulo del trabajador.
*                               Gestiona las funcionalidades disponibles para el rol trabajador,
*                               incluyendo el inicio y seguimiento de trámites, carga y envío
*                               de documentos, consulta del catálogo de trámites, visualización
*                               de noticias, notificaciones y acceso a soporte.
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
use App\Http\Controllers\WorkerProcedureController;
use App\Http\Controllers\WorkerNotificationController;
use App\Http\Controllers\WorkerNewsController;

Route::prefix('worker')->middleware(['auth', 'isWorker'])->group(function () 
{

    Route::get('/', [WorkerProcedureController::class, 'index'])
        ->name('worker.index');

    Route::get('/news', [WorkerNewsController::class, 'index'])
        ->name('worker.news.index');

    Route::get('/news/{id}', [WorkerNewsController::class, 'show'])
        ->name('worker.news.show');


    Route::get('/notifications', [WorkerNotificationController::class, 'index'])
        ->name('worker.notifications.index');

    Route::patch('/notifications/{id}/read', [WorkerNotificationController::class, 'markAsRead'])
        ->name('worker.notifications.read');


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


    Route::get('/requests/{id}', [WorkerProcedureController::class, 'show'])
        ->name('worker.requests.show');

    Route::get('/catalog', [WorkerProcedureController::class, 'catalog'])
        ->name('worker.catalog.index');

    Route::get('/catalog/{id}', [WorkerProcedureController::class, 'catalogDetail'])
        ->name('worker.catalog.detail');

    Route::view('/support', 'worker.support')
        ->name('worker.support');
});
