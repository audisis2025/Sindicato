<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TramitePaso extends Model
{
    use HasFactory;

    protected $table = 'tramite_pasos';

    protected $fillable = [
        'tramite_id',
        'orden',
        'nombre_paso',
        'descripcion_paso',
        'tiempo_estimado_dias',
        'formato_path',
        'next_step_if_fail',
    ];

    public function tramite()
    {
        return $this->belongsTo(Procedure::class, 'tramite_id');
    }
}
