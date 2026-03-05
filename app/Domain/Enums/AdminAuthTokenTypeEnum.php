<?php

declare(strict_types=1);

namespace App\Domain\Enums;

enum AdminAuthTokenTypeEnum: string
{
    case ACCESS = 'access';
    case BEARER = 'Bearer';
}
