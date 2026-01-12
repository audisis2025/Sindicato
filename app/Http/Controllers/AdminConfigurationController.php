<?php
/*
* ===========================================================
* Nombre de la clase: AdminConfigurationController
* Descripción de la clase: Controlador encargado de gestionar la configuración del sistema,
* respaldos y bitácoras para el rol de Administrador. 
* Fecha de creación: 02/11/2025
* Elaboró: Iker Piza 
* Fecha de liberación: 
* Autorizó: Líder Técnico 
* Versión: 2.1
*
* Fecha de mantenimiento: 11/12/2025
* Folio de mantenimiento: 
* Tipo de mantenimiento: Perfectivo
* Descripción del mantenimiento: Se aplica middleware de administrador, se añaden tipos de retorno 
* y se refactoriza la bitácora para usar la tabla 'activity_logs', cumpliendo con los estándares de codificación. [cite: 481]
* Responsable: 
* Revisor: 
* ===========================================================
*/

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\ReminderSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Carbon\Carbon;

class AdminConfigurationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('isAdmin');
    }

    public function index(): View
    {
        return view('admin.configuration');
    }

    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'app_name' => 'required|string|max:100',
            'admin_email' => 'nullable|email|max:100',
            'session_timeout' => 'required|integer|min:5|max:120',
        ]);

        Config::set('app.name', $validated['app_name']);
        Config::set('mail.from.address', $validated['admin_email']);
        Session::put('session_timeout', $validated['session_timeout']);

        $user = Auth::user();
        $rol = $user->rol ?? 'desconocido';

        $logEntry = sprintf(
            "[%s] Usuario: %s (%s) actualizó configuración → Nombre: %s | Email: %s | Timeout: %s min%s",
            now()->format('Y-m-d H:i:s'),
            $user->name ?? 'Sin nombre',
            $rol,
            $validated['app_name'],
            $validated['admin_email'] ?? 'N/A',
            $validated['session_timeout'],
            PHP_EOL
        );

        if (!is_dir(storage_path('logs')))
        {
            mkdir(storage_path('logs'), 0755, true);
        }

        file_put_contents(storage_path('logs/configuracion_sistema.log'), $logEntry, FILE_APPEND);

        ActivityLog::create([
            'user_id' => $user->id,
            'action' => 'Actualizó la configuración del sistema.',
            'module' => 'Configuración',
            'ip_address' => $request->ip()
        ]);

        return back()->with('status', 'Configuración actualizada correctamente.');
    }

    public function backup(Request $request): RedirectResponse
    {
        $filename = 'backup_' . now()->format('Ymd_His') . '.sql';
        $content = '-- Simulated backup file for SINDISOFT --';

        Storage::put('backups/' . $filename, $content);

        $user = Auth::user();
        $rol = $user->rol ?? 'desconocido';

        $logEntry = sprintf(
            "[%s] Usuario: %s (%s) generó un respaldo del sistema → Archivo: %s%s",
            now()->format('Y-m-d H:i:s'),
            $user->name ?? 'Sin nombre',
            $rol,
            $filename,
            PHP_EOL
        );

        file_put_contents(storage_path('logs/configuracion_sistema.log'), $logEntry, FILE_APPEND);

        ActivityLog::create([
            'user_id' => $user->id,
            'action' => 'Generó un respaldo: ' . $filename,
            'module' => 'Respaldos',
            'ip_address' => $request->ip()
        ]);

        return back()->with('status', 'Respaldo generado correctamente: ' . $filename);
    }

    public function logs(Request $request): View
    {
        $query = ActivityLog::query();

        $dateFrom = null;
        $dateTo = null;

        if ($request->filled('date_from'))
        {
            try
            {
                $dateFrom = Carbon::createFromFormat('d/m/Y', $request->date_from)->format('Y-m-d');
            }
            catch (\Exception $e)
            {
            }
        }

        if ($request->filled('date_to'))
        {
            try
            {
                $dateTo = Carbon::createFromFormat('d/m/Y', $request->date_to)->format('Y-m-d');
            }
            catch (\Exception $e)
            {
            }
        }

        if ($dateFrom)
        {
            $query->whereDate('created_at', '>=', $dateFrom);
        }

        if ($dateTo)
        {
            $query->whereDate('created_at', '<=', $dateTo);
        }

        if ($request->filled('keyword'))
        {
            $keyword = $request->keyword;

            $query->where(function ($q) use ($keyword)
            {
                $q->where('module', 'LIKE', "%{$keyword}%")
                    ->orWhere('action', 'LIKE', "%{$keyword}%");
            });
        }

        $logs = $query->orderBy('created_at', 'desc')->get();

        return view('admin.logs', compact('logs'));
    }

    public function reminders(): View
    {
        $config = ReminderSetting::first();

        if (!$config)
        {
            $config = ReminderSetting::create([
                'enabled' => 0,
                'channel' => 'email',
                'interval_days' => 2,
                'base_message' => 'Tienes un paso pendiente en tu trámite.',
            ]);
        }

        $rules = collect([
            (object)['id' => 1, 'label' => 'Recordar pasos pendientes', 'value' => 'Cada 2 días'],
            (object)['id' => 2, 'label' => 'Aviso previo a fecha límite', 'value' => '3 días antes'],
        ]);

        return view('admin.reminders', compact('config', 'rules'));
    }

    public function updateReminders(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'enabled' => 'required|in:0,1',
            'channel' => 'required|in:email,inapp',
            'interval_days' => 'required|integer|min:1|max:30',
            'base_message' => 'required|string|max:500',
        ]);

        $config = ReminderSetting::first();

        if (!$config)
        {
            $config = new ReminderSetting();
        }

        $config->update($validated);

        ActivityLog::create([
            'user_id' => Auth::id(),
            'module' => 'Configuración',
            'action' => 'Actualizó configuración de recordatorios',
            'ip_address' => $request->ip()
        ]);

        return redirect()
            ->route('admin.reminders')
            ->with('success', 'Configuración de recordatorios actualizada correctamente.');
    }

    public function clearLogs(): RedirectResponse
    {
        ActivityLog::truncate();

        return back()->with('status', 'Bitácora eliminada correctamente.');
    }

    public function exportWord(): Response
    {
        $logs = ActivityLog::orderBy('created_at', 'desc')->get();

        $filename = 'Bitacora_Sistema_' . now()->format('Ymd_His') . '.doc';

        $content = '<h2>Bitácora del Sistema</h2>';
        $content .= '<p>Generado el: ' . now()->timezone('America/Mexico_City')->format('d/m/Y H:i:s') . '</p>';
        $content .= '<hr>';

        $content .= "
        <table border='1' cellspacing='0' cellpadding='5' width='100%'>
            <thead>
                <tr style='background:#eeeeee; font-weight:bold;'>
                    <th>Fecha / Hora</th>
                    <th>Módulo</th>
                    <th>Acción</th>
                    <th>Usuario</th>
                </tr>
            </thead>
            <tbody>
        ";

        foreach ($logs as $log)
        {
            $content .= "
            <tr>
                <td>" . $log->created_at->timezone('America/Mexico_City')->format('d/m/Y H:i:s') . "</td>
                <td>" . ($log->module ?? '-') . "</td>
                <td>" . ($log->action ?? '-') . "</td>
                <td>" . ($log->user->name ?? 'Sistema') . "</td>
            </tr>
            ";
        }

        $content .= '</tbody></table>';

        return response($content)
            ->header('Content-Type', 'application/msword')
            ->header('Content-Disposition', "attachment; filename=\"{$filename}\"");
    }
}