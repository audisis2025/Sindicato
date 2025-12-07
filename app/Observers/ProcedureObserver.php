<?php

namespace App\Observers;

use App\Models\Procedure;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;

class ProcedureObserver
{
    public function created(Procedure $procedure): void
    {
        ActivityLog::create([
            'user_id'    => Auth::id() ?? $procedure->created_by ?? null,
            'module'     => 'Trámites (plantillas)',
            'action'     => "Creó el trámite plantilla: " . $this->getProcedureName($procedure),
            'ip_address' => request()->ip() ?? 'system',
        ]);
    }

    public function updated(Procedure $procedure): void
    {
        ActivityLog::create([
            'user_id'    => Auth::id() ?? $procedure->updated_by ?? null,
            'module'     => 'Trámites (plantillas)',
            'action'     => "Actualizó el trámite plantilla: " . $this->getProcedureName($procedure),
            'ip_address' => request()->ip() ?? 'system',
        ]);
    }

    public function deleted(Procedure $procedure): void
    {
        ActivityLog::create([
            'user_id'    => Auth::id() ?? null,
            'module'     => 'Trámites (plantillas)',
            'action'     => "Eliminó el trámite plantilla: " . $this->getProcedureName($procedure),
            'ip_address' => request()->ip() ?? 'system',
        ]);
    }


    private function getProcedureName(Procedure $procedure): string
    {
        return $procedure->name
            ?? $procedure->titulo
            ?? $procedure->procedure_name
            ?? "N/D";
    }
}
