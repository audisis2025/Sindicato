<?php


namespace App\Exports;

use App\Models\User;
use App\Models\ProcedureRequest;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ReportesExport implements FromArray, WithHeadings
{
    protected string $tab;

    public function __construct(string $tab)
    {
        $this->tab = $tab;
    }

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

    public function array(): array
    {
        switch ($this->tab) {

            case 'gender':
                return [
                    ['Hombres',     User::where('gender', 'H')->count()],
                    ['Mujeres',     User::where('gender', 'M')->count()],
                    ['No definido', User::where('gender', 'ND')->count()],
                    ['No dice',     User::where('gender', 'X')->count()],
                ];

            case 'status':
                return [
                    ['Completados', ProcedureRequest::where('status', 'completed')->count()],
                    ['Pendientes',  ProcedureRequest::where('status', 'pending')->count()],
                ];

            case 'types':
                $stats = ProcedureRequest::with('procedure')->get()
                    ->groupBy(fn($r) => $r->procedure->name ?? 'Sin Trámite')
                    ->map->count();

                $rows = [];
                foreach ($stats as $name => $total) {
                    $rows[] = [$name, $total];
                }
                return $rows;

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
