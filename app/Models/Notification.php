<?php

/*
* ===========================================================
* Nombre de la clase: Notification
* Descripción de la clase: Modelo Eloquent para la tabla 'notifications'.
* Fecha de creación: [Fecha Original]
* Elaboró: [Autor Original]
* Fecha de liberación: 12/11/2025
* Autorizó: Líder Técnico
* Versión: 2.0
*
* Fecha de mantenimiento: 12/11/2025
* Folio de mantenimiento: [Tu Folio]
* Tipo de mantenimiento: Perfectivo (Traducción)
* Descripción del mantenimiento: Se traducen $fillable a inglés
* para alinear con la migración y el Manual PRO-Laravel V3.2.
* Responsable: [Tu Nombre]
* Revisor: Gemini
* ===========================================================
*/

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo; // Importar

class Notification extends Model
{
    use HasFactory;

    /**
     * La tabla asociada con el modelo.
     *
     * @var string
     */
    protected $table = 'notifications';

    /**
     * Los atributos que se pueden asignar masivamente. (Traducidos)
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'title',    // 'titulo'
        'message',  // 'mensaje'
        'type',     // 'tipo'
        'status',   // 'estado'
    ];

    /**
     * Obtiene el usuario al que pertenece la notificación.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}