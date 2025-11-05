<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $table = 'notifications';

    protected $fillable = [
        'user_id',       // destinatario del mensaje
        'titulo',        // asunto breve
        'mensaje',       // contenido o instrucción
        'tipo',          // aviso | error | correccion
        'estado',        // leida | no_leida
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
