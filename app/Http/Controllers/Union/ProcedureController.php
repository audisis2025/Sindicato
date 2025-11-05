<?php

/**
 * ===========================================================
 * Nombre de la clase: ProcedureController.php
 * Descripción: Controlador para la gestión de trámites creados por el usuario Sindicato.
 * Fecha de creación: 03/11/2025
 * Elaboró: Iker Piza
 * Fecha de liberación: 03/11/2025
 * Autorizó: Líder Técnico
 * Versión: 1.3
 * Tipo de mantenimiento: Corrección.
 * Descripción del mantenimiento: Se adaptó al modelo Procedure (tabla 'tramites') 
 * y se añadió registro automático del user_id (RF06–RF14).
 * Responsable: Iker Piza
 * Revisor: QA SINDISOFT
 * ===========================================================
 */

namespace App\Http\Controllers\Union;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Procedure;
use App\Models\SolicitudTramite;
use App\Models\TramitePaso;
use App\Models\BitacoraActividad;

class ProcedureController extends Controller
{
    /**
     * Mostrar listado de trámites registrados (RF06).
     */
    public function index()
    {
        $procedures = Procedure::where('user_id', Auth::id())
            ->orderBy('id', 'desc')
            ->get();

        return view('union.procedures.index', compact('procedures'));

    }

    /**
     * Mostrar formulario de creación de trámite (RF06).
     */
    public function create()
    {
        return view('union.procedures.create');
    }

    /**
     * Registrar un nuevo trámite (RF07–RF09).
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre'               => 'required|string|max:255',
            'descripcion'          => 'nullable|string|max:1000',
            'fecha_apertura'       => 'nullable|date',
            'fecha_cierre'         => 'nullable|date|after_or_equal:fecha_apertura',
            'tiempo_estimado_dias' => 'nullable|integer|min:1|max:365',

            // Validación por pasos
            'pasos'                        => 'required|array|min:1',
            'pasos.*.orden'                => 'required|integer|min:1',
            'pasos.*.nombre_paso'          => 'required|string|max:255',
            'pasos.*.descripcion_paso'     => 'nullable|string|max:1000',
            'pasos.*.tiempo_estimado_dias' => 'nullable|integer|min:1|max:365',
            'pasos.*.next_step_if_fail'    => 'nullable|integer|min:1',
            'pasos.*.formato'              => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx|max:10240',
        ]);

        // Validación lógica: next_step_if_fail no puede exceder numero_pasos
        foreach ($request->pasos as $p) {
            if (!empty($p['next_step_if_fail']) && $p['next_step_if_fail'] > (int)$request->numero_pasos) {
                return back()
                    ->withInput()
                    ->with('error', 'El flujo alterno de un paso no puede apuntar a un paso mayor al total definido.');
            }
        }

        $procedure = Procedure::create([
            'user_id'              => Auth::id(),
            'nombre'               => $request->nombre,
            'descripcion'          => $request->descripcion,
            'numero_pasos'         => count($request->pasos), // ✅ nuevo
            'fecha_apertura'       => $request->fecha_apertura,
            'fecha_cierre'         => $request->fecha_cierre,
            'tiempo_estimado_dias' => $request->tiempo_estimado_dias,
            'tiene_flujo_alterno'  => false, // ✅ siempre false, flujo por paso
        ]);

        // 2) Guardar pasos + archivo opcional + flujo alterno
        foreach ($request->pasos as $p) {
            $rutaArchivo = null;

            if (isset($p['formato']) && $p['formato']) {
                // Guardamos en storage/app/public/formatos_tramite
                $rutaArchivo = $p['formato']->store('formatos_tramite', 'public');
            }

            $procedure->pasos()->create([
                'orden'                => $p['orden'],
                'nombre_paso'          => $p['nombre_paso'],
                'descripcion_paso'     => $p['descripcion_paso'] ?? null,
                'tiempo_estimado_dias' => $p['tiempo_estimado_dias'] ?? null,
                'formato_path'         => $rutaArchivo,
                'next_step_if_fail'    => $p['next_step_if_fail'] ?? null,
            ]);
        }

        // Mensaje SweetAlert2 (tu layout ya muestra session('success'))
        return redirect()
            ->route('union.procedures.index')
            ->with('success', '✅ Trámite creado correctamente.');
    }


    /**
     * Mostrar detalle del trámite (RF13–RF14).
     */
    public function show($id)
    {
        $procedure = Procedure::findOrFail($id);
        return view('union.procedures.show', compact('procedure'));

    }

    /**
     * Mostrar formulario de edición (RF14).
     */
    public function edit($id)
    {
        $procedure = Procedure::findOrFail($id);
        return view('union.procedures.edit', compact('procedure'));
    }

    /**
     * Actualizar información del trámite (RF14).
     */
    public function update(Request $request, $id)
    {
        $procedure = Procedure::findOrFail($id);

        // ✅ Validación simplificada (sin numero_pasos)
        $request->validate([
            'nombre'               => 'required|string|max:255',
            'descripcion'          => 'nullable|string|max:1000',
            'fecha_apertura'       => 'nullable|date',
            'fecha_cierre'         => 'nullable|date|after_or_equal:fecha_apertura',
            'tiempo_estimado_dias' => 'nullable|integer|min:1|max:365',

            // Pasos (opcionales)
            'pasos'                        => 'nullable|array',
            'pasos.*.orden'                => 'nullable|integer|min:1',
            'pasos.*.nombre_paso'          => 'nullable|string|max:255',
            'pasos.*.descripcion_paso'     => 'nullable|string|max:1000',
            'pasos.*.tiempo_estimado_dias' => 'nullable|integer|min:1|max:365',
            'pasos.*.formato'              => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx|max:10240',
            'pasos.*.next_step_if_fail'    => 'nullable|integer|min:1',
        ]);

        // ✅ Actualizar datos del trámite
        $procedure->update([
            'nombre'               => $request->nombre,
            'descripcion'          => $request->descripcion,
            'fecha_apertura'       => $request->fecha_apertura,
            'fecha_cierre'         => $request->fecha_cierre,
            'tiempo_estimado_dias' => $request->tiempo_estimado_dias,
        ]);

        // ✅ Actualizar pasos (si los hay)
        if ($request->has('pasos')) {
            foreach ($request->pasos as $p) {
                // Solo actualiza si hay nombre
                if (!empty($p['nombre_paso'])) {
                    $procedure->pasos()->updateOrCreate(
                        ['orden' => $p['orden']],
                        [
                            'nombre_paso'          => $p['nombre_paso'],
                            'descripcion_paso'     => $p['descripcion_paso'] ?? null,
                            'tiempo_estimado_dias' => $p['tiempo_estimado_dias'] ?? null,
                            'next_step_if_fail'    => $p['next_step_if_fail'] ?? null,
                            'formato_path'         => isset($p['formato'])
                                ? $p['formato']->store('formatos_tramite', 'public')
                                : null,
                        ]
                    );
                }
            }
        }

        // ✅ SweetAlert2
        return redirect()->route('union.procedures.index')
            ->with('success', '📝 Trámite actualizado correctamente.');
    }
    public function showRequest($id)
    {
        // 🔹 Carga la solicitud con su trámite, pasos y trabajador
        $requestData = SolicitudTramite::with(['trabajador', 'tramite', 'pasos'])
            ->findOrFail($id);

        return view('union.procedures-requests-show', compact('requestData'));
    }

    /**
     * RF13 – Notificar error al trabajador sobre un paso específico
     */
    public function notifyError(Request $request, $id, $stepId)
    {
        $request->validate(['mensaje_error' => 'required|string|max:500']);

        // 🔹 Registrar la observación en bitácora_actividades
        BitacoraActividad::create([
            'procedure_id' => $id,
            'paso_id'      => $stepId,
            'user_id'      => Auth::id(),
            'mensaje'      => $request->mensaje_error,
            'tipo'         => 'error',
        ]);

        return back()->with('success', '⚠️ Error notificado correctamente al trabajador.');
    }

    /**
     * RF14 – Aprobar un paso dentro del trámite del trabajador
     */
    public function approveStep($id, $stepId)
    {
        $paso = TramitePaso::where('tramite_id', $id)
            ->where('id', $stepId)
            ->first();

        if ($paso) {
            $paso->update(['estado' => 'Aprobado']);
        }

        return back()->with('success', '✅ Paso aprobado correctamente.');
    }

    /**
     * RF14 – Finalizar trámite como Completado o Rechazado
     */
    public function finalize($id, $estado)
    {
        $validEstados = ['Completado', 'Rechazado'];
        if (!in_array($estado, $validEstados)) {
            abort(400, 'Estado inválido');
        }

        $solicitud = SolicitudTramite::findOrFail($id);
        $solicitud->update(['estado' => $estado]);

        return redirect()->route('union.reports.index')
            ->with('success', "🏁 Trámite marcado como {$estado} correctamente.");
    }


    /**
     * Eliminar trámite (RF14).
     */
    public function destroy($id)
    {
        $procedure = Procedure::findOrFail($id);
        $procedure->delete();

        return redirect()->route('union.procedures.index')
            ->with('success', '🗑️ Trámite eliminado correctamente.');
    }
}
