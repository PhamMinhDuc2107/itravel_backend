<?php

declare(strict_types=1);

namespace App\Infrastructure\Database\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CompanySettingModel extends Model
{
    use HasFactory;

    public const SEARCHABLE_COLUMNS = [
        'company_name',
        'company_name_en',
        'email',
        'phone',
        'website',
        'address',
    ];

    protected $fillable = [
        'company_name',
        'company_name_en',
        'logo',
        'favicon',
        'business_license',
        'travel_license',
        'established_year',
        'description',
        'email',
        'email_support',
        'phone',
        'hotline',
        'fax',
        'website',
        'address',
        'ward',
        'district',
        'province',
        'country',
        'google_map_url',
        'latitude',
        'longitude',
        'facebook',
        'instagram',
        'youtube',
        'tiktok',
        'zalo',
        'bank_name',
        'bank_branch',
        'bank_account_number',
        'bank_account_name',
        'bank_qr_code',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'og_image',
        'header_scripts',
        'footer_scripts',
    ];

    protected $casts = [
        'latitude' => 'decimal:7',
        'longitude' => 'decimal:7',
    ];

    /**
     * @return array<int, string>
     */
    public static function searchableColumns(): array
    {
        return self::SEARCHABLE_COLUMNS;
    }
}
