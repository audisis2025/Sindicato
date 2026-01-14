<?php
/*
* Nombre de la clase           : ProcedureController.php
* Descripción de la clase      : Controlador encargado de la gestión de trámites del sindicato: creación, edición, flujo de pasos, activación/desactivación, eliminación y administración de solicitudes.
* Fecha de creación            : 15/11/2025
* Elaboró                      : Iker Piza
* Fecha de liberación          : 19/12/2025
* Autorizó                     :
* Versión                      : 1.3
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

	public function store(Request $request): RedirectResponse
	{
		$validated = $request->validate([
			'name' => 'required|string|max:255',
			'description' => 'nullable|string|max:1000',
			'opening_date' => 'nullable|date',
			'closing_date' => 'nullable|date|after_or_equal:opening_date',
			'estimated_days' => 'nullable|integer|min:1|max:365',
			'steps' => 'required|array|min:1',
			'steps.*.order' => 'required|integer|min:1',
			'steps.*.step_name' => 'required|string|max:255',
			'steps.*.step_description' => 'nullable|string|max:1000',
			'steps.*.next_step_if_fail' => 'nullable|integer|min:1',
			'steps.*.requires_file' => 'required|in:yes,no',
			'steps.*.file_path' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx|max:10240',
		]);

		$steps = $validated['steps'];
		$totalSteps = count($steps);

		foreach ($steps as $step)
		{
			if (!empty($step['next_step_if_fail']))
			{
				if ($step['next_step_if_fail'] > $totalSteps)
				{
					return back()->withInput()->with('error', 'El flujo alterno apunta a un paso inexistente.');
				}

				if ($step['next_step_if_fail'] == $step['order'])
				{
					return back()->withInput()->with('error', 'Un paso no puede tener flujo alterno a sí mismo.');
				}
			}
		}

		$procedure = Procedure::create([
			'user_id' => Auth::id(),
			'name' => $validated['name'],
			'description' => $validated['description'],
			'steps_count' => $totalSteps,
			'opening_date' => $validated['opening_date'],
			'closing_date' => $validated['closing_date'],
			'estimated_days' => $validated['estimated_days'],
			'has_alternate_flow' => collect($steps)->contains(function ($s)
			{
				return !empty($s['next_step_if_fail']);
			}),
		]);

		app(SystemLogger::class)->log('Crear trámite', "El sindicato creó el trámite {$procedure->id}.");

		foreach ($steps as $stepData)
		{
			$filePath = null;

			if (!empty($stepData['file_path']))
			{
				$filePath = $stepData['file_path']->store('procedure_files', 'public');
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

		return redirect()->route('union.procedures.index')
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

	public function update(Request $request, string $id): RedirectResponse
	{
		$procedure = Procedure::with('steps')->findOrFail($id);

		$validated = $request->validate([
			'name' => 'required|string|max:255',
			'description' => 'nullable|string|max:1000',
			'opening_date' => 'nullable|date',
			'closing_date' => 'nullable|date|after_or_equal:opening_date',
			'estimated_days' => 'nullable|integer|min:1|max:365',
			'steps' => 'nullable|array',
			'steps.*.order' => 'nullable|integer|min:1',
			'steps.*.step_name' => 'nullable|string|max:255',
			'steps.*.step_description' => 'nullable|string|max:1000',
			'steps.*.next_step_if_fail' => 'nullable|integer|min:1',
			'steps.*.requires_file' => 'nullable|in:yes,no',
			'steps.*.file_path' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx|max:10240',
		]);

		$stepsInput = $validated['steps'] ?? null;

		if ($stepsInput)
		{
			$totalSteps = count($stepsInput);

			foreach ($stepsInput as $step)
			{
				if (!empty($step['next_step_if_fail']))
				{
					if ($step['next_step_if_fail'] > $totalSteps)
					{
						return back()->withInput()->with('error', 'El flujo alterno apunta a un paso inexistente.');
					}

					if ($step['next_step_if_fail'] == $step['order'])
					{
						return back()->withInput()->with('error', 'Un paso no puede tener flujo alterno a sí mismo.');
					}
				}
			}
		}

		$procedure->update([
			'name' => $validated['name'],
			'description' => $validated['description'],
			'opening_date' => $validated['opening_date'],
			'closing_date' => $validated['closing_date'],
			'estimated_days' => $validated['estimated_days'],
			'steps_count' => $stepsInput ? count($stepsInput) : $procedure->steps_count,
			'has_alternate_flow' => $stepsInput && collect($stepsInput)->contains(function ($s)
			{
				return !empty($s['next_step_if_fail']);
			}),
		]);

		if (!$stepsInput)
		{
			return redirect()->route('union.procedures.index')
				->with('success', 'Trámite actualizado correctamente.');
		}

		$newOrders = collect($stepsInput)->pluck('order')->filter();

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

		foreach ($stepsInput as $stepData)
		{
			if (empty($stepData['order']) || empty($stepData['step_name']))
			{
				continue;
			}

			$step = $procedure->steps()->where('order', $stepData['order'])->first();

			$filePath = $step?->file_path ?? null;

			if (!empty($stepData['file_path']))
			{
				if ($filePath)
				{
					Storage::disk('public')->delete($filePath);
				}

				$filePath = $stepData['file_path']->store('procedure_files', 'public');
			}

			$procedure->steps()->updateOrCreate(
				['order' => $stepData['order']],
				[
					'step_name' => $stepData['step_name'],
					'step_description' => $stepData['step_description'] ?? null,
					'next_step_if_fail' => $stepData['next_step_if_fail'] ?? null,
					'requires_file' => $stepData['requires_file'] === 'yes',
					'file_path' => $filePath,
				]
			);
		}

		return redirect()->route('union.procedures.index')
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

		if ($procedure->requests()->exists())
		{
			return back()->with('error', 'No puedes eliminar este trámite porque tiene solicitudes asociadas. Puedes desactivarlo.');
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
