<?php
/*
* Nombre de la clase           : ProcedureController.php
* Descripción de la clase      : Controlador encargado de la gestión de trámites del sindicato: creación, edición, flujo de pasos, activación/desactivación, eliminación y administración de solicitudes.
* Fecha de creación            : 28/09/2025
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

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\Procedure;
use App\Models\ProcedureStep;
use App\Models\ProcedureRequest;
use App\Services\SystemLogger;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\Procedures\ProcedureStoreRequest;
use App\Http\Requests\Procedures\ProcedureUpdateRequest;

class ProcedureController extends Controller
{
	public function index(): View
	{
		$procedures = Procedure::orderBy('id', 'desc')->get();

		return view('union.procedures.index', compact('procedures'));
	}

	public function create(): View
	{
		return view('union.procedures.create');
	}

	public function store(ProcedureStoreRequest $request): RedirectResponse
	{
		$validated = $request->validated();

		$steps = $validated['steps'];
		$totalSteps = count($steps);

		$procedure = Procedure::create([
			'user_id' => Auth::id(),
			'name' => $validated['name'],
			'description' => $validated['description'],
			'steps_count' => $totalSteps,
			'opening_date' => $validated['opening_date'],
			'closing_date' => $validated['closing_date'],
			'estimated_days' => $validated['estimated_days'],
			'has_alternate_flow' => collect($steps)->contains(fn($s) => !empty($s['next_step_if_fail'])),
		]);

		app(SystemLogger::class)->log('Crear trámite', "El sindicato creó el trámite {$procedure->id}.");

		foreach ($steps as $i => $stepData) 
		{
			$filePath = null;

			if ($request->hasFile("steps.$i.file_path")) 
			{
				$filePath = $request->file("steps.$i.file_path")->store('procedure_files', 'public');
			}

			$procedure->steps()->create([
				'order' => $stepData['order'],
				'step_name' => $stepData['step_name'],
				'step_description' => $stepData['step_description'] ?? null,
				'next_step_if_fail' => $stepData['next_step_if_fail'] ?? null,
				'requires_file' => $stepData['requires_file'] === 'yes',
				'file_path' => $filePath,
			]);
		}

		return redirect()
			->route('union.procedures.index')
			->with('success', 'Trámite creado correctamente.');
	}


	public function show(string $id): View
	{
		$procedure = Procedure::with('steps')->findOrFail($id);

		return view('union.procedures.show', compact('procedure'));
	}

	public function edit(string $id): View
	{
		$procedure = Procedure::with('steps')->findOrFail($id);

		return view('union.procedures.edit', compact('procedure'));
	}

	public function update(ProcedureUpdateRequest $request, string $id): RedirectResponse
	{
		$procedure = Procedure::with('steps')->findOrFail($id);

		$validated = $request->validated();
		$stepsInput = $validated['steps'] ?? null;

		$procedure->update([
			'name' => $validated['name'],
			'description' => $validated['description'],
			'opening_date' => $validated['opening_date'],
			'closing_date' => $validated['closing_date'],
			'estimated_days' => $validated['estimated_days'],
			'steps_count' => $stepsInput ? count($stepsInput) : $procedure->steps_count,
			'has_alternate_flow' => $stepsInput
				? collect($stepsInput)->contains(fn($s) => !empty($s['next_step_if_fail']))
				: $procedure->has_alternate_flow,
		]);

		if ($stepsInput === null) 
		{
			return redirect()
				->route('union.procedures.index')
				->with('success', 'Trámite actualizado correctamente.');
		}

		$totalSteps = count($stepsInput);

		foreach ($stepsInput as $i => $stepData) 
		{
			if (empty($stepData['order']) || empty($stepData['step_name'])) 
			{
				return back()->withInput()->with('error', 'Todos los pasos deben tener orden y nombre.');
			}

			$order = (int) $stepData['order'];
			if (!empty($stepData['next_step_if_fail'])) 
			{
				$next = (int) $stepData['next_step_if_fail'];

				if ($next > $totalSteps) {
					return back()->withInput()->with('error', 'El flujo alterno apunta a un paso inexistente.');
				}
				if ($next === $order) {
					return back()->withInput()->with('error', 'Un paso no puede tener flujo alterno a sí mismo.');
				}
			}
		}
		$orders = collect($stepsInput)->pluck('order')->filter()->values();

		if ($orders->count() !== $orders->unique()->count()) 
		{
			return back()->withInput()->with('error', 'No puede haber pasos con el mismo orden.');
		}

		$newOrders = collect($stepsInput)->pluck('order')->filter()->values();

		$procedure->steps()
			->whereNotIn('order', $newOrders)
			->get()
			->each(function ($oldStep) 
			{
				if ($oldStep->file_path) 
				{
					Storage::disk('public')->delete($oldStep->file_path);
				}
				$oldStep->delete();
			});

		foreach ($stepsInput as $i => $stepData) 
		{
			$order = (int) $stepData['order'];

			$step = $procedure->steps()->where('order', $order)->first();
			$filePath = $step?->file_path;

			if ($request->hasFile("steps.$i.file_path")) 
			{
				if ($filePath) 
				{
					Storage::disk('public')->delete($filePath);
				}
				$filePath = $request->file("steps.$i.file_path")->store('procedure_files', 'public');
			}

			$procedure->steps()->updateOrCreate(
				['order' => $order],
				[
					'step_name' => $stepData['step_name'],
					'step_description' => $stepData['step_description'] ?? null,
					'next_step_if_fail' => $stepData['next_step_if_fail'] ?? null,
					'requires_file' => ($stepData['requires_file'] ?? 'no') === 'yes',
					'file_path' => $filePath,
				]
			);
		}

		return redirect()
			->route('union.procedures.index')
			->with('success', 'Trámite actualizado correctamente.');
	}


	public function toggleStatus(string $id): RedirectResponse
	{
		$procedure = Procedure::findOrFail($id);

		$procedure->status = $procedure->status === 'active'
			? 'inactive'
			: 'active';

		$procedure->save();

		return back()->with('success', $procedure->status === 'active'
			? 'Trámite activado.'
			: 'Trámite desactivado.');
	}

	public function destroy(string $id): RedirectResponse
	{
		$procedure = Procedure::with('requests', 'steps')->findOrFail($id);

		if ($procedure->requests()->whereIn('status', [
			ProcedureRequest::STATUS_INITIATED,
			ProcedureRequest::STATUS_IN_PROGRESS,
			ProcedureRequest::STATUS_PENDING_UNION,
			ProcedureRequest::STATUS_PENDING_WORKER,
		])->exists()) {
			return back()->with('error', 'No puedes eliminar este trámite porque tiene solicitudes activas asociadas. Puedes desactivarlo.');
		}

		foreach ($procedure->steps as $step) 
		{
			if ($step->file_path) 
			{
				Storage::disk('public')->delete($step->file_path);
			}
		}

		$procedure->delete();

		return redirect()->route('union.procedures.index')
			->with('success', 'Trámite eliminado correctamente.');
	}

	public function showRequest($id): View
	{
		$requestData = ProcedureRequest::with([
			'user',
			'procedure',
			'procedure.steps'
		])->findOrFail($id);

		return view('union.requests.procedures_requests_show', [
			'request' => $requestData,
		]);
	}

	public function notifyError(Request $request, $id, $stepOrder): RedirectResponse
	{
		$solicitud = ProcedureRequest::with('procedure.steps')->findOrFail($id);

		$solicitud->status = ProcedureRequest::STATUS_PENDING_WORKER;
		$solicitud->save();

		$mensaje = "El paso {$stepOrder} del trámite '{$solicitud->procedure->name}' requiere correcciones.";

		$solicitud->user->notify(
			new \App\Notifications\ProcedureReminderNotification($mensaje)
		);

		return back()->with('success', 'Se notificó al trabajador sobre el error en este paso.');
	}

	public function finalize(Request $request, $id, $estado): RedirectResponse
	{
		$solicitud = ProcedureRequest::findOrFail($id);

		$estado = strtolower($estado);

		if (!in_array($estado, ['completed', 'rejected'])) 
		{
			abort(400, 'Estado no válido.');
		}

		$solicitud->status = $estado;
		$solicitud->save();

		return redirect()
			->route('union.workers.requests.index')
			->with('success', 'La solicitud ha sido marcada como ' . ($estado === 'completed' ? 'completada' : 'rechazada') . '.');
	}
}
