<?php
/*
* Nombre de la clase           : ActivityLog.php
* Descripción de la clase      : Modelo Eloquent encargado de representar la bitácora de actividades del sistema, registrando acciones realizadas por los usuarios, módulo afectado y dirección IP.
* Fecha de creación            : 10/11/2025
* Elaboró                      : Iker Piza
* Fecha de liberación          : 18/12/2025
* Autorizó                     :
* Versión                      : 1.0
* Fecha de mantenimiento       :
* Folio de mantenimiento       :
* Tipo de mantenimiento        :
* Descripción del mantenimiento:
* Responsable                  :
* Revisor                      :
*/


namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ActivityLog extends Model
{
    use HasFactory;

 
    protected $table = 'activity_logs';

    protected $fillable = [
        'user_id',
        'action',
        'module',
        'ip_address',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
