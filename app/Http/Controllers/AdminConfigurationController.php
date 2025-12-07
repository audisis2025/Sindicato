<?php
/*
* ===========================================================
* Nombre de la clase: AdminConfigurationController.php
* Descripción de la clase: Gestiona la configuración del sistema, respaldos y bitácoras
* para el rol de Administrador.
* Fecha de creación: 02/11/2025
* Elaboró: Iker Piza
* Fecha de liberación: 10/11/2025
* Autorizó: Líder Técnico
* Versión: 2.0
*
* Fecha de mantenimiento: 10/11/2025
* Folio de mantenimiento: [Tu Folio]
* Tipo de mantenimiento: Perfectivo
* Descripción del mantenimiento: Se aplica middleware de administrador, se añaden tipos
* de retorno, se refactoriza la bitácora para usar la tabla 'activity_logs'
* y se cumplen los estándares del Manual PRO-Laravel V3.2.
* Responsable: [Tu Nombre]
* Revisor: [Tu Revisor]
* ===========================================================
*/

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog; // Importamos el nuevo modelo
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage; // Importamos Storage
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Carbon\Carbon;
use Illuminate\Support\Str;


class AdminConfigurationController extends Controller // [cite: 887-890]
{
    /**
     * Aplica el middleware de administrador a todos los métodos.
     */
    public function __construct()
    {
        // Esto aplica el middleware 'isAdmin' que debiste registrar
        // en tu Kernel.php. Reemplaza todas las validaciones manuales.
        $this->middleware('auth'); // Primero aseguramos que esté logueado
        $this->middleware('isAdmin'); // Luego que sea administrador
    }

    /**
     * Muestra el panel de configuración.
     *
     * @return \Illuminate\View\View
     */
    public function index(): View // [cite: 200, 217-218]
    {
        return view('admin.configuration');
    }

    /**
     * Actualiza la configuración y registra en bitácoras.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request): RedirectResponse // [cite: 200, 214-215]
    {
        $validated = $request->validate([
            'app_name' => 'required|string|max:100',
            'admin_email' => 'nullable|email|max:100',
            'session_timeout' => 'required|integer|min:5|max:120',
        ]);

        // Simulación de guardar configuración
        Config::set('app.name', $validated['app_name']);
        Config::set('mail.from.address', $validated['admin_email']);
        Session::put('session_timeout', $validated['session_timeout']);

        $user = Auth::user(); //
        $rol = $user->rol ?? 'desconocido';

        // 1. Escritura en archivo log (como lo tenías)
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

        if (!is_dir(storage_path('logs'))) {
            mkdir(storage_path('logs'), 0755, true);
        }
        file_put_contents(storage_path('logs/configuracion_sistema.log'), $logEntry, FILE_APPEND);

        // 2. Escritura en la nueva tabla 'activity_logs'
        ActivityLog::create([
            'user_id' => $user->id,
            'action' => "Actualizó la configuración del sistema.",
            'module' => "Configuración",
            'ip_address' => $request->ip()
        ]);

        return back()->with('status', '✅ Configuración actualizada correctamente.'); // [cite: 1291-1294]
    }

    /**
     * Simula un respaldo de la base de datos.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function backup(Request $request): RedirectResponse
    {
        $filename = 'backup_' . now()->format('Ymd_His') . '.sql';
        $content = '-- Simulated backup file for SINDISOFT --';

        // Usamos el Facade Storage (más limpio que mkdir/file_put_contents)
        // Esto guarda en storage/app/backups/
        Storage::put('backups/' . $filename, $content);

        $user = Auth::user();
        $rol = $user->rol ?? 'desconocido';

        // 1. Escritura en archivo log
        $logEntry = sprintf(
            "[%s] Usuario: %s (%s) generó un respaldo del sistema → Archivo: %s%s",
            now()->format('Y-m-d H:i:s'),
            $user->name ?? 'Sin nombre',
            $rol,
            $filename,
            PHP_EOL
        );
        file_put_contents(storage_path('logs/configuracion_sistema.log'), $logEntry, FILE_APPEND);

        // 2. Escritura en la nueva tabla 'activity_logs'
        ActivityLog::create([
            'user_id' => $user->id,
            'action' => "Generó un respaldo: " . $filename,
            'module' => "Respaldos",
            'ip_address' => $request->ip()
        ]);

        return back()->with('status', 'Respaldo generado correctamente: ' . $filename);
    }

    /**
     * Muestra la bitácora de configuración.
     *
     * @return \Illuminate\View\View
     */
    public function logs(Request $request): View
    {
        $query = ActivityLog::query();

        // CONVERSIÓN DE FORMATO dd/mm/aaaa → Y-m-d
        $dateFrom = null;
        $dateTo   = null;

        if ($request->filled('date_from')) {
            try {
                $dateFrom = Carbon::createFromFormat('d/m/Y', $request->date_from)->format('Y-m-d');
            } catch (\Exception $e) {
            }
        }

        if ($request->filled('date_to')) {
            try {
                $dateTo = Carbon::createFromFormat('d/m/Y', $request->date_to)->format('Y-m-d');
            } catch (\Exception $e) {
            }
        }

        // FILTRO POR FECHA INICIO
        if ($dateFrom) {
            $query->whereDate('created_at', '>=', $dateFrom);
        }

        // FILTRO POR FECHA FIN
        if ($dateTo) {
            $query->whereDate('created_at', '<=', $dateTo);
        }

        // FILTRO POR PALABRA CLAVE (Buscar en módulo y acción)
        if ($request->filled('keyword')) {
            $keyword = $request->keyword;
            $query->where(function ($q) use ($keyword) {
                $q->where('module',  'LIKE', "%{$keyword}%")
                    ->orWhere('action', 'LIKE', "%{$keyword}%");
            });
        }

        $logs = $query->orderBy('created_at', 'desc')->get();

        return view('admin.logs', compact('logs'));
    }




    /**
     * Muestra la configuración de recordatorios (RF-04).
     *
     * @return \Illuminate\View\View
     */
    public function reminders(): View
    {
        $config = \App\Models\ReminderSetting::first();

        if (!$config) {
            // Crear registro inicial si no existe
            $config = \App\Models\ReminderSetting::create([
                'enabled' => 0,
                'channel' => 'email',
                'interval_days' => 2,
                'base_message' => 'Tienes un paso pendiente en tu trámite.'
            ]);
        }

        // Reglas dummy (hasta que hagas su propia tabla)
        $rules = collect([
            (object)['id' => 1, 'label' => 'Recordar pasos pendientes', 'value' => 'Cada 2 días'],
            (object)['id' => 2, 'label' => 'Aviso previo a fecha límite', 'value' => '3 días antes'],
        ]);

        return view('admin.reminders', compact('config', 'rules'));
    }



    /**
     * Guarda la configuración de recordatorios (RF-04).
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateReminders(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'enabled'       => 'required|in:0,1',
            'channel'       => 'required|in:email,inapp',
            'interval_days' => 'required|integer|min:1|max:30',
            'base_message'  => 'required|string|max:500',
        ]);

        $config = \App\Models\ReminderSetting::first();

        if (!$config) {
            $config = new \App\Models\ReminderSetting();
        }

        $config->update($validated);

        ActivityLog::create([
            'user_id' => Auth::id(),
            'module'  => 'Configuración',
            'action'  => 'Actualizó configuración de recordatorios',
            'ip_address' => $request->ip()
        ]);

        return redirect()
            ->route('admin.reminders')
            ->with('success', 'Configuración de recordatorios actualizada correctamente.');
    }
    public function clearLogs()
    {
        ActivityLog::truncate();

        return back()->with('status', 'Bitácora eliminada correctamente.');
    }
    public function exportWord()
    {
        $logs = ActivityLog::orderBy('created_at', 'desc')->get();

        $filename = "Bitacora_Sistema_" . now()->format('Ymd_His') . ".doc";

        // Encabezado inicial del documento
        $content  = "<h2>Bitácora del Sistema</h2>";
        $content .= "<p>Generado el: " . now()->timezone('America/Mexico_City')->format('d/m/Y H:i:s') . "</p>";
        $content .= "<hr>";

        // Tabla
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

        foreach ($logs as $log) {
            $content .= "
            <tr>
                <td>" . $log->created_at->timezone('America/Mexico_City')->format('d/m/Y H:i:s') . "</td>
                <td>" . ($log->module ?? '-') . "</td>
                <td>" . ($log->action ?? '-') . "</td>
                <td>" . ($log->user->name ?? 'Sistema') . "</td>
            </tr>
        ";
        }

        $content .= "</tbody></table>";

        // Headers para Word
        return response($content)
            ->header('Content-Type', 'application/msword')
            ->header('Content-Disposition', "attachment; filename=\"{$filename}\"");
    }
}
