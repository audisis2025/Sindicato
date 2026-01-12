<?php
/*
* ===========================================================
* Nombre de la clase: WorkerNotificationController
* Descripción de la clase: Controla la visualización y 
* actualización de notificaciones para trabajadores.
* Fecha de creación: 09/11/2025
* Elaboró: [Tu Nombre]
* Fecha de liberación: 12/11/2025
* Autorizó: Líder Técnico
* Versión: 1.0
*
* Fecha de mantenimiento: 11/12/2025
* Folio de mantenimiento: COR-002
* Tipo de mantenimiento: Correctivo
* Descripción del mantenimiento: Estandarización del controlador 
* y aplicación de Type Hinting según el Manual PRO-Laravel V4.0.
* Responsable: [Tu Nombre]
* Revisor: QA SINDISOFT
* ===========================================================
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
		]);

		return back()->with('success', 'Notificación marcada como leída.');
	}
}
