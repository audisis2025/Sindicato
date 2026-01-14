<?php
/*
* Nombre de la clase           : WorkerRequestController.php
* Descripción de la clase      : Controlador encargado de la gestión de solicitudes de trámites del trabajador: dashboard, inicio de trámites, visualización, envío de correcciones por paso y finalización/cancelación.
* Fecha de creación            : 27/11/2025
* Elaboró                      : Iker Piza
* Fecha de liberación          : 19/12/2025
* Autorizó                     :
* Versión                      : 1.1
* Fecha de mantenimiento       :
* Folio de mantenimiento       :
* Tipo de mantenimiento        :
* Descripción del mantenimiento:
* Responsable                  :
* Revisor                      :
*/

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Procedure;
use App\Models\ProcedureRequest;
use App\Models\ProcedureStep;
use App\Models\ProcedureDocument;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use App\Models\ActivityLog;

class WorkerRequestController extends Controller
{
	public function __construct()
	{
		$this->middleware(['auth', 'isWorker']);
	}

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

		$available = Procedure::orderBy('name')->get();

		return view('worker.dashboard', [
			'active_requests' => $active,
			'finished_requests' => $finished,
			'available_procedures' => $available
		]);
	}

	public function start(Request $request, string $procedureId): RedirectResponse
	{
		$user = Auth::id();

		$exists = ProcedureRequest::where('user_id', $user)
			->where('procedure_id', $procedureId)
			->whereNotIn('status', ['completed', 'rejected'])
			->exists();

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

			ProcedureDocument::create([
				'procedure_request_id' => $request->id,
				'procedure_step_id' => $step->id,
				'file_name' => $validated['file']->getClientOriginalName(),
				'file_path' => $path,
				'type' => $validated['file']->getClientOriginalExtension(),
				'year' => now()->year
			]);
		}

		ActivityLog::create([
			'user_id' => Auth::id(),
			'module' => 'Trámites',
			'action' => "Corrección enviada en paso {$order} del trámite {$request->id}",
			'ip_address' => $httpRequest->ip()
		]);

		$request->update([
			'status' => 'pending_union'
		]);

		return back()->with('success', 'Corrección enviada correctamente.');
	}

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
