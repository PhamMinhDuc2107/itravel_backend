<?php

declare(strict_types=1);

namespace App\Infrastructure\Database\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class ContactModel extends Model
{
    use HasFactory;
    use SoftDeletes;

    public const SEARCHABLE_COLUMNS = [
        'full_name',
        'email',
        'phone',
        'subject',
        'message',
    ];

    protected $fillable = [
        'full_name',
        'email',
        'phone',
        'subject',
        'message',
        'status',
        'admin_note',
        'resolved_by',
        'resolved_at',
        'ip_address',
    ];

    protected $casts = [
        'resolved_at' => 'datetime',
    ];

    public function resolver(): BelongsTo
    {
        return $this->belongsTo(UserModel::class, 'resolved_by');
    }

    /**
     * @return array<int, string>
     */
    public static function searchableColumns(): array
    {
        return self::SEARCHABLE_COLUMNS;
    }
}
