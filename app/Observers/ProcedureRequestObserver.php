<?php

namespace App\Observers;

use App\Models\ProcedureRequest;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;

class ProcedureRequestObserver
{
    public function created(ProcedureRequest $model): void
    {
        ActivityLog::create([
            'user_id'    => Auth::id() ?? $model->user_id ?? null,
            'module'     => 'Solicitudes de trámite',
            'action'     => sprintf(
                "Creó la solicitud #%s del trámite %s",
                $model->id,
                $model->procedure->name ?? $model->procedure_name ?? "N/D"
            ),
            'ip_address' => request()->ip() ?? 'system',
        ]);
    }

    public function updated(ProcedureRequest $model): void
    {
        ActivityLog::create([
            'user_id'    => Auth::id() ?? $model->user_id ?? null,
            'module'     => 'Solicitudes de trámite',
            'action'     => sprintf(
                "Actualizó la solicitud #%s (estado: %s)",
                $model->id,
                $model->status ?? 'N/D'
            ),
            'ip_address' => request()->ip() ?? 'system',
        ]);
    }

    public function deleted(ProcedureRequest $model): void
    {
        ActivityLog::create([
            'user_id'    => Auth::id() ?? null,
            'module'     => 'Solicitudes de trámite',
            'action'     => "Eliminó la solicitud #{$model->id}",
            'ip_address' => request()->ip() ?? 'system',
        ]);
    }
}
