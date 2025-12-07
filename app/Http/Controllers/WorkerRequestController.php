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
        // Controlador exclusivo del rol trabajador
        $this->middleware(['auth', 'isUnion']);
    }

    /**
     * Panel del trabajador:
     * Trámites activos, historial y trámites disponibles.
     */
    public function dashboard(): View
    {
        $user = Auth::user();

        $active = ProcedureRequest::with('procedure')
            ->where('user_id', $user->id)
            ->whereNotIn('status', ['completed', 'rejected', 'cancelled'])
            ->orderBy('created_at', 'desc')
            ->get();

        $finished = ProcedureRequest::with('procedure')
            ->where('user_id', $user->id)
            ->whereIn('status', ['completed', 'rejected', 'cancelled'])
            ->orderBy('updated_at', 'desc')
            ->get();

        $available = \App\Models\Procedure::orderBy('name')->get();

        return view('worker.dashboard', [
            'active_requests'   => $active,
            'finished_requests' => $finished,
            'available_procedures' => $available
        ]);
    }

    /**
     * Iniciar un trámite.
     */
    public function start(Request $request, string $procedureId): RedirectResponse
    {
        $user = Auth::id();

        $exists = ProcedureRequest::where('user_id', $user)
            ->where('procedure_id', $procedureId)
            ->whereNotIn('status', ['completed', 'rejected'])
            ->first();

        if ($exists) {
            return back()->with('error', 'Ya existe un trámite activo de este tipo.');
        }

        ProcedureRequest::create([
            'user_id' => $user,
            'procedure_id' => $procedureId,
            'current_step' => 1,
            'status' => 'initiated'
        ]);

        return back()->with('success', 'Trámite iniciado correctamente.');
    }

    /**
     * Ver detalle de la solicitud desde el rol trabajador.
     */
    public function show(string $id): View
    {
        $userId = Auth::id();

        $request = ProcedureRequest::with(['procedure.steps', 'documents'])
            ->where('user_id', $userId)
            ->findOrFail($id);

        return view('worker.requests.show', [
            'request' => $request
        ]);
    }

    /**
     * El trabajador corrige un paso (RF-13).
     * Sube archivos, corrige campos y envía nuevamente.
     */
    public function correctStep(Request $httpRequest, string $id, int $order): RedirectResponse
    {
        $validated = $httpRequest->validate([
            'file' => 'nullable|file|mimes:pdf,jpg,png,docx|max:8192',
            'comment' => 'nullable|string|max:500'
        ]);

        $request = ProcedureRequest::with('procedure.steps')
            ->where('user_id', Auth::id())
            ->findOrFail($id);

        $step = $request->procedure->steps
            ->where('order', $order)
            ->first();

        if (!$step) {
            return back()->with('error', 'Paso no válido.');
        }

        if (!empty($validated['file'])) {
            $path = $validated['file']->store('worker_corrections', 'public');

            $request->documents()->create([
                'step_order' => $order,
                'file_path' => $path
            ]);
        }

        ActivityLog::create([
            'user_id' => Auth::id(),
            'module' => 'Trámites',
            'action' => "Corrección enviada en paso {$order} del trámite {$request->id}",
            'ip_address' => $httpRequest->ip()
        ]);

        // El trámite vuelve al sindicato para revisión
        $request->update([
            'status' => 'pending_union'
        ]);

        return back()->with('success', 'Corrección enviada correctamente.');
    }

    /**
     * Finalizar un trámite desde el trabajador.
     * Solo se usa si el trámite permite autocompletado.
     */
    public function finalize(string $id, string $status): RedirectResponse
    {
        $valid = ['completed', 'cancelled'];

        if (!in_array($status, $valid)) {
            abort(400, 'Estado no permitido.');
        }

        $request = ProcedureRequest::where('user_id', Auth::id())
            ->findOrFail($id);

        $request->update(['status' => $status]);

        return redirect()->route('worker.dashboard')
            ->with('success', 'Trámite finalizado correctamente.');
    }
}
