<?php
/*
* Nombre de la clase           : UnionRequestController.php
* Descripción de la clase      : Controlador encargado de la gestión de solicitudes de trámites por parte del sindicato: listado, visualización, aprobación/rechazo de pasos, notificación de errores y finalización.
* Fecha de creación            : 29/09/2025
* Elaboró                      : Iker Piza
* Fecha de liberación          : 14/12/2025
* Autorizó                     : Salvador Monroy
* Versión                      : 1.2
* Fecha de mantenimiento       :
* Folio de mantenimiento       :
* Tipo de mantenimiento        : 
* Descripción del mantenimiento: 
* Responsable                  :
* Revisor                      : 
*/

namespace App\Http\Controllers;

use App\Models\ProcedureRequest;
use App\Models\SystemNotification;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\UnionRequests\UnionRequestIndexRequest;
use App\Http\Requests\UnionRequests\UnionApproveStepRequest;
use App\Http\Requests\UnionRequests\UnionNotifyErrorRequest;

class UnionRequestController extends Controller
{
	public function __construct()
	{
		$this->middleware(['auth', 'isUnion']);
	}

	
	public function index(UnionRequestIndexRequest $request): View
	{
		$data = $request->validated();

		$query = ProcedureRequest::with(['user', 'procedure'])
			->orderBy('created_at', 'desc');

		if (!empty($data['status']))
		{
			$query->where('status', $data['status']);
		}

		if (!empty($data['keyword']))
		{
			$kw = $data['keyword'];

			$query->where(function ($q) use ($kw)
			{
				$q->whereHas('user', function ($u) use ($kw)
				{
					$u->where('name', 'like', "%{$kw}%");
				})
				->orWhereHas('procedure', function ($p) use ($kw)
				{
					$p->where('name', 'like', "%{$kw}%");
				});
			});
		}

		$requests = $query->get();

		return view('union.requests.index', compact('requests'));
	}


	public function show(string $id): View
	{
		$requestData = ProcedureRequest::with([
			'user',
			'procedure',
			'procedure.steps',
			'documents',
		])->findOrFail($id);

		return view('union.requests.show', compact('requestData'));
	}

	public function approveStep(UnionApproveStepRequest $httpRequest, string $requestId, int $order): RedirectResponse
	{
		$validated = $httpRequest->validate([
			'result' => 'required|in:approve,reject',
			'comments' => 'nullable|string|max:1000',
		]);

		$result = $validated['result'];

		$request = ProcedureRequest::with(['procedure.steps', 'documents'])
			->findOrFail($requestId);

		$procedure = $request->procedure;
		$currentStep = $procedure->steps->firstWhere('order', $order);

		if (!$currentStep)
		{
			return back()->with('error', 'El paso solicitado no existe.');
		}

		if ($request->current_step !== $order)
		{
			return back()->with('error', 'Solo puedes revisar el paso actual.');
		}

		if ($currentStep->requires_file)
		{
			$hasFile = $request->documents()
				->where('procedure_step_id', $currentStep->id)
				->exists();

			if (!$hasFile)
			{
				return back()->with('error', 'Este paso requiere un archivo del trabajador.');
			}
		}

		if ($result === 'approve')
		{
			$nextOrder = $order + 1;
			$maxOrder = $procedure->steps_count;

			if ($nextOrder > $maxOrder)
			{
				$request->update([
					'status' => ProcedureRequest::STATUS_COMPLETED,
					'current_step' => $order,
				]);

				SystemNotification::create([
					'user_id' => $request->user_id,
					'title' => 'Trámite completado',
					'message' => "Tu trámite '{$procedure->name}' ha sido completado.",
					'type' => 'success',
					'status' => 'unread',
				]);

				return back()->with('success', 'El trámite fue completado correctamente.');
			}

			$request->update([
				'current_step' => $nextOrder,
				'status' => ProcedureRequest::STATUS_IN_PROGRESS,
			]);

			SystemNotification::create([
				'user_id' => $request->user_id,
				'title' => 'Paso aprobado',
				'message' => "El paso {$order} del trámite '{$procedure->name}' fue aprobado.",
				'type' => 'success',
				'status' => 'unread',
			]);

			return back()->with('success', 'Paso aprobado. Avanzando al siguiente.');
		}

		if (!empty($currentStep->next_step_if_fail))
		{
			$alternateOrder = (int) $currentStep->next_step_if_fail;

			if ($alternateOrder >= 1 && $alternateOrder <= $procedure->steps_count)
			{
				$request->update([
					'current_step' => $alternateOrder,
					'status' => ProcedureRequest::STATUS_PENDING_WORKER,
				]);

				SystemNotification::create([
					'user_id' => $request->user_id,
					'title' => 'Redirección por error',
					'message' => "Fuiste redirigido al paso alterno {$alternateOrder} del trámite '{$procedure->name}'.",
					'type' => 'warning',
					'status' => 'unread',
				]);

				return back()->with('success', "Paso rechazado. Redirigido al flujo alterno {$alternateOrder}.");
			}
		}

		$request->update([
			'status' => ProcedureRequest::STATUS_REJECTED,
			'current_step' => $order,
		]);

		SystemNotification::create([
			'user_id' => $request->user_id,
			'title' => 'Trámite rechazado',
			'message' => "Tu trámite '{$procedure->name}' fue rechazado.",
			'type' => 'error',
			'status' => 'unread',
		]);

		return back()->with('error', 'El trámite fue rechazado por errores en este paso.');
	}

	public function notifyError(UnionNotifyErrorRequest $httpRequest, string $requestId, int $order): RedirectResponse
	{
		$validated = $httpRequest->validate([
			'error_message' => 'required|string|max:500',
		]);

		$request = ProcedureRequest::findOrFail($requestId);

		SystemNotification::create([
			'user_id' => $request->user_id,
			'title' => 'Corrección requerida',
			'message' => $validated['error_message'],
			'type' => 'error',
			'status' => 'unread',
		]);

		$request->update([
			'status' => 'pending_worker',
		]);

		ActivityLog::create([
			'user_id' => Auth::id(),
			'module' => 'Solicitudes',
			'action' => "Notificó error en el paso {$order} de la solicitud {$request->id}",
			'ip_address' => $httpRequest->ip(),
		]);

		return back()->with('success', 'El error fue notificado correctamente.');
	}

	public function finalize(string $requestId, string $status): RedirectResponse
	{
		if (!in_array($status, ['completed', 'rejected']))
		{
			abort(400, 'Estado no permitido.');
		}

		$request = ProcedureRequest::findOrFail($requestId);

		$request->update([
			'status' => $status,
		]);

		ActivityLog::create([
			'user_id' => Auth::id(),
			'module' => 'Solicitudes',
			'action' => "Finalizó la solicitud {$request->id} con estado {$status}",
			'ip_address' => request()->ip(),
		]);

		return redirect()->route('union.requests.index')
			->with('success', 'El trámite fue actualizado correctamente.');
	}
}
