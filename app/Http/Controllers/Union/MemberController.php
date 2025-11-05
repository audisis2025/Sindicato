<?php

/**
 * ===========================================================
 * Nombre de la clase: MemberController.php
 * Descripción: Controlador para la gestión de trabajadores del sindicato.
 * Fecha de creación: 02/11/2025
 * Elaboró: Iker Piza
 * Fecha de liberación: 02/11/2025
 * Autorizó: Líder Técnico
 * Versión: 1.1
 * Tipo de mantenimiento: Ajuste funcional.
 * Descripción del mantenimiento: Se agregó listado general, corrección de ruta de retorno y generación automática de usuario.
 * Responsable: Iker Piza
 * Revisor: QA SINDISOFT
 * ===========================================================
 */

namespace App\Http\Controllers\Union;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class MemberController extends Controller
{
    /**
     * Mostrar listado de trabajadores registrados (RF02)
     */
    public function index()
    {
        $workers = User::where('rol', 'trabajador')
            ->orderBy('id', 'desc')
            ->get();

        return view('union.members.index', compact('workers'));
    }

    /**
     * Mostrar formulario de registro de trabajador (RF02)
     */
    public function create()
    {
        return view('union.members.create');
    }

    /**
     * Guardar nuevo trabajador en la base de datos (RF02)
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'nullable|email|max:100|unique:users,email',
            'curp'  => 'nullable|string|max:18',
            'rfc'   => 'nullable|string|max:13',
            'sexo'  => 'nullable|in:H,M',
            'clave_presupuestal' => 'nullable|string|max:50',
        ]);

        // Generar nombre de usuario automático basado en el nombre
        $usuario = strtolower(str_replace(' ', '', substr($request->name, 0, 8))) . rand(10, 99);

        User::create([
            'usuario' => $usuario,
            'name'  => $request->name,
            'email' => $request->email,
            'rol'   => 'trabajador',
            'curp'  => $request->curp,
            'rfc'   => $request->rfc,
            'sexo'  => $request->sexo,
            'clave_presupuestal' => $request->clave_presupuestal,
            'password' => Hash::make('12345678'),
            'activo' => true,
        ]);

        return redirect()->route('union.members.index')
            ->with('success', '✅ Trabajador registrado correctamente.');
    }
    public function edit($id)
    {
        $worker = User::with('detalle')->findOrFail($id);
        return view('union.members.edit', compact('worker'));
    }

    public function update(Request $request, $id)
    {
        $worker = User::findOrFail($id);

        $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'nullable|email|max:100',
            'curp'  => 'nullable|string|max:18',
            'rfc'   => 'nullable|string|max:13',
            'sexo'  => 'nullable|in:H,M',
            'clave_presupuestal' => 'nullable|string|max:50',
            'activo' => 'required|boolean',
        ]);

        $worker->update([
            'name'   => $request->name,
            'email'  => $request->email,
            'activo' => $request->activo,
        ]);

        if ($worker->detalle) {
            $worker->detalle->update([
                'curp' => $request->curp,
                'rfc'  => $request->rfc,
                'sexo' => $request->sexo,
                'clave_presupuestal' => $request->clave_presupuestal,
            ]);
        }

        return redirect()->route('union.members.index')
            ->with('success', '📝 Datos del trabajador actualizados correctamente.');
    }


    /**
     * Eliminar trabajador (opcional)
     */
    public function destroy($id)
    {
        $worker = User::where('rol', 'trabajador')->findOrFail($id);
        $worker->delete();

        return back()->with('success', '🗑️ Trabajador eliminado correctamente.');
    }
}
