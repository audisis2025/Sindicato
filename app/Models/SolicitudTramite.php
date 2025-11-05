<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SolicitudTramite extends Model
{
    use HasFactory;

    protected $table = 'solicitudes_tramite';

    protected $fillable = [
        'tramite_id',
        'user_id',
        'paso_actual',
        'estado',
    ];

    /** 🔹 Relaciones **/
    public function tramite()
    {
        return $this->belongsTo(Procedure::class, 'tramite_id');
    }

    public function trabajador()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function pasos()
    {
        return $this->hasMany(TramitePaso::class, 'tramite_id', 'tramite_id');
    }
}
