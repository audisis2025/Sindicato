<?php

/**
 * ===========================================================
 * Nombre de la clase: ReportesExport
 * Descripción: Clase de exportación para generar reportes sindicales
 * en formato Excel y CSV, conforme al estándar PRO-Laravel V3.2.
 * Fecha de creación: 04/11/2025
 * Elaboró: Iker Piza
 * Versión: 1.0
 * Tipo de mantenimiento: Creación inicial.
 * Descripción del mantenimiento: Implementación de exportación institucional
 * de trámites sindicales (RF20) con encabezado, filtros y formato tabular.
 * Responsable: Iker Piza
 * Revisor: QA SINDISOFT
 * ===========================================================
 */

namespace App\Exports;

use App\Models\SolicitudTramite;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class ReportesExport implements FromView, WithTitle, ShouldAutoSize
{
    protected $filters;

    /**
     * Constructor que recibe los filtros del reporte.
     */
    public function __construct(array $filters = [])
    {
        $this->filters = $filters;
    }

    /**
     * Generar la vista que alimentará el archivo Excel/CSV.
     */
    public function view(): View
    {
        $query = SolicitudTramite::with(['trabajador', 'tramite']);

        if (!empty($this->filters['from']) && !empty($this->filters['to'])) {
            $query->whereBetween('created_at', [
                $this->filters['from'],
                $this->filters['to']
            ]);
        }

        if (!empty($this->filters['type'])) {
            $query->where('tipo_tramite', $this->filters['type']);
        }

        $solicitudes = $query->orderBy('created_at', 'desc')->get();

        return view('union.reports.exports', [
            'solicitudes' => $solicitudes,
            'filters' => $this->filters,
        ]);
    }

    /**
     * Definir título de la hoja de Excel.
     */
    public function title(): string
    {
        return 'Reporte Sindical';
    }
}

