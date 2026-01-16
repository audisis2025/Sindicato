<?php
/*
* Nombre de la clase           : AdminConfigurationController.php
* Descripción de la clase      : Controlador encargado de administrar la configuración del sistema, respaldos, bitácora y recordatorios.
* Fecha de creación            : 26/09/2025
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

use App\Http\Requests\Logs\ActivityLogFilterRequest;

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

    public function logs(ActivityLogFilterRequest $request): View
    {
        $data = $request->validated();

        $query = ActivityLog::query()->with('user');

        if (!empty($data['date_from']))
        {
            $query->whereDate('created_at', '>=', $data['date_from']);
        }

        if (!empty($data['date_to']))
        {
            $query->whereDate('created_at', '<=', $data['date_to']);
        }

        if (!empty($data['keyword']))
        {
            $keyword = $data['keyword'];

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
        $logs = ActivityLog::with('user')->orderBy('created_at', 'desc')->get();

        $filename = 'Bitacora_Sistema_' . now()->format('Ymd_His') . '.doc';

        $content  = '<html><head>';
        $content .= '<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">';
        $content .= '<style>
            body { font-family: Arial, sans-serif; font-size: 11pt; }
            h2 { color: #241178; }
            table { border-collapse: collapse; width: 100%; }
            th, td { border: 1px solid #000; padding: 6px; }
            th { background: #eeeeee; font-weight: bold; }
        </style>';
        $content .= '</head><body>';

        $content .= '<h2>Bitácora del Sistema</h2>';
        $content .= '<p>Generado el: ' .
            now()->timezone('America/Mexico_City')->format('d/m/Y H:i:s') .
            '</p>';
        $content .= '<hr>';

        $content .= '<table>
            <thead>
                <tr>
                    <th>Fecha / Hora</th>
                    <th>Módulo</th>
                    <th>Acción</th>
                    <th>Usuario</th>
                </tr>
            </thead>
            <tbody>';

        foreach ($logs as $log)
        {
            $content .= '<tr>
                <td>' . e($log->created_at->timezone('America/Mexico_City')->format('d/m/Y H:i:s')) . '</td>
                <td>' . e($log->module ?? '-') . '</td>
                <td>' . e($log->action ?? '-') . '</td>
                <td>' . e($log->user->name ?? 'Sistema') . '</td>
            </tr>';
        }

        $content .= '</tbody></table>';
        $content .= '</body></html>';

        return response(
                mb_convert_encoding($content, 'UTF-8', 'UTF-8')
            )
            ->header('Content-Type', 'application/msword; charset=UTF-8')
            ->header('Content-Disposition', "attachment; filename=\"{$filename}\"");
    }

}