<?php
/*
* ===========================================================
* Nombre de la clase: ReportController.php
* Descripción de la clase: Genera reportes, estadísticas y exportaciones
* sobre las solicitudes de trámites.
* Fecha de creación: 03/11/2025
* Elaboró: Iker Piza
* Fecha de liberación: 10/11/2025
* Autorizó: Líder Técnico
* Versión: 2.0
*
* Fecha de mantenimiento: 10/11/2025
* Folio de mantenimiento: [Tu Folio]
* Tipo de mantenimiento: Perfectivo
* Descripción del mantenimiento: Refactorizado para usar la BD en inglés (name),
* consultar 'procedure_requests' (en lugar de 'procedures') y
* cumplir con el Manual PRO-Laravel V3.2.
* Responsable: [Tu Nombre]
* Revisor: [Tu Revisor]
* ===========================================================
*/

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Procedure;
use App\Models\ProcedureRequest; // Modelo correcto para reportes
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ReportesExport; // Este archivo también necesitará refactorización
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class ReportController extends Controller // [cite: 887-890]
{
    /**
     * Panel principal de reportes (agrupa y filtra por nombre del trámite).
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request): View // [cite: 200, 217-218]
    {
        $from = $request->input('from');
        $to = $request->input('to');
        $type = $request->input('type'); // nombre del trámite (procedure.name)
        $filters = compact('from', 'to', 'type');

        // Nombres de plantillas de trámites para el <select>
        $procedureTypes = Procedure::query() // [cite: 236-239]
            ->select('name') // Corregido
            ->distinct()
            ->orderBy('name') // Corregido
            ->pluck('name') // Corregido
            ->toArray();

        $query = ProcedureRequest::with(['user', 'procedure']); // Consultamos solicitudes

        if ($from && $to) {
            $query->whereBetween('created_at', [$from, $to]);
        }

        // Filtrar por nombre de trámite (usando la relación)
        if ($type) {
            $query->whereHas('procedure', function ($q) use ($type) {
                $q->where('name', $type); // Corregido
            });
        }

        $requests = $query->orderByDesc('created_at')->get(); // [cite: 236-239]

        // Estadísticas por nombre de trámite
        $statistics = $requests // [cite: 236-239]
            ->groupBy(fn ($req) => $req->procedure->name ?? 'Sin Trámite') // Corregido
            ->map->count();

        return view('union.reports.index', [
            'requests' => $requests, // [cite: 288-291]
            'statistics' => $statistics,
            'filters' => $filters,
            'procedure_types' => $procedureTypes, // [cite: 288-291]
        ]);
    }

    /**
     * Datos JSON para Chart.js (conteo por nombre de trámite).
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getChartData(Request $request): JsonResponse
    {
        $from = $request->input('from');
        $to = $request->input('to');
        $type = $request->input('type');

        $query = ProcedureRequest::query()->join(
            'procedures',
            'procedure_requests.procedure_id',
            '=',
            'procedures.id'
        );

        if ($from && $to) {
            $query->whereBetween('procedure_requests.created_at', [$from, $to]);
        }
        if ($type) {
            $query->where('procedures.name', $type); // Corregido
        }

        $data = $query->selectRaw('COALESCE(procedures.name, "Sin Trámite") as name, COUNT(*) as total') // Corregido
            ->groupBy('procedures.name') // Corregido
            ->orderBy('procedures.name') // Corregido
            ->get();

        return response()->json($data);
    }

    /**
     * Exporta los resultados a PDF.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function exportPdf(Request $request): Response
    {
        $query = ProcedureRequest::with(['user', 'procedure']);

        if ($request->filled('from') && $request->filled('to')) {
            $query->whereBetween('created_at', [$request->from, $request->to]);
        }
        if ($request->filled('type')) {
            $query->whereHas('procedure', function ($q) use ($request) {
                $q->where('name', $request->type); // Corregido
            });
        }
        $requests = $query->get(); // [cite: 236-239]

        $pdf = Pdf::loadView('union.reports.exports', ['requests' => $requests]); // [cite: 288-291]
        return $pdf->download('procedures_report.pdf'); // Corregido
    }

    /**
     * Exporta los resultados a Excel.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function exportExcel(Request $request): \Symfony\Component\HttpFoundation\BinaryFileResponse
    {
        // Pasa los filtros al Export. Tu clase ReportesExport debe ser actualizada
        // para manejar 'name' en lugar de 'nombre'.
        $filters = $request->only(['from', 'to', 'type']);
        return Excel::download(new ReportesExport($filters), 'procedures_report.xlsx'); // Corregido
    }

    /**
     * Exporta los resultados a CSV.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function exportCsv(Request $request): \Symfony\Component\HttpFoundation\BinaryFileResponse
    {
        $filters = $request->only(['from', 'to', 'type']);
        return Excel::download(new ReportesExport($filters), 'procedures_report.csv'); // Corregido
    }
}