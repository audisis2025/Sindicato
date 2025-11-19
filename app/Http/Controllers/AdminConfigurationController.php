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

        return back()->with('status', '💾 Respaldo generado correctamente: ' . $filename);
    }

    /**
     * Muestra la bitácora de configuración.
     *
     * @return \Illuminate\View\View
     */
    // quita cualquier referencia a SystemLog

    public function logs(Request $request): View
    {
        $logPath = storage_path('logs/configuracion_sistema.log');

        $logs = file_exists($logPath)
            ? file($logPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES)
            : [];

        $from = $request->input('date_from');
        $to = $request->input('date_to');
        $keyword = $request->input('keyword');

        // Aplicar filtros si existen
        if ($keyword || $from || $to) {
            $logs = array_filter($logs, function ($line) use ($from, $to, $keyword) {
                $lineValid = true;

                // 1. Filtro por Palabra Clave
                if ($keyword && !Str::contains(strtolower($line), strtolower($keyword))) {
                    $lineValid = false;
                }

                // 2. Filtro por Fecha
                if ($lineValid && ($from || $to)) {
                    preg_match('/\[(.*?)\]/', $line, $matches);
                    $logDateStr = $matches[1] ?? null;

                    if (!$logDateStr) {
                        $lineValid = false;
                    } else {
                        $logDate = Carbon::parse($logDateStr)->startOfDay();

                        // Rango de fechas
                        if ($from && $to) {
                            $startDate = Carbon::parse($from)->startOfDay();
                            $endDate = Carbon::parse($to)->endOfDay();

                            if (!$logDate->isBetween($startDate, $endDate)) {
                                $lineValid = false;
                            }
                        }

                        // Solo una fecha (solo "desde")
                        else if ($from && !$to) {
                            $searchDate = Carbon::parse($from)->startOfDay();

                            if (!$logDate->isSameDay($searchDate)) {
                                $lineValid = false;
                            }
                        }
                    }
                }

                return $lineValid;
            });
        }

        // Invertir para mostrar los más nuevos primero
        $system_logs = array_reverse($logs);

        return view('admin.logs', compact('system_logs'));
    }
}
