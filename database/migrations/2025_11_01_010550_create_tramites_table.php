<?php
/**
 * ===========================================================
 * Nombre de la clase: ProcedureController.php
 * Descripción: Controlador para la gestión de trámites (tabla 'tramites') creados por el Sindicato.
 * Fecha de creación: 03/11/2025
 * Elaboró: Iker Piza
 * Fecha de liberación: 03/11/2025
 * Autorizó: Líder Técnico
 * Versión: 1.1
 * Tipo de mantenimiento: Adaptación.
 * Descripción del mantenimiento: Se ajustaron los nombres de columnas al esquema real de la tabla 'tramites'.
 * Responsable: Iker Piza
 * Revisor: QA SINDISOFT
 * ===========================================================
 */

namespace App\Http\Controllers\Union;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Tramite;
use Illuminate\Support\Facades\Auth;

class ProcedureController extends Controller
{
    /**
     * Mostrar lista de trámites creados por el sindicato autenticado.
     */
    public function index()
    {
        $procedures = Tramite::where('user_id', Auth::id())
            ->orderBy('id', 'desc')
            ->get();

        return view('v.union.procedures-index', compact('procedures'));
    }

    /**
     * Formulario para crear un nuevo trámite.
     */
    public function create()
    {
        return view('v.union.procedures');
    }

    /**
     * Guardar un nuevo trámite (RF06–RF09).
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string|max:1000',
            'numero_pasos' => 'required|integer|min:1|max:20',
            'fecha_apertura' => 'nullable|date',
            'fecha_cierre' => 'nullable|date|after_or_equal:fecha_apertura',
            'tiempo_estimado_dias' => 'nullable|integer|min:1|max:365',
            'tiene_flujo_alterno' => 'nullable|boolean',
        ]);

        Tramite::create([
            'user_id' => Auth::id(),
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion,
            'numero_pasos' => $request->numero_pasos,
            'fecha_apertura' => $request->fecha_apertura,
            'fecha_cierre' => $request->fecha_cierre,
            'tiempo_estimado_dias' => $request->tiempo_estimado_dias,
            'tiene_flujo_alterno' => $request->tiene_flujo_alterno ?? false,
        ]);

        return redirect()->route('union.procedures.index')
            ->with('success', '✅ Trámite registrado correctamente.');
    }

    /**
     * Mostrar detalles del trámite.
     */
    public function show($id)
    {
        $procedure = Tramite::findOrFail($id);
        return view('v.union.procedures-show', compact('procedure'));
    }

    /**
     * Formulario de edición.
     */
    public function edit($id)
    {
        $procedure = Tramite::findOrFail($id);
        return view('v.union.procedures-edit', compact('procedure'));
    }

    /**
     * Actualizar un trámite existente.
     */
    public function update(Request $request, $id)
    {
        $procedure = Tramite::findOrFail($id);

        $request->validate([
            'nombre' => 'required|string|max:255',
            'descripcion' => 'nullable|string|max:1000',
            'numero_pasos' => 'required|integer|min:1|max:20',
            'fecha_apertura' => 'nullable|date',
            'fecha_cierre' => 'nullable|date|after_or_equal:fecha_apertura',
            'tiempo_estimado_dias' => 'nullable|integer|min:1|max:365',
            'tiene_flujo_alterno' => 'nullable|boolean',
        ]);

        $procedure->update($request->only([
            'nombre',
            'descripcion',
            'numero_pasos',
            'fecha_apertura',
            'fecha_cierre',
            'tiempo_estimado_dias',
            'tiene_flujo_alterno'
        ]));

        return redirect()->route('union.procedures.index')
            ->with('success', '📝 Trámite actualizado correctamente.');
    }

    /**
     * Eliminar un trámite.
     */
    public function destroy($id)
    {
        $procedure = Tramite::findOrFail($id);
        $procedure->delete();

        return redirect()->route('union.procedures.index')
            ->with('success', '🗑️ Trámite eliminado correctamente.');
    }
}
