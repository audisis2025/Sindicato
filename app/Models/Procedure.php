<?php

/*
* ===========================================================
* Nombre de la clase: Procedure
* Descripción de la clase: Modelo Eloquent para la tabla 'procedures' (trámites).
* Representa la plantilla o definición de un trámite.
* Fecha de creación: [Fecha Original]
* Elaboró: [Autor Original]
* Fecha de liberación: 12/11/2025
* Autorizó: Líder Técnico
* Versión: 2.0
*
* Fecha de mantenimiento: 12/11/2025
* Folio de mantenimiento: [Tu Folio]
* Tipo de mantenimiento: Perfectivo (Traducción y Refactorización)
* Descripción del mantenimiento: Se traduce la tabla y $fillable a inglés.
* Se corrige 'steps()' y se añade 'requests()' según el resumen.
* Responsable: [Tu Nombre]
* Revisor: Gemini
* ===========================================================
*/

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo; // Importar
use Illuminate\Database\Eloquent\Relations\HasMany; // Importar

class Procedure extends Model
{
    use HasFactory;

    /**
     * La tabla asociada con el modelo. (Corregido)
     *
     * @var string
     */
    protected $table = 'procedures'; // Antes 'tramites'

    /**
     * Los atributos que se pueden asignar masivamente. (Traducidos)
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',          // ID del Sindicato que lo crea
        'name',             // 'nombre'
        'description',      // 'descripcion'
        'steps_count',      // 'numero_pasos'
        'opening_date',     // 'fecha_apertura'
        'closing_date',     // 'fecha_cierre'
        'estimated_days',   // 'tiempo_estimado_dias'
        'has_alternate_flow', // 'tiene_flujo_alterno'
        'status',          
    ];

    /**
     * Obtiene el usuario (Sindicato) que creó este trámite.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Obtiene los pasos (ProcedureStep) que definen este trámite.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function steps(): HasMany // Antes 'pasos'
    {
        // Antes 'TramitePaso' y 'tramite_id'
        return $this->hasMany(ProcedureStep::class, 'procedure_id')->orderBy('order');
    }

    /**
     * Obtiene todas las solicitudes (ProcedureRequest) que se han hecho de este trámite.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function requests(): HasMany // Relación añadida según el resumen
    {
        return $this->hasMany(ProcedureRequest::class, 'procedure_id');
    }
}