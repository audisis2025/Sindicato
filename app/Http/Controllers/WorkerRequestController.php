<?php
/*
* ===========================================================
* Nombre de la clase: WorkerRequestController.php
* Descripción de la clase: Controlador para la gestión y revisión de solicitudes
* de trámites (RF13, RF14).
* Fecha de creación: 06/11/2025
* Elaboró: Iker Piza
* Fecha de liberación: 10/11/2025
* Autorizó: Líder Técnico
* Versión: 2.0
*
* Fecha de mantenimiento: 10/11/2025
* Folio de mantenimiento: [Tu Folio]
* Tipo de mantenimiento: Perfectivo
* Descripción del mantenimiento: Refactorizado para usar la BD en inglés (ProcedureRequest),
* se movieron métodos desde ProcedureController (notify, approve, finalize)
* y se alineó con el Manual PRO-Laravel V3.2.
* Responsable: [Tu Nombre]
* Revisor: [Tu Revisor]
* ===========================================================
*/

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\ProcedureRequest; // Modelo correcto
use App\Models\Notification; // Para notificar errores
use App\Models\ActivityLog; // Para bitácora interna
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class WorkerRequestController extends Controller // [cite: 887-890]
{
    /**
     * Aplica el middleware de Sindicato a todos los métodos.
     */
    public function __construct()
    {
        $this->middleware('auth');
        // Asumiendo que tienes un middleware 'isUnion' similar a 'isAdmin'
        $this->middleware('isUnion');
    }

    /**
     * Listado de solicitudes realizadas por trabajadores (RF13–RF14).
     *
     * @return \Illuminate\View\View
     */
    public function index(): View // [cite: 200, 217-218]
    {
        // Carga relaciones en inglés
        $requests = ProcedureRequest::with(['user', 'procedure']) // [cite: 236-239]
            ->latest('created_at')
            ->get();

        return view('union.requests.index', compact('requests')); // [cite: 288-291]
    }

    /**
     * Muestra el detalle de una solicitud específica para revisión.
     *
     * @param string $id
     * @return \Illuminate\View\View
     */
    public function show(string $id): View
    {
        $request = ProcedureRequest::with(['user', 'procedure.steps', 'documents'])
            ->findOrFail($id);

        return view('union.requests.show', compact('request'));
    }

    /**
     * RF13 – Notifica un error al trabajador sobre su solicitud.
     *
     * @param \Illuminate\Http\Request $httpRequest
     * @param string $id (ID de la ProcedureRequest)
     * @return \Illuminate\Http\RedirectResponse
     */
    public function notifyError(Request $httpRequest, string $id): RedirectResponse
    {
        $validated = $httpRequest->validate([
            'error_message' => 'required|string|max:500', // 'mensaje_error'
        ]);

        $request = ProcedureRequest::findOrFail($id); // [cite: 236-239]
        $sindicatoUser = Auth::user();

        // 1. Crea la notificación para el trabajador
        Notification::create([
            'user_id' => $request->user_id, // ID del trabajador
            'title' => 'Error en tu trámite: ' . $request->procedure->name, // 'title'
            'message' => $validated['error_message'], // 'message'
            'type' => 'error', // 'type'
            'status' => 'unread', // 'status'
        ]);

        // 2. Crea el registro en la bitácora para el Sindicato
        ActivityLog::create([
            'user_id' => $sindicatoUser->id,
            'action' => "Notificó error en solicitud #{$request->id}: " . $validated['error_message'],
            'module' => "Solicitudes",
            'ip_address' => $httpRequest->ip()
        ]);

        return back()->with('success', '⚠️ Error notificado correctamente al trabajador.'); // [cite: 1291-1294]
    }

    /**
     * RF14 – Aprueba y avanza el paso actual del trabajador.
     *
     * @param string $id (ID de la ProcedureRequest)
     * @return \Illuminate\Http\RedirectResponse
     */
    public function approveStep(string $id): RedirectResponse
    {
        $request = ProcedureRequest::with('procedure')->findOrFail($id);

        if ($request->status == 'completado' || $request->status == 'rechazado') {
            return back()->with('error', 'Este trámite ya está finalizado.');
        }

        $request->current_step += 1; // 'paso_actual'

        // Si ya terminó todos los pasos
        if ($request->current_step > $request->procedure->steps_count) { // 'numero_pasos'
            $request->status = 'completado'; // 'estado'
        }

        $request->save();

        return back()->with('success', '✅ Paso aprobado y avanzado correctamente.');
    }

    /**
     * RF14 – Finaliza un trámite como Completado o Rechazado.
     *
     * @param string $id (ID de la ProcedureRequest)
     * @param string $status
     * @return \Illuminate\Http\RedirectResponse
     */
    public function finalize(string $id, string $status): RedirectResponse
    {
        $validStatuses = ['completado', 'rechazado']; // Corregido
        if (!in_array($status, $validStatuses)) {
            abort(400, 'Estado inválido');
        }

        $request = ProcedureRequest::findOrFail($id);
        $request->update(['status' => $status]); // 'estado'

        return redirect()->route('union.requests.index') // Asumiendo esta ruta
            ->with('success', "🏁 Trámite marcado como {$status} correctamente.");
    }
}