<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Procedure extends Model
{
    use HasFactory;

    protected $table = 'tramites';

    protected $fillable = [
        'user_id',
        'nombre',
        'descripcion',
        'numero_pasos',
        'fecha_apertura',
        'fecha_cierre',
        'tiempo_estimado_dias',
        'tiene_flujo_alterno',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    // app/Models/Procedure.php
    public function pasos()
    {
        return $this->hasMany(\App\Models\TramitePaso::class, 'tramite_id')->orderBy('orden');
    }
}
