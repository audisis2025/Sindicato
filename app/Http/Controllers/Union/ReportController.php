<?php

namespace App\Http\Controllers\Union;

use App\Http\Controllers\Controller;
use App\Models\Procedure;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ReportesExport;
use Carbon\Carbon;

class ReportController extends Controller
{
    /**
     * Panel principal de reportes (agrupa y filtra por nombre del trámite).
     */
    public function index(Request $request)
    {
        $from = $request->input('from');
        $to   = $request->input('to');
        $type = $request->input('type'); // aquí será un nombre de trámite
        $filters = compact('from', 'to', 'type');

        // Distintos nombres de trámites para el <select>
        $tipos = Procedure::query()
            ->select('nombre')
            ->distinct()
            ->orderBy('nombre')
            ->pluck('nombre')
            ->toArray();

        $query = Procedure::with('user');

        if ($from && $to) {
            $query->whereBetween('created_at', [$from, $to]);
        }

        // 🔸 Filtrar por nombre de trámite si viene 'type'
        if ($type) {
            $query->where('nombre', $type);
        }

        $tramites = $query->orderByDesc('created_at')->get();

        // 🔹 Estadísticas por nombre (cantidad por tipo de trámite = nombre)
        $estadisticas = $tramites
            ->groupBy(fn ($t) => $t->nombre ?? 'Sin nombre')
            ->map->count();

        return view('union.reports.index', [
            'tramites'     => $tramites,
            'estadisticas' => $estadisticas,
            'filters'      => $filters,
            'tipos'        => $tipos,
        ]);
    }

    /**
     * Datos JSON para Chart.js (conteo por nombre de trámite).
     * Soporta mismos filtros que la vista (from, to, type=nombre).
     */
    public function getChartData(Request $request)
    {
        $from = $request->input('from');
        $to   = $request->input('to');
        $type = $request->input('type');

        $q = Procedure::query();

        if ($from && $to) {
            $q->whereBetween('created_at', [$from, $to]);
        }
        if ($type) {
            $q->where('nombre', $type);
        }

        // nombre = “tipo de trámite”
        $datos = $q->selectRaw('COALESCE(nombre, "Sin nombre") as nombre, COUNT(*) as total')
                   ->groupBy('nombre')
                   ->orderBy('nombre')
                   ->get();

        return response()->json($datos);
    }

    public function exportPdf(Request $request)
    {
        $q = Procedure::with('user');
        if ($request->filled('from') && $request->filled('to')) {
            $q->whereBetween('created_at', [$request->from, $request->to]);
        }
        if ($request->filled('type')) {
            $q->where('nombre', $request->type);
        }
        $tramites = $q->get();

        $pdf = Pdf::loadView('union.reports.exports', compact('tramites'));
        return $pdf->download('reporte_tramites.pdf');
    }

    public function exportExcel(Request $request)
    {
        // Tu ReportesExport puede leer los mismos filtros de $request si lo deseas.
        return Excel::download(new ReportesExport, 'reporte_tramites.xlsx');
    }

    public function exportCsv(Request $request)
    {
        return Excel::download(new ReportesExport, 'reporte_tramites.csv');
    }
}
