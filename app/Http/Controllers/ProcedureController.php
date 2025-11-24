<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\Procedure;
use App\Models\ProcedureStep;
use App\Services\SystemLogger;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class ProcedureController extends Controller
{
    /**
     * Listado de trámites del sindicato autenticado.
     */
    public function index(): View
    {
        $procedures = Procedure::where('user_id', Auth::id())
            ->orderBy('id', 'desc')
            ->get();

        return view('union.procedures.index', compact('procedures'));
    }

    /**
     * Formulario para crear trámite.
     */
    public function create(): View
    {
        return view('union.procedures.create');
    }

    /**
     * Guarda un trámite y sus pasos.
     */
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
            'steps.*.estimated_days' => 'nullable|integer|min:1|max:365',
            'steps.*.next_step_if_fail' => 'nullable|integer|min:1',
            'steps.*.requires_file' => 'required|in:yes,no',
            'steps.*.file_path' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx|max:10240',
        ]);

        $steps = $validated['steps'];
        $totalSteps = count($steps);

        /**
         * Validación del flujo alterno:
         * - No puede apuntar a un paso mayor
         * - No puede apuntar a sí mismo
         */
        foreach ($steps as $step) {
            if (!empty($step['next_step_if_fail'])) {
                if ($step['next_step_if_fail'] > $totalSteps) {
                    return back()->withInput()->with('error', 'El flujo alterno apunta a un paso inexistente.');
                }

                if ($step['next_step_if_fail'] == $step['order']) {
                    return back()->withInput()->with('error', 'Un paso no puede fallar hacia sí mismo.');
                }
            }
        }

        /**
         * Crear trámite
         */
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

        app(SystemLogger::class)->log('Crear trámite', "El sindicato creó el trámite: {$procedure->id}");

        /**
         * Guardar cada paso
         */
        foreach ($steps as $stepData) {
            $filePath = null;

            if (!empty($stepData['file_path'])) {
                $filePath = $stepData['file_path']->store('procedure_files', 'public');
            }

            $procedure->steps()->create([
                'order' => $stepData['order'],
                'step_name' => $stepData['step_name'],
                'step_description' => $stepData['step_description'] ?? null,
                'estimated_days' => $stepData['estimated_days'] ?? null,
                'next_step_if_fail' => $stepData['next_step_if_fail'] ?? null,
                'requires_file' => $stepData['requires_file'] === 'yes',
                'file_path' => $filePath,
            ]);
        }

        return redirect()->route('union.procedures.index')
            ->with('success', 'Trámite creado correctamente.');
    }

    /**
     * Mostrar detalle del trámite.
     */
    public function show(string $id): View
    {
        $procedure = Procedure::with('steps')->findOrFail($id);
        return view('union.procedures.show', compact('procedure'));
    }

    /**
     * Formulario de edición.
     */
    public function edit(string $id): View
    {
        $procedure = Procedure::with('steps')->findOrFail($id);
        return view('union.procedures.edit', compact('procedure'));
    }

    /**
     * Actualizar trámite.
     */
    public function update(Request $request, string $id): RedirectResponse
    {
        $procedure = Procedure::findOrFail($id);

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
            'steps.*.estimated_days' => 'nullable|integer|min:1|max:365',
            'steps.*.next_step_if_fail' => 'nullable|integer|min:1',
            'steps.*.requires_file' => 'nullable|in:yes,no',
            'steps.*.file_path' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx|max:10240',
        ]);

        /**
         * Actualizar trámite
         */
        $procedure->update([
            'name' => $validated['name'],
            'description' => $validated['description'],
            'opening_date' => $validated['opening_date'],
            'closing_date' => $validated['closing_date'],
            'estimated_days' => $validated['estimated_days'],
            'steps_count' => $request->has('steps')
                ? count($validated['steps'])
                : $procedure->steps_count,
            'has_alternate_flow' =>
            isset($validated['steps']) &&
                collect($validated['steps'])->contains(fn($s) => !empty($s['next_step_if_fail'])),
        ]);

        /**
         * Actualizar pasos
         */
        if ($request->has('steps')) {
            $newOrders = collect($validated['steps'])->pluck('order')->filter();
            $procedure->steps()->whereNotIn('order', $newOrders)->delete();

            foreach ($validated['steps'] as $stepData) {
                if (empty($stepData['order']) || empty($stepData['step_name'])) continue;

                $step = $procedure->steps()->where('order', $stepData['order'])->first();
                $filePath = $step->file_path ?? null;

                if (!empty($stepData['file_path'])) {
                    if ($filePath) Storage::disk('public')->delete($filePath);
                    $filePath = $stepData['file_path']->store('procedure_files', 'public');
                }

                $procedure->steps()->updateOrCreate(
                    ['order' => $stepData['order']],
                    [
                        'step_name' => $stepData['step_name'],
                        'step_description' => $stepData['step_description'] ?? null,
                        'estimated_days' => $stepData['estimated_days'] ?? null,
                        'next_step_if_fail' => $stepData['next_step_if_fail'] ?? null,
                        'requires_file' => $stepData['requires_file'] === 'yes',
                        'file_path' => $filePath,
                    ]
                );
            }
        }

        return redirect()->route('union.procedures.index')
            ->with('success', 'Trámite actualizado correctamente.');
    }

    /**
     * Eliminar trámite.
     */
    public function destroy(string $id): RedirectResponse
    {
        $procedure = Procedure::with('steps')->findOrFail($id);

        foreach ($procedure->steps as $step) {
            if ($step->file_path) {
                Storage::disk('public')->delete($step->file_path);
            }
        }

        $procedure->delete();

        return redirect()->route('union.procedures.index')
            ->with('success', 'Trámite eliminado correctamente.');
    }
    /**
     * Mostrar el detalle de una solicitud de trámite (RF-04).
     *
     * @param int $id
     * @return \Illuminate\View\View
     */
    public function showRequest($id): View
    {
        $requestData = \App\Models\ProcedureRequest::with([
            'user',
            'procedure',
            'procedure.steps'
        ])->findOrFail($id);

        return view('union.requests.procedures_requests_show', [
            'request' => $requestData

        ]);
    }
}
