<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use InvalidArgumentException;

class SystemNotification extends Model
{
	use HasFactory;

	protected $table = 'system_notifications';

	public const TYPE_INFO = 'info';
	public const TYPE_SUCCESS = 'success';
	public const TYPE_ERROR = 'error';
	public const TYPE_WARNING = 'warning';
	public const TYPE_PENDING = 'pending';
	public const TYPE_APPROVED = 'approved';
	public const TYPE_REJECTED = 'rejected';
	public const TYPE_CORRECTION = 'correction';

	public static function allowedTypes(): array
	{
		return [
			self::TYPE_INFO,
			self::TYPE_SUCCESS,
			self::TYPE_ERROR,
			self::TYPE_WARNING,
			self::TYPE_PENDING,
			self::TYPE_APPROVED,
			self::TYPE_REJECTED,
			self::TYPE_CORRECTION,
		];
	}

	public const STATUS_UNREAD = 'unread';
	public const STATUS_READ = 'read';

	public static function allowedStatuses(): array
	{
		return [
			self::STATUS_UNREAD,
			self::STATUS_READ,
		];
	}

	protected $fillable = [
		'user_id',
		'title',
		'message',
		'type',
		'status',
		'read_at',
	];
	protected $casts = [
		'read_at' => 'datetime',
	];

	public function user(): BelongsTo
	{
		return $this->belongsTo(User::class);
	}

	public function isType(string $type): bool
	{
		return $this->type === $type;
	}

	public function markAsRead(): void
	{
		$this->update([
			'status' => self::STATUS_READ,
			'read_at' => now(),
		]);
	}


	protected static function booted()
	{
		static::creating(function ($model) {
			if (!in_array($model->type, self::allowedTypes())) {
				throw new InvalidArgumentException("Tipo de notificación inválido: {$model->type}");
			}

			if (!in_array($model->status, self::allowedStatuses())) {
				throw new InvalidArgumentException("Estado inválido: {$model->status}");
			}
		});
	}
}
