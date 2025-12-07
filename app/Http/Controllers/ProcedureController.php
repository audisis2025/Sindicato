<?php

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
    /**
     * Listado de trámites.
     */
    public function index(): View
    {
        $procedures = Procedure::orderBy('id', 'desc')->get();
        return view('union.procedures.index', compact('procedures'));
    }

    /**
     * Formulario de creación.
     */
    public function create(): View
    {
        return view('union.procedures.create');
    }

    public function store(Request $request): RedirectResponse
    {
        // ===========================
        // 1. VALIDACIÓN DE DATOS
        // ===========================
        $validated = $request->validate([
            'name'            => 'required|string|max:255',
            'description'     => 'nullable|string|max:1000',
            'opening_date'    => 'nullable|date',
            'closing_date'    => 'nullable|date|after_or_equal:opening_date',
            'estimated_days'  => 'nullable|integer|min:1|max:365',

            'steps'                     => 'required|array|min:1',
            'steps.*.order'             => 'required|integer|min:1',
            'steps.*.step_name'         => 'required|string|max:255',
            'steps.*.step_description'  => 'nullable|string|max:1000',
            'steps.*.next_step_if_fail' => 'nullable|integer|min:1',
            'steps.*.requires_file'     => 'required|in:yes,no',
            'steps.*.file_path'         => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx|max:10240',
        ]);

        $steps = $validated['steps'];
        $totalSteps = count($steps);

        // =======================================================
        // 2. VALIDACIÓN DEL FLUJO ALTERNO
        // =======================================================
        foreach ($steps as $step) {

            // Si tiene flujo alterno asignado...
            if (!empty($step['next_step_if_fail'])) {

                // 2.1 Debe existir
                if ($step['next_step_if_fail'] > $totalSteps) {
                    return back()->withInput()->with('error', 'El flujo alterno apunta a un paso inexistente.');
                }

                // 2.2 No puede apuntar a sí mismo
                if ($step['next_step_if_fail'] == $step['order']) {
                    return back()->withInput()->with('error', 'Un paso no puede tener flujo alterno a sí mismo.');
                }
            }
        }

        $procedure = Procedure::create([
            'user_id'            => Auth::id(),
            'name'               => $validated['name'],
            'description'        => $validated['description'],
            'steps_count'        => $totalSteps,
            'opening_date'       => $validated['opening_date'],
            'closing_date'       => $validated['closing_date'],
            'estimated_days'     => $validated['estimated_days'],
            'has_alternate_flow' => collect($steps)->contains(fn($s) => !empty($s['next_step_if_fail'])),
        ]);

        app(SystemLogger::class)->log('Crear trámite', "El sindicato creó el trámite {$procedure->id}.");

        foreach ($steps as $stepData) {

            $filePath = null;

            // Subida de archivo
            if (!empty($stepData['file_path'])) {
                $filePath = $stepData['file_path']->store('procedure_files', 'public');
            }

            $procedure->steps()->create([
                'order'             => $stepData['order'],
                'step_name'         => $stepData['step_name'],
                'step_description'  => $stepData['step_description'] ?? null,
                'next_step_if_fail' => $stepData['next_step_if_fail'] ?? null,
                'requires_file'     => $stepData['requires_file'] === 'yes',
                'file_path'         => $filePath,
            ]);
        }

        return redirect()->route('union.procedures.index')
            ->with('success', 'Trámite creado correctamente.');
    }


    /**
     * Mostrar trámite.
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


    public function update(Request $request, string $id): RedirectResponse
    {
        $procedure = Procedure::with('steps')->findOrFail($id);

        // ======================================================
        // 1. VALIDACIÓN DE CAMPOS
        // ======================================================
        $validated = $request->validate([
            'name'            => 'required|string|max:255',
            'description'     => 'nullable|string|max:1000',
            'opening_date'    => 'nullable|date',
            'closing_date'    => 'nullable|date|after_or_equal:opening_date',
            'estimated_days'  => 'nullable|integer|min:1|max:365',

            'steps'                     => 'nullable|array',
            'steps.*.order'             => 'nullable|integer|min:1',
            'steps.*.step_name'         => 'nullable|string|max:255',
            'steps.*.step_description'  => 'nullable|string|max:1000',
            'steps.*.next_step_if_fail' => 'nullable|integer|min:1',
            'steps.*.requires_file'     => 'nullable|in:yes,no',
            'steps.*.file_path'         => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx|max:10240',
        ]);

        $stepsInput = $validated['steps'] ?? null;

        // ======================================================
        // 2. SI HAY PASOS, VALIDAR EL FLUJO ALTERNO
        // ======================================================
        if ($stepsInput) {
            $totalSteps = count($stepsInput);

            foreach ($stepsInput as $step) {
                if (!empty($step['next_step_if_fail'])) {

                    // 2.1 Apunta a un paso válido
                    if ($step['next_step_if_fail'] > $totalSteps) {
                        return back()->withInput()->with('error', 'El flujo alterno apunta a un paso inexistente.');
                    }

                    // 2.2 No puede apuntarse a sí mismo
                    if ($step['next_step_if_fail'] == $step['order']) {
                        return back()->withInput()->with('error', 'Un paso NO puede tener flujo alterno a sí mismo.');
                    }
                }
            }
        }

        // ======================================================
        // 3. ACTUALIZAR TRÁMITE
        // ======================================================
        $procedure->update([
            'name'               => $validated['name'],
            'description'        => $validated['description'],
            'opening_date'       => $validated['opening_date'],
            'closing_date'       => $validated['closing_date'],
            'estimated_days'     => $validated['estimated_days'],
            'steps_count'        => $stepsInput ? count($stepsInput) : $procedure->steps_count,
            'has_alternate_flow' =>
            $stepsInput &&
                collect($stepsInput)->contains(fn($s) => !empty($s['next_step_if_fail'])),
        ]);

        // ======================================================
        // 4. SI NO HAY PASOS NUEVOS, TERMINAR
        // ======================================================
        if (!$stepsInput) {
            return redirect()
                ->route('union.procedures.index')
                ->with('success', 'Trámite actualizado correctamente.');
        }

        // ======================================================
        // 5. ELIMINAR PASOS QUE YA NO SE USAN
        // ======================================================
        $newOrders = collect($stepsInput)->pluck('order')->filter();

        $procedure->steps()
            ->whereNotIn('order', $newOrders)
            ->get()
            ->each(function ($oldStep) {
                if ($oldStep->file_path) {
                    Storage::disk('public')->delete($oldStep->file_path);
                }
                $oldStep->delete();
            });

        // ======================================================
        // 6. CREAR O ACTUALIZAR PASOS
        // ======================================================
        foreach ($stepsInput as $stepData) {

            // Ignore campos vacíos
            if (empty($stepData['order']) || empty($stepData['step_name'])) {
                continue;
            }

            // Buscar paso existente por ORDER
            $step = $procedure->steps()->where('order', $stepData['order'])->first();

            $filePath = $step?->file_path ?? null;

            // Si subió un archivo nuevo reemplazarlo
            if (!empty($stepData['file_path'])) {

                // Borrar archivo anterior
                if ($filePath) {
                    Storage::disk('public')->delete($filePath);
                }

                $filePath = $stepData['file_path']->store('procedure_files', 'public');
            }

            // Crear o actualizar
            $procedure->steps()->updateOrCreate(
                ['order' => $stepData['order']], // criterio de búsqueda
                [
                    'step_name'         => $stepData['step_name'],
                    'step_description'  => $stepData['step_description'] ?? null,
                    'next_step_if_fail' => $stepData['next_step_if_fail'] ?? null,
                    'requires_file'     => $stepData['requires_file'] === 'yes',
                    'file_path'         => $filePath,
                ]
            );
        }

        // ======================================================
        // 7. FINALIZAR
        // ======================================================
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

        return back()->with(
            'success',
            $procedure->status === 'active'
                ? '✔ Trámite activado.'
                : '✔ Trámite desactivado.'
        );
    }


    public function destroy(string $id): RedirectResponse
    {
        $procedure = Procedure::with('requests', 'steps')->findOrFail($id);

        if ($procedure->requests()->exists()) {
            return back()->with('error', '⚠️ No puedes eliminar este trámite porque tiene solicitudes asociadas. Puedes desactivarlo.');
        }

        // Borrar archivos y pasos si está permitido eliminar
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
     * Mostrar detalle de solicitud (RF-04).
     */
    public function showRequest($id): View
    {
        $requestData = ProcedureRequest::with([
            'user',
            'procedure',
            'procedure.steps'
        ])->findOrFail($id);

        return view('union.requests.procedures_requests_show', [
            'request' => $requestData
        ]);
    }



    public function notifyError(Request $request, $id, $stepOrder)
    {
        $solicitud = \App\Models\ProcedureRequest::with('procedure.steps')->findOrFail($id);

        // Estado correcto para regresarlo al trabajador
        $solicitud->status = \App\Models\ProcedureRequest::STATUS_PENDING_WORKER;

        // No avanzamos pasos: el trabajador debe corregir el actual
        // Mantiene su current_step
        $solicitud->save();

        // Notificación
        $mensaje = "El paso {$stepOrder} del trámite '{$solicitud->procedure->name}' requiere correcciones.";
        $solicitud->user->notify(new \App\Notifications\ProcedureReminderNotification($mensaje));

        return redirect()
            ->back()
            ->with('success', 'Se notificó al trabajador sobre el error en este paso.');
    }

    /**
     * Finalizar solicitud manualmente.
     */
    public function finalize(Request $request, $id, $estado)
    {
        $solicitud = ProcedureRequest::findOrFail($id);

        $estado = strtolower($estado);

        if (!in_array($estado, ['completed', 'rejected'])) {
            abort(400, "Estado no válido.");
        }

        $solicitud->status = $estado;
        $solicitud->save();

        return redirect()
            ->route('union.workers.requests.index')
            ->with('success', 'La solicitud ha sido marcada como ' . ($estado === 'completed' ? 'completada' : 'rechazada') . '.');
    }
}
