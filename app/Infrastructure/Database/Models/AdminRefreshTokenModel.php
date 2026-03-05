<?php

declare(strict_types=1);

namespace App\Infrastructure\Database\Models;

use App\Domain\Enums\StatusStateEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AdminRefreshTokenModel extends Model
{
    protected $table = 'admin_refresh_tokens';

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

    public function admin(): BelongsTo
    {
        return $this->belongsTo(AdminModel::class, 'user_id');
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
