<?php
/*
* ===========================================================
* Nombre de la clase: ProcedureController.php
* Descripción de la clase: Controlador para la gestión de plantillas de trámites (tabla 'procedures')
* creados por el Sindicato. Permite alta, edición, consulta y eliminación.
* Fecha de creación: 03/11/2025
* Elaboró: Iker Piza
* Fecha de liberación: 10/11/2025
* Autorizó: Líder Técnico
* Versión: 2.0
*
* Fecha de mantenimiento: 10/11/2025
* Folio de mantenimiento: [Tu Folio]
* Tipo de mantenimiento: Perfectivo
* Descripción del mantenimiento: Refactorizado al 100% para usar la BD en inglés.
* Se eliminaron modelos obsoletos (SolicitudTramite, TramitePaso) y
* se tradujeron todas las claves (nombre, pasos, etc.).
* Se eliminaron métodos de 'WorkerRequestController' (notifyError, approveStep).
* Responsable: [Tu Nombre]
* Revisor: [Tu Revisor]
* ===========================================================
*/

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\Procedure;
use App\Models\ProcedureStep;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use App\Services\SystemLogger;

class ProcedureController extends Controller // [cite: 887-890]
{
    /**
     * Muestra el listado de plantillas de trámites.
     *
     * @return \Illuminate\View\View
     */
    public function index(): View // [cite: 200, 217-218]
    {
        $procedures = Procedure::where('user_id', Auth::id())
            ->orderBy('id', 'desc')
            ->get();

        return view('union.procedures.index', compact('procedures')); // [cite: 288-291]
    }

    /**
     * Muestra el formulario de creación de trámite.
     *
     * @return \Illuminate\View\View
     */
    public function create(): View
    {
        return view('union.procedures.create');
    }

    /**
     * Registra un nuevo trámite y sus pasos.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request): RedirectResponse // [cite: 200, 214-215]
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255', // Antes 'nombre'
            'description' => 'nullable|string|max:1000', // Antes 'descripcion'
            'opening_date' => 'nullable|date', // Antes 'fecha_apertura'
            'closing_date' => 'nullable|date|after_or_equal:opening_date', // Antes 'fecha_cierre'
            'estimated_days' => 'nullable|integer|min:1|max:365', // Antes 'tiempo_estimado_dias'

            // Validación por pasos
            'steps' => 'required|array|min:1', // Antes 'pasos'
            'steps.*.order' => 'required|integer|min:1', // Antes 'orden'
            'steps.*.step_name' => 'required|string|max:255', // Antes 'nombre_paso'
            'steps.*.step_description' => 'nullable|string|max:1000', // Antes 'descripcion_paso'
            'steps.*.estimated_days' => 'nullable|integer|min:1|max:365', // Antes 'tiempo_estimado_dias'
            'steps.*.next_step_if_fail' => 'nullable|integer|min:1',
            'steps.*.file' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx|max:10240', // Antes 'formato'
        ]);

        // Validación de flujo alterno
        $totalSteps = count($validatedData['steps']);
        foreach ($validatedData['steps'] as $step) {
            if (!empty($step['next_step_if_fail']) && $step['next_step_if_fail'] > $totalSteps) {
                return back()
                    ->withInput()
                    ->with('error', 'El flujo alterno de un paso no puede apuntar a un paso mayor al total definido.');
            }
        }

        $procedure = Procedure::create([
            'user_id' => Auth::id(),
            'name' => $validatedData['name'],
            'description' => $validatedData['description'],
            'steps_count' => $totalSteps, // Antes 'numero_pasos'
            'opening_date' => $validatedData['opening_date'],
            'closing_date' => $validatedData['closing_date'],
            'estimated_days' => $validatedData['estimated_days'],
            'has_alternate_flow' => false, // Lógica ahora por paso
        ]);
        app(SystemLogger::class)->log(
            'Crear trámite',
            'El sindicato creó un trámite: ' . $procedure->id
        );

        // Guardar pasos
        foreach ($validatedData['steps'] as $stepData) {
            $filePath = null; // [cite: 236-239]

            if (isset($stepData['file'])) {
                $filePath = $stepData['file']->store('procedure_files', 'public');
            }

            // Asumimos que el modelo Procedure tiene una relación hasMany('steps')
            $procedure->steps()->create([
                'order' => $stepData['order'],
                'step_name' => $stepData['step_name'],
                'step_description' => $stepData['step_description'] ?? null,
                'estimated_days' => $stepData['estimated_days'] ?? null,
                'file_path' => $filePath, // Antes 'formato_path'
                'next_step_if_fail' => $stepData['next_step_if_fail'] ?? null,
            ]);
        }

        return redirect()
            ->route('union.procedures.index')
            ->with('success', '✅ Trámite creado correctamente.'); // [cite: 1291-1294]
    }

    /**
     * Muestra el detalle del trámite.
     *
     * @param string $id
     * @return \Illuminate\View\View
     */
    public function show(string $id): View
    {
        $procedure = Procedure::with('steps')->findOrFail($id); // Carga los pasos
        return view('union.procedures.show', compact('procedure'));
    }

    /**
     * Muestra el formulario de edición del trámite.
     *
     * @param string $id
     * @return \Illuminate\View\View
     */
    public function edit(string $id): View
    {
        $procedure = Procedure::with('steps')->findOrFail($id);
        return view('union.procedures.edit', compact('procedure'));
    }

    /**
     * Actualiza la información del trámite y sus pasos.
     *
     * @param \Illuminate\Http\Request $request
     * @param string $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, string $id): RedirectResponse
    {
        $procedure = Procedure::findOrFail($id);

        $validatedData = $request->validate([
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
            'steps.*.file' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx|max:10240',
            'steps.*.next_step_if_fail' => 'nullable|integer|min:1',
        ]);

        $procedure->update([
            'name' => $validatedData['name'],
            'description' => $validatedData['description'],
            'opening_date' => $validatedData['opening_date'],
            'closing_date' => $validatedData['closing_date'],
            'estimated_days' => $validatedData['estimated_days'],
            'steps_count' => $request->has('steps') ? count($request->steps) : $procedure->steps_count,
        ]);

        if ($request->has('steps')) {
            // Eliminar pasos viejos que no vinieron en el request
            $currentStepOrders = collect($validatedData['steps'])->pluck('order')->filter();
            $procedure->steps()->whereNotIn('order', $currentStepOrders)->delete();

            foreach ($validatedData['steps'] as $stepData) {
                if (empty($stepData['order']) || empty($stepData['step_name'])) continue;

                $step = $procedure->steps()->where('order', $stepData['order'])->first();
                $filePath = $step->file_path ?? null;

                if (isset($stepData['file'])) {
                    if ($filePath) Storage::disk('public')->delete($filePath);
                    $filePath = $stepData['file']->store('procedure_files', 'public');
                }

                $procedure->steps()->updateOrCreate(
                    ['order' => $stepData['order']], // Busca por orden
                    [
                        'step_name' => $stepData['step_name'],
                        'step_description' => $stepData['step_description'] ?? null,
                        'estimated_days' => $stepData['estimated_days'] ?? null,
                        'next_step_if_fail' => $stepData['next_step_if_fail'] ?? null,
                        'file_path' => $filePath,
                    ]
                );
            }
        }

        return redirect()->route('union.procedures.index')
            ->with('success', '📝 Trámite actualizado correctamente.');
    }

    /**
     * Elimina una plantilla de trámite.
     *
     * @param string $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(string $id): RedirectResponse
    {
        $procedure = Procedure::with('steps')->findOrFail($id);

        // Eliminar archivos de pasos
        foreach ($procedure->steps as $step) {
            if ($step->file_path) {
                Storage::disk('public')->delete($step->file_path);
            }
        }
        // Pasos y Trámite se borran en cascada (definido en la migración)
        $procedure->delete();

        return redirect()->route('union.procedures.index')
            ->with('success', '🗑️ Trámite eliminado correctamente.'); // [cite: 1257-1264]
    }
}
