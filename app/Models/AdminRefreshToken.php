<?php

namespace App\Models;

use App\Enums\StatusStateEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AdminRefreshToken extends Model
{
    protected $fillable = [
        'user_id',
        'token',
        'user_agent',
        'ip_address',
        'is_revoked',
        'expires_at',
    ];

    protected $casts = [
        'is_revoked' => StatusStateEnum::class,
        'expires_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function isExpired(): bool
    {
        return $this->expires_at->isPast();
    }

    public function isValid(): bool
    {
        return !$this->is_revoked->isActive() && !$this->isExpired();
    }
}
