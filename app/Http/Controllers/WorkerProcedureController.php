<?php
/*
* Nombre de la clase           : WorkerProcedureController.php
* Descripción de la clase      : Controlador encargado de la gestión de trámites del trabajador: listado de solicitudes activas/finalizadas, inicio de trámites, visualización de pasos, envío de pasos, carga de documentos, cancelación y consulta del catálogo.
* Fecha de creación            : 30/09/2025
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

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Procedure;
use App\Models\ProcedureRequest;
use App\Models\ProcedureStep;
use App\Models\ProcedureDocument;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use App\Models\News;
use Illuminate\Support\Facades\Storage;

class WorkerProcedureController extends Controller
{
	public function __construct()
	{
		$this->middleware('auth');
	}

	public function index(): View
	{
		$userId = Auth::id();

		$active = ['initiated', 'in_progress', 'pending_union', 'pending_worker'];

		$activeRequests = ProcedureRequest::with('procedure')
			->where('user_id', $userId)
			->whereIn('status', $active)
			->latest()
			->get();

		$finishedRequests = ProcedureRequest::with('procedure')
			->where('user_id', $userId)
			->whereIn('status', ['completed', 'rejected', 'cancelled'])
			->latest()
			->get();

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

	public function start(string $id): RedirectResponse
	{
		$userId = Auth::id();

		$blocked = [
			'initiated',
			'in_progress',
			'pending_union',
			'pending_worker',
			'completed',
		];

		$exists = ProcedureRequest::where('user_id', $userId)
			->where('procedure_id', $id)
			->whereIn('status', $blocked)
			->exists();

		if ($exists) 
		{
			return back()->with('error', 'Ya registraste este trámite y no puedes iniciarlo nuevamente.');
		}

		$newRequest = ProcedureRequest::create([
			'user_id' => $userId,
			'procedure_id' => $id,
			'current_step' => 1,
			'status' => 'initiated',
		]);

		return redirect()
			->route('worker.procedures.show', $newRequest->id)
			->with('success', 'Trámite iniciado correctamente.');
	}

	public function show(string $id): View
	{
		$request = ProcedureRequest::with(['procedure.steps', 'documents'])
			->where('id', $id)
			->where('user_id', Auth::id())
			->firstOrFail();

		return view('worker.requests.show', [
			'procedure_request' => $request,
		]);
	}

	public function upload(Request $req, string $solicitudId, string $pasoId): RedirectResponse
	{
		$validated = $req->validate([
			'file' => ['required', 'file', 'max:10240', 'mimes:pdf,doc,docx,jpg,jpeg,png,xls,xlsx'],
		]);

		$procedureRequest = ProcedureRequest::with('procedure.steps')
			->where('id', $solicitudId)
			->where('user_id', Auth::id())
			->firstOrFail();

		$step = $procedureRequest->procedure->steps->firstWhere('id', (int) $pasoId);

		if (!$step) 
		{
			return back()->with('error', 'El paso seleccionado no pertenece a este trámite.');
		}

		if ((int) $procedureRequest->current_step !== (int) $step->order) 
		{
			return back()->with('error', 'Debes completar los pasos en orden.');
		}

		if (!$step->requires_file) 
		{
			return back()->with('error', 'Este paso no requiere archivo.');
		}

		$file = $validated['file'];
		$path = $file->store('worker_uploads', 'public');

		$existing = ProcedureDocument::where('procedure_request_id', $procedureRequest->id)
			->where('procedure_step_id', $step->id)
			->first();

		if ($existing && $existing->file_path) 
		{
			Storage::disk('public')->delete($existing->file_path);
		}

		ProcedureDocument::updateOrCreate(
			[
				'procedure_request_id' => $procedureRequest->id,
				'procedure_step_id' => $step->id,
			],
			[
				'file_name' => $file->getClientOriginalName(),
				'file_path' => $path,
				'type' => $file->getClientOriginalExtension(),
				'year' => now()->year,
			]
		);

		$procedureRequest->update([
			'status' => 'pending_union',
		]);

		return back()->with('success', 'Archivo enviado al sindicato para revisión.');
	}

	public function cancel(string $id): RedirectResponse
	{
		$req = ProcedureRequest::where('id', $id)
			->where('user_id', Auth::id())
			->whereIn('status', ['initiated', 'in_progress', 'pending_union', 'pending_worker'])
			->firstOrFail();

		$req->update(['status' => 'cancelled']);

		return redirect()->route('worker.index')
			->with('success', 'Trámite cancelado.');
	}

	public function sendStep(string $requestId, string $stepId): RedirectResponse
	{
		$request = ProcedureRequest::with(['procedure.steps', 'documents'])
			->where('id', $requestId)
			->where('user_id', Auth::id())
			->firstOrFail();

		$step = $request->procedure->steps->firstWhere('id', (int) $stepId);

		if (!$step) 
		{
			return back()->with('error', 'El paso seleccionado no pertenece a este trámite.');
		}

		if ((int) $request->current_step !== (int) $step->order) 
		{
			return back()->with('error', 'Debes completar los pasos en orden.');
		}

		if ($step->requires_file) 
		{
			return back()->with('error', 'Este paso requiere subir un archivo primero.');
		}

		$request->update([
			'status' => 'pending_union',
		]);

		return back()->with('success', 'Paso enviado al sindicato para revisión.');
	}


	public function catalog(): View
	{
		$userId = Auth::id();

		$active = ['initiated', 'in_progress', 'pending_union', 'pending_worker'];

		$procedures = Procedure::whereNotIn('id', function ($q) use ($userId, $active) {
			$q->select('procedure_id')
				->from('procedure_requests')
				->where('user_id', $userId)
				->whereIn('status', $active);
		})
			->withCount('steps')
			->orderBy('name')
			->get();

		$news = News::where('status', 'published')
			->orderBy('publication_date', 'desc')
			->get();

		return view('worker.catalog.index', [
			'procedures' => $procedures,
			'news' => $news,
		]);
	}

	public function catalogDetail(string $id): View
	{
		$procedure = Procedure::with('steps')->findOrFail($id);

		return view('worker.catalog.detail', [
			'procedure' => $procedure,
		]);
	}
}
