<?php
/*
* Nombre de la clase           : WorkerNotificationController.php
* Descripción de la clase      : Controlador encargado de la visualización y gestión de notificaciones del trabajador, incluyendo el marcado de notificaciones como leídas.
* Fecha de creación            : 30/09/2025
* Elaboró                      : Iker Piza
* Fecha de liberación          : 14/12/2025
* Autorizó                     : Salvador Monroy
* Versión                      : 1.2
* Fecha de mantenimiento       :
* Folio de mantenimiento       :
* Tipo de mantenimiento        : 
* Descripción del mantenimiento: 
* Responsable                  :
* Revisor                      : 
*/


namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\SystemNotification;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class WorkerNotificationController extends Controller
{
	public function index(): View
	{
		$user = Auth::user();

		$notifications = SystemNotification::where('user_id', $user->id)
			->orderBy('created_at', 'desc')
			->get();

		return view('worker.notifications', [
			'notifications_list' => $notifications,
		]);
	}

	public function markAsRead(string $id): RedirectResponse
	{
		/** @var \App\Models\User $user */
		$user = Auth::user();

		$notification = $user->systemNotifications()
			->where('id', $id)
			->firstOrFail();

		$notification->update([
			'status' => 'read',
			'read_at' => now(),
		]);

		return back()->with('success', 'Notificación marcada como leída.');
	}
}
