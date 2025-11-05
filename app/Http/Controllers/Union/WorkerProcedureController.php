<?php

namespace App\Http\Controllers\Union;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\SolicitudTramite;
use App\Models\Procedure;
use App\Models\TramitePaso;
use App\Models\News;
use App\Models\Notification;


/**
 * ===========================================================
 * Nombre de la clase: WorkerProcedureController
 * Descripción: Controla el panel del trabajador con sus trámites
 * activos, finalizados y disponibles para iniciar.
 * Fecha de creación: 07/11/2025
 * Elaboró: Iker Piza
 * Versión: 1.4
 * Tipo de mantenimiento: Homogeneización y corrección de relaciones.
 * ===========================================================
 */
class WorkerProcedureController extends Controller
{
    /**
     * 🏠 Mostrar el panel principal del trabajador.
     */
    public function index()
    {
        $userId = Auth::id();

        // 🟠 Trámites activos
        $tramitesActivos = SolicitudTramite::with('tramite')
            ->where('user_id', $userId)
            ->whereIn('estado', ['Pendiente', 'En Progreso'])
            ->latest()
            ->get();

        // 🟢 Trámites finalizados
        $tramitesFinalizados = SolicitudTramite::with('tramite')
            ->where('user_id', $userId)
            ->whereIn('estado', ['Completado', 'Rechazado'])
            ->latest()
            ->get();

        $tramitesDisponibles = Procedure::whereNotIn('id', function ($query) use ($userId) {
            $query->select('tramite_id')
                ->from('solicitudes_tramite')
                ->where('user_id', $userId)
                ->whereNotIn('estado', ['Completado', 'Rechazado', 'Cancelado']);
        })
            ->orderBy('nombre')
            ->get();


        return view('worker.index', compact(
            'tramitesActivos',
            'tramitesFinalizados',
            'tramitesDisponibles'
        ));
    }
    public function completeStep($solicitudId, $pasoId)
    {
        $solicitud = SolicitudTramite::with('tramite')->findOrFail($solicitudId);
        $paso = TramitePaso::findOrFail($pasoId);

        // 🔒 Validar secuencia
        if ($solicitud->paso_actual != $paso->orden) {
            return back()->with('error', '⚠️ Debes completar los pasos en orden.');
        }

        // ✅ Avanzar
        $solicitud->paso_actual += 1;

        // Si ya terminó todos los pasos → Completado
        if ($solicitud->paso_actual > $solicitud->tramite->numero_pasos) {
            $solicitud->estado = 'Completado';
        }

        $solicitud->save();

        return back()->with('success', '✅ Paso completado correctamente.');
    }

    /**
     * 🚀 Iniciar un nuevo trámite.
     */
    public function start($id)
    {
        $userId = Auth::id();
        $procedure = Procedure::findOrFail($id);

        // 🔒 Verificar si el trabajador ya tiene ese trámite activo
        $existe = SolicitudTramite::where('user_id', $userId)
            ->where('tramite_id', $id)
            ->whereIn('estado', ['Pendiente', 'En Progreso'])
            ->exists();

        if ($existe) {
            return redirect()
                ->route('worker.index')
                ->with('error', '⚠️ Ya tienes un trámite activo de este tipo.');
        }

        // 🆕 Crear nueva solicitud
        $solicitud = SolicitudTramite::create([
            'user_id'     => $userId,
            'tramite_id'  => $procedure->id,
            'paso_actual' => 1,
            'estado'      => 'Pendiente',
        ]);

        // ✅ Redirigir al detalle del trámite (primer paso)
        return redirect()
            ->route('worker.procedures.show', $solicitud->id)
            ->with('success', '🚀 Trámite iniciado correctamente.');
    }

    /**
     * 👣 Mostrar los pasos del trámite actual.
     */
    public function show($id)
    {
        $solicitud = SolicitudTramite::with(['tramite.pasos'])
            ->where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        return view('worker.procedure-show', compact('solicitud'));
    }

    /**
     * 📤 Subir un archivo correspondiente a un paso.
     */
    public function upload(Request $request, $solicitudId, $pasoId)
    {
        $request->validate([
            'archivo' => 'required|file|max:10240', // Máx 10MB
        ]);

        $solicitud = SolicitudTramite::findOrFail($solicitudId);
        $paso = TramitePaso::findOrFail($pasoId);

        $path = $request->file('archivo')->store('tramites/subidos', 'public');

        // Guardar ruta en el campo correspondiente (puede ser formato_path o un campo nuevo)
        $paso->update(['formato_path' => $path]);

        return back()->with('success', '📤 Archivo subido correctamente.');
    }
    /**
     * ❌ Cancelar un trámite activo del trabajador.
     */
    public function cancel($id)
    {
        $solicitud = SolicitudTramite::where('id', $id)
            ->where('user_id', Auth::id())
            ->whereIn('estado', ['Pendiente', 'En Progreso'])
            ->firstOrFail();

        $solicitud->update(['estado' => 'Cancelado']);

        return redirect()
            ->route('worker.index')
            ->with('success', '❌ Trámite cancelado correctamente.');
    }
    /**
     * 👁️ Mostrar detalle completo de una solicitud de trámite.
     */
    public function showDetail($id)
    {
        $solicitud = SolicitudTramite::with(['tramite.pasos'])
            ->where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        return view('worker.request-show', compact('solicitud'));
    }



    /**
     * 📢 Mostrar convocatorias y noticias del sindicato.
     */
    public function showNews()
    {
        $news = News::where('estado', 'publicada')->latest()->get();
        return view('worker.news', compact('news'));
    }
    /**
     * 🔔 Mostrar todas las notificaciones del trabajador.
     */
    public function showNotifications()
    {
        $userId = Auth::id();
        $notifications = Notification::where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('worker.notifications', compact('notifications'));
    }

    /**
     * ✅ Marcar una notificación como leída.
     */
    public function markAsRead($id)
    {
        $notification = Notification::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $notification->update(['estado' => 'leida']);

        return back()->with('success', '✅ Notificación marcada como leída.');
    }
}
