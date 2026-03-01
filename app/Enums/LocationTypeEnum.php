<?php

namespace App\Enums;

enum LocationTypeEnum: string
{
    case COUNTRY = 'country';
    case PROVINCE = 'province';
    case CITY = 'city';
    case DISTRICT = 'district';
    case AREA = 'area';

    public function label(): string
    {
        return match($this) {
            self::COUNTRY => 'Quốc gia',
            self::PROVINCE => 'Tỉnh',
            self::CITY => 'Thành phố',
            self::DISTRICT => 'Quận/Huyện',
            self::AREA => 'Khu vực',
        };
    }
}
