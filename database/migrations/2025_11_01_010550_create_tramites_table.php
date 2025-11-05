<?php
/**
 * ===========================================================
 * Nombre de la clase: ProcedureController.php
 * Descripción: Controlador para la gestión de trámites (tabla 'tramites')
 * creados por el Sindicato. Permite alta, edición, consulta y eliminación.
 * Fecha de creación: 03/11/2025
 * Elaboró: Iker Piza
 * Fecha de liberación: 03/11/2025
 * Autorizó: Líder Técnico
 * Versión: 1.2
 * Tipo de mantenimiento: Corrección y homogeneización.
 * Descripción del mantenimiento: Se sustituyó el modelo Tramite por Procedure,
 * se ajustaron los nombres de vistas y se aplicó el estándar PRO-Laravel V3.2.
 * Responsable: Iker Piza
 * Revisor: QA SINDISOFT
 * ===========================================================
 */

namespace App\Http\Controllers\Union;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Procedure;

class ProcedureController extends Controller
{
    /**
     * 🧾 Mostrar lista de trámites creados por el sindicato autenticado.
     */
    public function index()
    {
        $procedures = Procedure::where('user_id', Auth::id())
            ->orderByDesc('id')
            ->get();

        return view('v.union.procedures-index', compact('procedures'));
    }

    /**
     * ➕ Formulario para crear un nuevo trámite.
     */
    public function create()
    {
        return view('v.union.procedures-create');
    }

    /**
     * 💾 Guardar un nuevo trámite (RF06–RF09).
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string|max:1000',
            'numero_pasos' => 'required|integer|min:1|max:20',
            'fecha_apertura' => 'nullable|date',
            'fecha_cierre' => 'nullable|date|after_or_equal:fecha_apertura',
            'tiempo_estimado_dias' => 'nullable|integer|min:1|max:365',
            'tiene_flujo_alterno' => 'nullable|boolean',
        ]);

        Procedure::create(array_merge($validated, [
            'user_id' => Auth::id(),
            'tiene_flujo_alterno' => $request->boolean('tiene_flujo_alterno'),
        ]));

        return redirect()
            ->route('union.procedures.index')
            ->with('success', '✅ Trámite registrado correctamente.');
    }

    /**
     * 👁️ Mostrar los detalles de un trámite.
     */
    public function show($id)
    {
        $procedure = Procedure::findOrFail($id);
        return view('v.union.procedures-show', compact('procedure'));
    }

    /**
     * ✏️ Formulario para editar un trámite existente.
     */
    public function edit($id)
    {
        $procedure = Procedure::findOrFail($id);
        return view('v.union.procedures-edit', compact('procedure'));
    }

    /**
     * 🔁 Actualizar los datos de un trámite.
     */
    public function update(Request $request, $id)
    {
        $procedure = Procedure::findOrFail($id);

        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string|max:1000',
            'numero_pasos' => 'required|integer|min:1|max:20',
            'fecha_apertura' => 'nullable|date',
            'fecha_cierre' => 'nullable|date|after_or_equal:fecha_apertura',
            'tiempo_estimado_dias' => 'nullable|integer|min:1|max:365',
            'tiene_flujo_alterno' => 'nullable|boolean',
        ]);

        $procedure->update(array_merge($validated, [
            'tiene_flujo_alterno' => $request->boolean('tiene_flujo_alterno'),
        ]));

        return redirect()
            ->route('union.procedures.index')
            ->with('success', '📝 Trámite actualizado correctamente.');
    }

    /**
     * 🗑️ Eliminar un trámite del registro.
     */
    public function destroy($id)
    {
        $procedure = Procedure::findOrFail($id);
        $procedure->delete();

        return redirect()
            ->route('union.procedures.index')
            ->with('success', '🗑️ Trámite eliminado correctamente.');
    }
}
