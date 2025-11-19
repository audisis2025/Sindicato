<?php

/*
* ===========================================================
* Nombre de la clase: WorkerProcedureController.php
* Descripción de la clase: Controla el panel del trabajador (trámites activos,
* finalizados, disponibles) y sus interacciones.
* Fecha de creación: 07/11/2025
* Elaboró: Iker Piza
* Fecha de liberación: 10/11/2025
* Autorizó: Líder Técnico
* Versión: 2.1
*
* Fecha de mantenimiento: 10/11/2025
* Folio de mantenimiento: [Tu Folio]
* Tipo de mantenimiento: Perfectivo
* Descripción del mantenimiento: Refactorizado al 100% para usar la BD en inglés...
* Responsable: [Tu Nombre]
* Revisor: [Tu Revisor]
*
* Fecha de mantenimiento: 12/11/2025
* Folio de mantenimiento: [Tu Folio 2]
* Tipo de mantenimiento: Perfectivo (Traducción Enums)
* Descripción del mantenimiento: Se traducen los valores de los enums (status)
* a inglés (pending, completed, published, read, etc.).
* Responsable: [Tu Nombre]
* Revisor: Gemini
* ===========================================================
*/

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
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
    /**
     * Aplica el middleware de autenticación.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * 🏠  Muestra el panel principal del trabajador.
     *
     * @return \Illuminate\View\View
     */
    public function index(): View
    {
        $userId = Auth::id();

        // Corregido: status en inglés
        $activeRequests = ProcedureRequest::with('procedure')
            ->where('user_id', $userId)
            ->whereIn('status', ['pending', 'in_progress']) // Antes 'pendiente', 'en_proceso'
            ->latest()
            ->get();

        // Corregido: status en inglés
        $finishedRequests = ProcedureRequest::with('procedure')
            ->where('user_id', $userId)
            ->whereIn('status', ['completed', 'rejected']) // Antes 'completado', 'rechazado' (y 'cancelado')
            ->latest()
            ->get();

        $availableProcedures = Procedure::whereNotIn('id', function ($query) use ($userId) {
            $query->select('procedure_id')
                ->from('procedure_requests')
                ->where('user_id', $userId)
                // Corregido: status en inglés
                ->whereIn('status', ['pending', 'in_progress']); // Antes 'pendiente', 'en_proceso'
        })
            ->orderBy('name')
            ->get();

        return view('worker.index', [
            'active_requests' => $activeRequests,
            'finished_requests' => $finishedRequests,
            'available_procedures' => $availableProcedures,
        ]);
    }

    /**
     * 🚀  Inicia un nuevo trámite para el trabajador.
     *
     * @param string $id (ID del Procedure)
     * @return \Illuminate\Http\RedirectResponse
     */
    public function start(string $id): RedirectResponse
    {
        $userId = Auth::id();
        $procedure = Procedure::findOrFail($id);

        // Corregido: status en inglés
        $exists = ProcedureRequest::where('user_id', $userId)
            ->where('procedure_id', $id)
            ->whereIn('status', ['pending', 'in_progress']) // Antes 'pendiente', 'en_proceso'
            ->exists();

        if ($exists) {
            return redirect()
                ->route('worker.index')
                ->with('error', ' ⚠️  Ya tienes un trámite activo de este tipo.');
        }

        // Corregido: status en inglés
        $newRequest = ProcedureRequest::create([
            'user_id' => $userId,
            'procedure_id' => $procedure->id,
            'current_step' => 1,
            'status' => 'pending', // Antes 'pendiente'
        ]);

        return redirect()
            ->route('worker.procedures.show', $newRequest->id)
            ->with('success', ' 🚀  Trámite iniciado correctamente.');
    }

    /**
     * 👣  Muestra los pasos del trámite actual del trabajador.
     *
     * @param string $id (ID de la ProcedureRequest)
     * @return \Illuminate\View\View
     */
    public function show(string $id): View
    {
        $procedureRequest = ProcedureRequest::with(['procedure.steps'])
            ->where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        return view('worker.procedure-show', [
            'procedure_request' => $procedureRequest
        ]);
    }

    /**
     * 📤  Sube un archivo del trabajador para una solicitud.
     * (Este método estaba bien)
     * @param \Illuminate\Http\Request $request
     * @param string $requestId (ID de la ProcedureRequest)
     * @return \Illuminate\Http\RedirectResponse
     */
    public function upload(Request $httpRequest, string $requestId): RedirectResponse
    {
        $validatedData = $httpRequest->validate([
            'file' => 'required|file|mimes:pdf,doc,docx,jpg,png|max:10240',
        ]);

        $procedureRequest = ProcedureRequest::where('id', $requestId)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $file = $validatedData['file'];
        $path = $file->store('worker_uploads', 'public');

        ProcedureDocument::create([
            'procedure_request_id' => $procedureRequest->id,
            'file_name' => $file->getClientOriginalName(),
            'file_path' => $path,
            'type' => $file->getClientOriginalExtension(),
            'year' => now()->year
        ]);

        return back()->with('success', ' 📤  Archivo subido correctamente.');
    }

    /**
     * ✅  Avanza al siguiente paso (marcado por el trabajador).
     *
     * @param string $requestId (ID de la ProcedureRequest)
     * @param string $stepId (ID del ProcedureStep)
     * @return \Illuminate\Http\RedirectResponse
     */
    public function completeStep(string $requestId, string $stepId): RedirectResponse
    {
        $request = ProcedureRequest::with('procedure')->findOrFail($requestId);
        $step = ProcedureStep::findOrFail($stepId);

        if ($request->current_step != $step->order) {
            return back()->with('error', ' ⚠️  Debes completar los pasos en orden.');
        }

        $request->current_step += 1;

        // Corregido: status en inglés
        if ($request->current_step > $request->procedure->steps_count) {
            $request->status = 'completed'; // Antes 'completado'
        }

        $request->save();
        return back()->with('success', ' ✅  Paso completado correctamente.');
    }

    /**
     * ❌  Cancela un trámite activo del trabajador.
     *
     * @param string $id (ID de la ProcedureRequest)
     * @return \Illuminate\Http\RedirectResponse
     */
    public function cancel(string $id): RedirectResponse
    {
        // Corregido: status en inglés
        $procedureRequest = ProcedureRequest::where('id', $id)
            ->where('user_id', Auth::id())
            ->whereIn('status', ['pending', 'in_progress']) // Antes 'pendiente', 'en_proceso'
            ->firstOrFail();

        // Corregido: 'cancelado' -> 'rejected' (según la migración)
        $procedureRequest->update(['status' => 'rejected']);

        return redirect()
            ->route('worker.index')
            ->with('success', ' ❌  Trámite cancelado (rechazado) correctamente.');
    }

    /**
     * 📢  Muestra convocatorias y noticias del sindicato.
     *
     * @return \Illuminate\View\View
     */
    public function showNews(Request $request): View
    {
        $query = News::where('status', 'published'); // Solo noticias publicadas

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


    /**
     * 🔔  Muestra todas las notificaciones del trabajador.
     *
     * @return \Illuminate\View\View
     */
    public function showNotifications(Request $request): View
    {
        $query = Notification::where('user_id', Auth::id());

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        if ($request->boolean('unread')) {
            $query->where('status', 'unread');
        }

        $notificationsList = $query->orderBy('created_at', 'desc')->get();

        return view('worker.notifications', [
            'notifications_list' => $notificationsList
        ]);
    }
    public function markAllAsRead(): RedirectResponse
    {
        Notification::where('user_id', Auth::id())
            ->where('status', 'unread')
            ->update(['status' => 'read']);

        return back()->with('success', 'Todas las notificaciones fueron marcadas como leídas.');
    }



    /**
     * ✅  Marcar una notificación como leída.
     *
     * @param string $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function markAsRead(string $id): RedirectResponse
    {
        $notification = Notification::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        // Corregido: status en inglés (aunque este ya estaba bien)
        $notification->update(['status' => 'read']);

        return back()->with('success', ' ✅  Notificación marcada como leída.');
    }

    /**
     * 📚 Catálogo de trámites (vista principal con filtros y buscador)
     */
    public function catalog(Request $request): View
    {
        $query = Procedure::query();

        if ($request->filled('keyword')) {
            $query->where('name', 'like', '%' . $request->keyword . '%')
                ->orWhere('description', 'like', '%' . $request->keyword . '%');
        }

        if ($request->filled('steps_min')) {
            $query->where('steps_count', '>=', $request->steps_min);
        }

        $procedures = $query->orderBy('name')->get();

        return view('worker.catalog.index', compact('procedures'));
    }

    /**
     * 📄 Detalle de trámite antes de iniciar
     */
    public function catalogDetail($id): View
    {
        $procedure = Procedure::with('steps')->findOrFail($id);

        return view('worker.catalog.detail', compact('procedure'));
    }
}
