<?php

/**
 * ===========================================================
 * File name: AdminConfigurationController.php
 * Description: Handles configuration management and system logs for administrators in SINDISOFT.
 * Creation date: 02/11/2025
 * Author: Iker Piza
 * Version: 1.3
 * Maintenance type: Correction and Security Hardening.
 * Description: Fixes undefined role access, adds role-based access validation,
 * and improves log safety and reliability.
 * ===========================================================
 */

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;

class AdminConfigurationController extends Controller
{
    /**
     * Display configuration panel.
     */
    public function index()
    {
        // 🔒 Solo administradores pueden acceder
        if (Auth::user()->rol !== 'administrador') {
            abort(403, 'Acceso denegado: Solo los administradores pueden acceder a esta sección.');
        }

        return view('admin.configuration');
    }

    /**
     * Save configuration changes and register them in the log file.
     */
    public function update(Request $request)
    {
        // 🔒 Solo administradores pueden actualizar
        if (Auth::user()->rol !== 'administrador') {
            abort(403, 'No tienes permisos para modificar la configuración del sistema.');
        }

        $validated = $request->validate([
            'app_name' => 'required|string|max:100',
            'admin_email' => 'nullable|email|max:100',
            'session_timeout' => 'required|integer|min:5|max:120',
        ]);

        // Guardar parámetros en sesión (simulado)
        Config::set('app.name', $validated['app_name']);
        Config::set('mail.from.address', $validated['admin_email']);
        Session::put('session_timeout', $validated['session_timeout']);

        // Registrar acción en bitácora
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

        // Crea carpeta si no existe
        if (!is_dir(storage_path('logs'))) {
            mkdir(storage_path('logs'), 0755, true);
        }

        file_put_contents(storage_path('logs/configuracion_sistema.log'), $logEntry, FILE_APPEND);

        return back()->with('status', '✅ Configuración actualizada correctamente.');
    }

    /**
     * Simulate database backup.
     */
    public function backup()
    {
        // 🔒 Solo administradores pueden hacer respaldos
        if (Auth::user()->rol !== 'administrador') {
            abort(403, 'No tienes permisos para generar respaldos del sistema.');
        }

        $filename = 'backup_' . now()->format('Ymd_His') . '.sql';
        $path = storage_path('app/backups/' . $filename);

        // Crear directorio de respaldos si no existe
        if (!is_dir(storage_path('app/backups'))) {
            mkdir(storage_path('app/backups'), 0755, true);
        }

        // Crear archivo de respaldo simulado
        file_put_contents($path, '-- Simulated backup file for SINDISOFT --');

        // Registrar en bitácora
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

        return back()->with('status', '💾 Respaldo generado correctamente: ' . $filename);
    }

    /**
     * Show system logs.
     */
    public function logs()
    {
        // 🔒 Solo administradores pueden ver la bitácora
        if (Auth::user()->rol !== 'administrador') {
            abort(403, 'Acceso denegado: Solo los administradores pueden ver la bitácora.');
        }

        $logPath = storage_path('logs/configuracion_sistema.log');
        $logs = file_exists($logPath)
            ? array_reverse(file($logPath, FILE_IGNORE_NEW_LINES))
            : [];

        return view('admin.logs', compact('logs'));
    }
}
