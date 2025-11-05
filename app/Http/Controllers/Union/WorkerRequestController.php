<?php
/**
 * ===========================================================
 * Nombre de la clase: WorkerRequestController
 * Descripción: Controlador para la gestión y revisión de solicitudes 
 * de trámites realizadas por los trabajadores del Sindicato.
 * Fecha de creación: 06/11/2025
 * Elaboró: Iker Piza
 * Versión: 1.0
 * Tipo de mantenimiento: Creación.
 * Descripción del mantenimiento: Implementa RF13 y RF14 (seguimiento de solicitudes de trabajadores).
 * Responsable: Iker Piza
 * Revisor: QA SINDISOFT
 * ===========================================================
 */



namespace App\Http\Controllers\Union;

use App\Http\Controllers\Controller;
use App\Models\SolicitudTramite;

class WorkerRequestController extends Controller
{
    /**
     * Listado de solicitudes realizadas por trabajadores (RF13–RF14)
     */
    public function index()
    {
        // Carga trabajador y trámite para poder mostrar nombre/etiquetas sin N+1
        $solicitudes = SolicitudTramite::with(['trabajador', 'tramite'])
            ->latest('created_at')
            ->get();

        // Vista del submódulo requests (no members)
        return view('union.requests.index', compact('solicitudes'));
    }
}
