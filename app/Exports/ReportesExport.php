<?php

/**
 * ===========================================================
 * Nombre de la clase: ReportesExport
 * Descripción: Exporta tablas sindicales (por pestaña) a Excel
 * conforme al estándar PRO-Laravel V3.2.
 * Fecha de creación original: 04/11/2025
 * Mantenimiento: Refactor total para exportación dinámica por pestaña.
 * Versión: 3.0 (24/11/2025)
 * Responsable: Iker Piza
 * Revisor: QA SINDISOFT
 * ===========================================================
 */

namespace App\Exports;

use App\Models\User;
use App\Models\ProcedureRequest;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ReportesExport implements FromArray, WithHeadings
{
    protected string $tab;

    /**
     * Recibe el nombre de la pestaña activa.
     */
    public function __construct(string $tab)
    {
        $this->tab = $tab;
    }

    /**
     * ENCABEZADOS SEGÚN PESTAÑA
     */
    public function headings(): array
    {
        switch ($this->tab) {

            case 'gender':
                return ['Género', 'Cantidad'];

            case 'status':
                return ['Estado', 'Cantidad'];

            case 'types':
                return ['Tipo de trámite', 'Cantidad'];

            case 'table':
            default:
                return ['#', 'Trabajador', 'Trámite', 'Estado', 'Fecha'];
        }
    }

    /**
     * FILAS SEGÚN PESTAÑA
     */
    public function array(): array
    {
        switch ($this->tab) {

            /* ===========================================================
               TAB: GÉNERO (H, M, ND, X)
            =========================================================== */
            case 'gender':
                return [
                    ['Hombres',     User::where('gender', 'H')->count()],
                    ['Mujeres',     User::where('gender', 'M')->count()],
                    ['No definido', User::where('gender', 'ND')->count()],
                    ['No dice',     User::where('gender', 'X')->count()],
                ];

            /* ===========================================================
               TAB: ESTADOS
            =========================================================== */
            case 'status':
                return [
                    ['Completados', ProcedureRequest::where('status', 'completed')->count()],
                    ['Pendientes',  ProcedureRequest::where('status', 'pending')->count()],
                ];

            /* ===========================================================
               TAB: TIPOS DE TRÁMITE
            =========================================================== */
            case 'types':
                $stats = ProcedureRequest::with('procedure')->get()
                    ->groupBy(fn($r) => $r->procedure->name ?? 'Sin Trámite')
                    ->map->count();

                $rows = [];
                foreach ($stats as $name => $total) {
                    $rows[] = [$name, $total];
                }
                return $rows;

            /* ===========================================================
               TAB: TABLA COMPLETA
            =========================================================== */
            case 'table':
            default:
                $requests = ProcedureRequest::with(['user', 'procedure'])->get();

                $rows = [];
                foreach ($requests as $i => $r) {
                    $rows[] = [
                        $i + 1,
                        $r->user->name ?? '—',
                        $r->procedure->name ?? '—',
                        $r->status ?? '—',
                        optional($r->created_at)->format('d/m/Y'),
                    ];
                }
                return $rows;
        }
    }
}
