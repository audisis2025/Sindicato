<?php

/*
* ===========================================================
* Nombre de la clase: News
* Descripción de la clase: Modelo Eloquent para la tabla 'news'.
* Fecha de liberación: 12/11/2025
* Versión: 3.0 (Actualizada para opción 3: imagen + PDF)
* ===========================================================
*/

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class News extends Model
{
    use HasFactory;

    protected $table = 'news';

    /**
     * Campos asignables masivamente.
     */
    protected $fillable = [
        'title',
        'content',
        'type',          // convocatoria | comunicado | evento
        'status',        // draft | published
        'image_path',    // portada
        'file_path',     // archivo PDF
        'user_id',
    ];

    /**
     * Relación: la noticia pertenece a un usuario (autor).
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
