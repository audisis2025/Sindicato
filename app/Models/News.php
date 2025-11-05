<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class News extends Model
{
    use HasFactory;

    protected $table = 'news';

    protected $fillable = [
        'titulo',
        'contenido',
        'tipo',
        'archivo_path',
        'publicado_por',
        'estado',
    ];

    public function autor()
    {
        return $this->belongsTo(User::class, 'publicado_por');
    }
}
