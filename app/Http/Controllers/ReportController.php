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
* Versión: 4.0 (Exportación por pestañas + refactor final)
*
* Fecha de mantenimiento: 24/11/2025
* Folio de mantenimiento: [Tu Folio]
* Tipo de mantenimiento: Correctivo y Perfectivo
* Descripción del mantenimiento:
* - Exportación dinámica según pestaña seleccionada.
* - Eliminación definitiva de filtros.
* - Limpieza y optimización de queries.
* - PDF/Excel/Word convertidos a formato tabular.
* Responsable: [Tu Nombre]
* Revisor: QA SINDISOFT
* ===========================================================
*/

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Procedure;
use App\Models\ProcedureRequest;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ReportesExport;
use PhpOffice\PhpWord\PhpWord;

class ReportController extends Controller
{
    /**
     * Panel principal de reportes sindicales.
     */
    public function index(Request $request): View
    {
        // Cargar solicitudes completas
        $requests = ProcedureRequest::with(['user', 'procedure'])
            ->orderByDesc('created_at')
            ->get();

        /* =============================
           KPIs solicitados en RF-06
        ============================= */
        $workers_attended = $requests->pluck('user_id')->unique()->count();

        $hombres      = User::where('gender', 'H')->count();
        $mujeres      = User::where('gender', 'M')->count();
        $no_definido  = User::where('gender', 'ND')->count();
        $no_dice      = User::where('gender', 'X')->count();


        $completed = ProcedureRequest::where('status', 'completed')->count();
        $pending   = ProcedureRequest::where('status', 'pending')->count();

        $avg_time = ProcedureRequest::selectRaw("AVG(DATEDIFF(updated_at, created_at)) as avg_days")
            ->value('avg_days');
        $avg_time = round($avg_time ?? 0, 1);

        // Estadísticas por tipo de trámite
        $statistics = $requests
            ->groupBy(fn($req) => $req->procedure->name ?? 'Sin Trámite')
            ->map->count();

        return view('union.reports.index', [
            'requests'         => $requests,
            'statistics'       => $statistics,
            'workers_attended' => $workers_attended,
            'hombres'          => $hombres,
            'mujeres'          => $mujeres,
            'completed'        => $completed,
            'pending'          => $pending,
            'avg_time'         => $avg_time,
            'no_definido'   => $no_definido,
            'no_dice'       => $no_dice,
        ]);
    }

    /**
     * Datos JSON para Chart.js (Trámites por nombre).
     */
    public function getChartData(): JsonResponse
    {
        $data = ProcedureRequest::query()->join(
            'procedures',
            'procedure_requests.procedure_id',
            '=',
            'procedures.id'
        )
            ->selectRaw('COALESCE(procedures.name, "Sin Trámite") as name, COUNT(*) as total')
            ->groupBy('procedures.name')
            ->orderBy('procedures.name')
            ->get();

        return response()->json($data);
    }

    /**
     * Exportación PDF por pestaña.
     */
    public function exportPdf(Request $request): Response
    {
        $tab = $request->query('tab', 'gender'); // pestaña activa

        switch ($tab) {

            case 'gender':
                $data = [
                    ['Género', 'Cantidad'],
                    ['Hombres',     User::where('gender', 'H')->count()],
                    ['Mujeres',     User::where('gender', 'M')->count()],
                    ['No definido', User::where('gender', 'ND')->count()],
                    ['No dice',     User::where('gender', 'X')->count()],
                ];

                $view = 'union.reports.exports.gender';
                break;

            case 'status':
                $data = [
                    ['Estado', 'Cantidad'],
                    ['Completados', ProcedureRequest::where('status', 'completed')->count()],
                    ['Pendientes', ProcedureRequest::where('status', 'pending')->count()],
                ];
                $view = 'union.reports.exports.status';
                break;

            case 'types':
                $stats = ProcedureRequest::with('procedure')->get()
                    ->groupBy(fn($r) => $r->procedure->name ?? 'Sin Trámite')
                    ->map->count();

                $data = $stats;
                $view = 'union.reports.exports.types';
                break;

            case 'table':
            default:
                $data = ProcedureRequest::with(['user', 'procedure'])->get();
                $view = 'union.reports.exports.table';
                break;
        }

        $pdf = Pdf::loadView($view, ['data' => $data]);
        return $pdf->download("reporte_$tab.pdf");
    }

    /**
     * Exportación Excel por pestaña.
     */
    public function exportExcel(Request $request)
    {
        $tab = $request->query('tab', 'gender');

        return Excel::download(new ReportesExport($tab), "reporte_$tab.xlsx");
    }

    /**
     * Exportación Word por pestaña.
     */
    public function exportWord(Request $request)
    {
        $tab = $request->query('tab', 'gender');

        $phpWord = new PhpWord();
        $section = $phpWord->addSection();

        $section->addTitle("Reporte de $tab", 1);

        switch ($tab) {

            case 'gender':
                $section->addText("Hombres     : " . User::where('gender', 'H')->count());
                $section->addText("Mujeres     : " . User::where('gender', 'M')->count());
                $section->addText("No definido : " . User::where('gender', 'ND')->count());
                $section->addText("No dice     : " . User::where('gender', 'X')->count());

                break;

            case 'status':
                $section->addText("Completados: " . ProcedureRequest::where('status', 'completed')->count());
                $section->addText("Pendientes: " . ProcedureRequest::where('status', 'pending')->count());
                break;

            case 'types':
                $stats = ProcedureRequest::with('procedure')->get()
                    ->groupBy(fn($r) => $r->procedure->name ?? 'Sin Trámite')
                    ->map->count();

                foreach ($stats as $name => $total) {
                    $section->addText("$name: $total");
                }
                break;

            case 'table':
            default:
                $requests = ProcedureRequest::with(['user', 'procedure'])->get();
                foreach ($requests as $req) {
                    $section->addText("Trámite: " . ($req->procedure->name ?? '—'));
                    $section->addText("Usuario: " . ($req->user->name ?? '—'));
                    $section->addText("Estado : " . $req->status);
                    $section->addText("-----------------------------");
                }
                break;
        }

        $fileName = "reporte_$tab.docx";
        $temp = tempnam(sys_get_temp_dir(), 'word');
        $phpWord->save($temp, 'Word2007');

        return response()->download($temp, $fileName)->deleteFileAfterSend(true);
    }
}
