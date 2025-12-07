<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\SystemNotification;

class WorkerNotificationController extends Controller
{
    public function index()
    {
        // El $user = Auth::user(); no es estrictamente necesario,
        // pero lo dejaremos para mostrar la alternativa más segura:

        $user = Auth::user();

        // 🚨 CAMBIO CLAVE: Usar SystemNotification::where() en lugar de la relación
        $notifications = SystemNotification::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('worker.notifications', [
            'notifications_list' => $notifications
        ]);
    }

    public function markAsRead(string $id)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        $notification = $user
            ->systemNotifications()
            ->where('id', $id)
            ->firstOrFail();

        $notification->update([
            'status' => 'read'
        ]);

        return back()->with('success', 'Notificación marcada como leída.');
    }
}
