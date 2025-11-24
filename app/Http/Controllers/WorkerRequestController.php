<?php

namespace App\Http\Controllers;

use App\Models\ProcedureRequest;
use App\Models\Notification;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class WorkerRequestController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'isUnion']);
    }

    /**
     * Listado de solicitudes hechas por trabajadores (RF-13 / RF-14)
     */
    public function index(): View
    {
        $requests = ProcedureRequest::with(['user', 'procedure'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('union.requests.index', compact('requests'));
    }

    /**
     * Mostrar detalle para revisión sindical
     */
    public function show(string $id): View
    {
        $request = ProcedureRequest::with(['user', 'procedure.steps', 'documents'])
            ->findOrFail($id);

        return view('union.requests.show', compact('request'));
    }

    /**
     * RF-13  
     * El sindicato notifica un error al trabajador.
     * Cambia el estado → pending_worker
     */
    public function notifyError(Request $httpRequest, string $id): RedirectResponse
    {
        $validated = $httpRequest->validate([
            'error_message' => 'required|string|max:500',
        ]);

        $request = ProcedureRequest::findOrFail($id);

        // Notificación al trabajador
        Notification::create([
            'user_id' => $request->user_id,
            'title' => 'Corrección requerida en tu trámite',
            'message' => $validated['error_message'],
            'type' => 'error',
            'status' => 'unread',
        ]);

        // Registrar bitácora
        ActivityLog::create([
            'user_id' => Auth::id(),
            'module' => 'Solicitudes',
            'action' => "Notificó error en trámite #{$request->id}",
            'ip_address' => $httpRequest->ip()
        ]);

        // Cambio de estado oficial RF-04
        $request->update([
            'status' => 'pending_worker'
        ]);

        return back()->with('success', '⚠️ Se notificó el error al trabajador.');
    }

    /**
     * RF-14  
     * El sindicato aprueba un paso enviado por el trabajador.
     * Cambia estado → in_progress o → completed si terminó
     */
    public function approveStep(string $id): RedirectResponse
    {
        $request = ProcedureRequest::with('procedure')->findOrFail($id);

        if (in_array($request->status, ['completed', 'rejected', 'cancelled'])) {
            return back()->with('error', 'El trámite ya está finalizado.');
        }

        // Avanzar paso
        $request->current_step += 1;

        if ($request->current_step > $request->procedure->steps_count) {
            // Fin del trámite
            $request->status = 'completed';
        } else {
            // Estado continúa en progreso
            $request->status = 'in_progress';
        }

        $request->save();

        return back()->with('success', '✅ Paso aprobado correctamente.');
    }

    /**
     * RF-14  
     * Finalizar trámite como completado o rechazado.
     */
    public function finalize(string $id, string $status): RedirectResponse
    {
        $valid = ['completed', 'rejected'];

        if (!in_array($status, $valid)) {
            abort(400, 'Estado no permitido.');
        }

        $request = ProcedureRequest::findOrFail($id);
        $request->update(['status' => $status]);

        return redirect()->route('union.requests.index')
            ->with('success', "🏁 El trámite fue marcado como {$status}.");
    }
}
