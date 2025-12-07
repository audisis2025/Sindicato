<?php

namespace App\Http\Controllers;

use App\Models\ProcedureRequest;
use App\Models\ProcedureStep;
use App\Models\SystemNotification;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class UnionRequestController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'isUnion']);
    }

    /* ============================================================
       LISTADO DE SOLICITUDES
       ============================================================ */
    public function index(): View
    {
        $requests = ProcedureRequest::with(['user', 'procedure'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('union.requests.index', compact('requests'));
    }

    /* ============================================================
       VER DETALLE DEL TRÁMITE
       ============================================================ */
    public function show(string $id): View
    {
        $requestData = ProcedureRequest::with([
            'user',
            'procedure',
            'procedure.steps',
            'documents'
        ])->findOrFail($id);

        return view('union.requests.show', compact('requestData'));
    }


    /* ============================================================
   APROBAR O RECHAZAR UN PASO (RF-14 COMPLETO + FLUJO ALTERNO)
   ============================================================ */
    public function approveStep(Request $httpRequest, string $requestId, int $order): RedirectResponse
    {
        $validated = $httpRequest->validate([
            'result'   => 'required|in:approve,reject',
            'comments' => 'nullable|string|max:1000'
        ]);

        $result = $validated['result'];

        $request = ProcedureRequest::with(['procedure.steps', 'documents'])
            ->findOrFail($requestId);

        $procedure = $request->procedure;
        $currentStep = $procedure->steps->firstWhere('order', $order);

        if (!$currentStep) {
            return back()->with('error', 'El paso solicitado no existe.');
        }

        // VALIDAR QUE SEA EL PASO ACTUAL
        if ($request->current_step !== $order) {
            return back()->with('error', 'Solo puedes revisar el paso actual.');
        }

        // VALIDAR ARCHIVO SI ES REQUERIDO
        if ($currentStep->requires_file) {
            $hasFile = $request->documents()
                ->where('procedure_step_id', $currentStep->id)
                ->exists();

            if (!$hasFile) {
                return back()->with('error', 'Este paso requiere un archivo del trabajador.');
            }
        }

        /**
         * =======================================================
         *             SI EL PASO ES APROBADO
         * =======================================================
         */
        if ($result === 'approve') {

            // AVANZA AL SIGUIENTE PASO NORMAL
            $nextOrder = $order + 1;
            $maxOrder  = $procedure->steps_count;

            if ($nextOrder > $maxOrder) {
                // COMPLETADO
                $request->update([
                    'status'       => ProcedureRequest::STATUS_COMPLETED,
                    'current_step' => $order,
                ]);

                SystemNotification::create([
                    'user_id' => $request->user_id,
                    'title'   => 'Trámite completado',
                    'message' => "Tu trámite '{$procedure->name}' ha sido completado.",
                    'type'    => 'success',
                    'status'  => 'unread',
                ]);

                return back()->with('success', '✔ El trámite fue completado correctamente.');
            }

            // Sigue flujo normal
            $request->update([
                'current_step' => $nextOrder,
                'status'       => ProcedureRequest::STATUS_IN_PROGRESS,
            ]);

            SystemNotification::create([
                'user_id' => $request->user_id,
                'title'   => 'Paso aprobado',
                'message' => "El paso $order del trámite '{$procedure->name}' fue aprobado.",
                'type'    => 'success',
                'status'  => 'unread',
            ]);

            return back()->with('success', '✔ Paso aprobado. Avanzando al siguiente.');
        }

        /**
         * =======================================================
         *             SI EL PASO ES RECHAZADO
         * =======================================================
         */

        // SI TIENE FLUJO ALTERNO ASIGNADO
        if (!empty($currentStep->next_step_if_fail)) {

            $alternateOrder = (int) $currentStep->next_step_if_fail;

            // Validación adicional
            if ($alternateOrder >= 1 && $alternateOrder <= $procedure->steps_count) {

                $request->update([
                    'current_step' => $alternateOrder,
                    'status'       => ProcedureRequest::STATUS_PENDING_WORKER,
                ]);

                SystemNotification::create([
                    'user_id' => $request->user_id,
                    'title'   => 'Redirección por error',
                    'message' => "Fuiste redirigido al paso alterno {$alternateOrder} del trámite '{$procedure->name}'.",
                    'type'    => 'warning',
                    'status'  => 'unread',
                ]);

                return back()->with('success', "🔁 Paso rechazado. Redirigido al flujo alterno {$alternateOrder}.");
            }
        }

        // SIN FLUJO ALTERNO → TRÁMITE RECHAZADO
        $request->update([
            'status'       => ProcedureRequest::STATUS_REJECTED,
            'current_step' => $order,
        ]);

        SystemNotification::create([
            'user_id' => $request->user_id,
            'title'   => 'Trámite rechazado',
            'message' => "Tu trámite '{$procedure->name}' fue rechazado.",
            'type'    => 'error',
            'status'  => 'unread',
        ]);

        return back()->with('error', '❌ El trámite fue rechazado por errores en este paso.');
    }



    /* ============================================================
       NOTIFICAR ERROR AL TRABAJADOR (RF-13)
       ============================================================ */
    public function notifyError(Request $httpRequest, string $requestId, int $order): RedirectResponse
    {
        $validated = $httpRequest->validate([
            'error_message' => 'required|string|max:500'
        ]);

        $request = ProcedureRequest::findOrFail($requestId);

        /* ---------------------------------------------------------
            NOTIFICACIÓN
        --------------------------------------------------------- */
        SystemNotification::create([
            'user_id' => $request->user_id,
            'title'   => 'Corrección requerida',
            'message' => $validated['error_message'],
            'type'    => 'error',
            'status'  => 'unread',
        ]);

        /* ---------------------------------------------------------
            ESTADO: REGRESA AL TRABAJADOR
        --------------------------------------------------------- */
        $request->update([
            'status' => 'pending_worker'
        ]);

        /* ---------------------------------------------------------
            BITÁCORA
        --------------------------------------------------------- */
        ActivityLog::create([
            'user_id'    => Auth::id(),
            'module'     => 'Solicitudes',
            'action'     => "Notificó error en el paso {$order} de la solicitud {$request->id}",
            'ip_address' => $httpRequest->ip()
        ]);

        return back()->with('success', 'El error fue notificado correctamente.');
    }


    /* ============================================================
       FINALIZAR TRÁMITE MANUALMENTE
       ============================================================ */
    public function finalize(string $requestId, string $status): RedirectResponse
    {
        if (!in_array($status, ['completed', 'rejected'])) {
            abort(400, 'Estado no permitido.');
        }

        $request = ProcedureRequest::findOrFail($requestId);

        $request->update([
            'status' => $status
        ]);

        ActivityLog::create([
            'user_id'    => Auth::id(),
            'module'     => 'Solicitudes',
            'action'     => "Finalizó la solicitud {$request->id} con estado {$status}",
            'ip_address' => request()->ip()
        ]);

        return redirect()->route('union.requests.index')
            ->with('success', 'El trámite fue actualizado correctamente.');
    }
}
