<?php

/**
 * ===========================================================
 * Nombre de la clase: BitacoraActividad.php
 * Descripción: Modelo para registrar observaciones, errores
 * o acciones realizadas durante el seguimiento de trámites.
 * Fecha de creación: 04/11/2025
 * Autor: Iker Piza
 * Versión: 1.0
 * ===========================================================
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BitacoraActividad extends Model
{
    use HasFactory;

    protected $table = 'bitacora_actividades';

    protected $fillable = [
        'procedure_id',
        'paso_id',
        'user_id',
        'mensaje',
        'tipo',
    ];

    public function usuario()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
