<?php

namespace App\Domain\Exceptions;

class UnauthorizedException extends BaseException
{
    public function __construct(string $message = 'Chưa xác thực', array $errors = [])
    {
        parent::__construct($message, 401, $errors);
    }
}
