<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SystemLog extends Model
{
	protected $table = 'system_logs';

	protected $fillable = [
		'user_id',
		'action',
		'description',
	];

	public function user(): BelongsTo
	{
		return $this->belongsTo(User::class);
	}
}
