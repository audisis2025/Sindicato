<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UsuarioDetalle extends Model
{
    use HasFactory;

    protected $table = 'usuarios_detalle';

    protected $fillable = [
        'user_id',
        'curp',
        'rfc',
        'sexo',
        'clave_presupuestal',
        'activo',
    ];


    /**
     * Relación con el usuario principal.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
