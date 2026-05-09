<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Http\Request;

class AuditLog extends Model
{
    protected $fillable = [
        'actor_id',
        'target_user_id',
        'auditable_type',
        'auditable_id',
        'action',
        'old_values',
        'new_values',
        'ip_address',
        'user_agent',
    ];

    protected $casts = [
        'old_values' => 'array',
        'new_values' => 'array',
    ];

    public function actor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'actor_id');
    }

    public function targetUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'target_user_id');
    }

    public function auditable(): MorphTo
    {
        return $this->morphTo();
    }

    public static function record(
        string $action,
        ?User $actor = null,
        ?User $targetUser = null,
        ?Model $auditable = null,
        array $oldValues = [],
        array $newValues = [],
        ?Request $request = null
    ): self {
        $request ??= request();

        return self::create([
            'actor_id' => $actor?->id,
            'target_user_id' => $targetUser?->id,
            'auditable_type' => $auditable ? $auditable::class : null,
            'auditable_id' => $auditable?->getKey(),
            'action' => $action,
            'old_values' => $oldValues ?: null,
            'new_values' => $newValues ?: null,
            'ip_address' => $request?->ip(),
            'user_agent' => $request?->userAgent(),
        ]);
    }
}
