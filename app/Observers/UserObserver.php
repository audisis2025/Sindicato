<?php

namespace App\Observers;

use App\Models\User;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;

class UserObserver
{
    public function created(User $user)
    {
        if (!Auth::check()) return; // ← evitar error

        ActivityLog::create([
            'user_id'    => Auth::id(),
            'module'     => 'Usuarios',
            'action'     => "Creó al usuario {$user->name}",
            'ip_address' => request()->ip()
        ]);
    }

    public function updated(User $user)
    {
        if (!Auth::check()) return;  // ← evitar error

        ActivityLog::create([
            'user_id'    => Auth::id(),
            'module'     => 'Usuarios',
            'action'     => "Actualizó datos del usuario {$user->name}",
            'ip_address' => request()->ip()
        ]);
    }

    public function deleted(User $user)
    {
        if (!Auth::check()) return; // ← evitar error

        ActivityLog::create([
            'user_id'    => Auth::id(),
            'module'     => 'Usuarios',
            'action'     => "Eliminó al usuario {$user->name}",
            'ip_address' => request()->ip()
        ]);
    }
}
