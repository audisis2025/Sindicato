<?php

/*
* ===========================================================
* Nombre de la clase: News
* Descripción de la clase: Modelo Eloquent para la tabla 'news' (noticias).
* Fecha de creación: [Fecha Original]
* Elaboró: [Autor Original]
* Fecha de liberación: 12/11/2025
* Autorizó: Líder Técnico
* Versión: 2.0
*
* Fecha de mantenimiento: 12/11/2025
* Folio de mantenimiento: [Tu Folio]
* Tipo de mantenimiento: Perfectivo (Traducción)
* Descripción del mantenimiento: Se traducen $fillable y relaciones a inglés
* para alinear con la migración y el Manual PRO-Laravel V3.2.
* Responsable: [Tu Nombre]
* Revisor: Gemini
* ===========================================================
*/

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo; // Importar

class News extends Model
{
    use HasFactory;

    /**
     * La tabla asociada con el modelo.
     *
     * @var string
     */
    protected $table = 'news';

    /**
     * Los atributos que se pueden asignar masivamente. (Traducidos)
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',        // 'titulo'
        'content',      // 'contenido'
        'type',         // 'tipo'
        'file_path',    // 'archivo_path'
        'user_id',      // 'publicado_por'
        'status',       // 'estado'
    ];

    /**
     * Obtiene el usuario (autor) que publicó la noticia.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo // 'autor' -> 'user'
    {
        // 'publicado_por' -> 'user_id'
        return $this->belongsTo(User::class, 'user_id'); 
    }
}