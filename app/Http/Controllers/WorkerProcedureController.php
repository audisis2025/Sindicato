<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Procedure;
use App\Models\ProcedureRequest;
use App\Models\ProcedureStep;
use App\Models\ProcedureDocument;
use App\Models\News;
use App\Models\Notification;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class WorkerProcedureController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /* ============================================================
       PANEL PRINCIPAL DEL TRABAJADOR
       ============================================================ */
    public function index(): View
    {
        $userId = Auth::id();

        // Trámites activos (solo estos estados)
        $active = ['initiated', 'in_progress', 'pending_union', 'pending_worker'];

        $activeRequests = ProcedureRequest::with('procedure')
            ->where('user_id', $userId)
            ->whereIn('status', $active)
            ->latest()
            ->get();

        // Trámites finalizados
        $finished = ['completed', 'rejected', 'cancelled'];

        $finishedRequests = ProcedureRequest::with('procedure')
            ->where('user_id', $userId)
            ->whereIn('status', $finished)
            ->latest()
            ->get();

        // Trámites disponibles (solo si NO hay activo del mismo)
        $availableProcedures = Procedure::whereNotIn('id', function ($query) use ($userId, $active) {
            $query->select('procedure_id')
                ->from('procedure_requests')
                ->where('user_id', $userId)
                ->whereIn('status', $active);
        })
            ->orderBy('name')
            ->get();

        return view('worker.index', [
            'active_requests' => $activeRequests,
            'finished_requests' => $finishedRequests,
            'available_procedures' => $availableProcedures,
        ]);
    }

    /* ============================================================
       INICIAR TRÁMITE
       ============================================================ */
    public function start(string $id): RedirectResponse
    {
        $userId = Auth::id();
        $procedure = Procedure::findOrFail($id);

        $active = ['initiated', 'in_progress', 'pending_union', 'pending_worker'];

        $exists = ProcedureRequest::where('user_id', $userId)
            ->where('procedure_id', $id)
            ->whereIn('status', $active)
            ->exists();

        if ($exists) {
            return back()->with('error', '⚠️ Ya tienes un trámite activo de este tipo.');
        }

        $newRequest = ProcedureRequest::create([
            'user_id' => $userId,
            'procedure_id' => $procedure->id,
            'current_step' => 1,
            'status' => 'initiated', // INICIADO
        ]);

        return redirect()
            ->route('worker.procedures.show', $newRequest->id)
            ->with('success', '🚀 Trámite iniciado correctamente.');
    }

    /* ============================================================
       MOSTRAR TRÁMITE EN PROCESO
       ============================================================ */
    public function show(string $id): View
    {
        $request = ProcedureRequest::with(['procedure.steps', 'documents'])
            ->where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        return view('worker.procedure_show', [
            'procedure_request' => $request
        ]);
    }

    /* ============================================================
       SUBIR ARCHIVO (TRABAJADOR → SINDICATO)
       ============================================================ */
    public function upload(Request $req, string $requestId): RedirectResponse
    {
        $validated = $req->validate([
            'file' => 'required|file|mimes:pdf,doc,docx,jpg,png|max:10240',
            'step_id' => 'required|integer|exists:procedure_steps,id',
        ]);

        $procedureRequest = ProcedureRequest::where('id', $requestId)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $file = $validated['file'];
        $path = $file->store('worker_uploads', 'public');

        ProcedureDocument::create([
            'procedure_request_id' => $procedureRequest->id,
            'procedure_step_id' => $validated['step_id'],
            'file_name' => $file->getClientOriginalName(),
            'file_path' => $path,
            'type' => $file->getClientOriginalExtension(),
            'year' => now()->year
        ]);

        // Estado correcto según RF-04
        $procedureRequest->update([
            'status' => 'pending_union', // El sindicato debe revisarlo
        ]);

        return back()->with('success', '📤 Archivo enviado al sindicato.');
    }

    /* ============================================================
       COMPLETAR PASO (VERIFICAR ARCHIVO SI ES REQUERIDO)
       ============================================================ */
    public function completeStep(string $requestId, string $stepId): RedirectResponse
    {
        $request = ProcedureRequest::with(['procedure.steps', 'documents'])->findOrFail($requestId);
        $step = ProcedureStep::findOrFail($stepId);

        if ($request->current_step != $step->order) {
            return back()->with('error', 'Debes completar los pasos en orden.');
        }

        if ($step->requires_file) {
            $hasFile = $request->documents()
                ->where('procedure_step_id', $step->id)
                ->exists();

            if (!$hasFile) {
                return back()->with('error', 'Debes subir el archivo requerido.');
            }
        }

        $request->current_step += 1;

        if ($request->current_step > $request->procedure->steps_count) {
            $request->status = 'completed';
        } else {
            $request->status = 'in_progress';
        }

        $request->save();

        return back()->with('success', '✔ Paso completado.');
    }

    /* ============================================================
       CANCELAR TRÁMITE
       ============================================================ */
    public function cancel(string $id): RedirectResponse
    {
        $active = ['initiated', 'in_progress', 'pending_union', 'pending_worker'];

        $req = ProcedureRequest::where('id', $id)
            ->where('user_id', Auth::id())
            ->whereIn('status', $active)
            ->firstOrFail();

        $req->update(['status' => 'cancelled']);

        return redirect()->route('worker.index')
            ->with('success', '❌ Trámite cancelado.');
    }

    /* ============================================================
       NOTICIAS DEL SINDICATO
       ============================================================ */
    public function showNews(Request $request): View
    {
        $query = News::where('status', 'published');

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('keyword')) {
            $keyword = $request->keyword;
            $query->where(function ($q) use ($keyword) {
                $q->where('title', 'like', "%$keyword%")
                    ->orWhere('content', 'like', "%$keyword%");
            });
        }

        $newsList = $query->latest()->get();

        return view('worker.news', [
            'news_list' => $newsList
        ]);
    }

    /* ============================================================
       NOTIFICACIONES
       ============================================================ */
    public function showNotifications(Request $request): View
    {
        $query = Notification::where('user_id', Auth::id());

        if ($request->boolean('unread')) {
            $query->where('status', 'unread');
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        return view('worker.notifications', [
            'notifications_list' => $query->orderBy('created_at', 'desc')->get()
        ]);
    }

    public function markAllAsRead(): RedirectResponse
    {
        Notification::where('user_id', Auth::id())
            ->where('status', 'unread')
            ->update(['status' => 'read']);

        return back()->with('success', 'Todas las notificaciones fueron marcadas como leídas.');
    }

    public function markAsRead(string $id): RedirectResponse
    {
        $notification = Notification::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $notification->update(['status' => 'read']);

        return back()->with('success', 'Notificación marcada como leída.');
    }
    public function catalog(Request $request)
    {
        $keyword   = $request->input('keyword');
        $steps_min = $request->input('steps_min');

        $procedures = \App\Models\Procedure::withCount('steps')
            ->when($keyword, function ($q) use ($keyword) {
                $q->where('name', 'LIKE', "%$keyword%")
                    ->orWhere('description', 'LIKE', "%$keyword%");
            })
            ->when($steps_min, function ($q) use ($steps_min) {
                $q->having('steps_count', '>=', $steps_min);
            })
            ->get();

        return view('worker.catalog.index', compact('procedures'));
    }

    public function catalogDetail($id)
    {
        $procedure = \App\Models\Procedure::with('steps')->findOrFail($id);

        return view('worker.catalog.detail', compact('procedure'));
    }
}
