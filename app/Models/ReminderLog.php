<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReminderLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'procedure_id',
        'channel',
        'message',
        'sent_at',
    ];
}
