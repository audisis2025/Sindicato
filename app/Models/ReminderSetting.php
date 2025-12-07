<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReminderSetting extends Model
{
    use HasFactory;

    protected $table = 'reminder_settings';

    protected $fillable = [
        'enabled',
        'channel',
        'interval_days',
        'base_message',
    ];
}
