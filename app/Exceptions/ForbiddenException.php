<?php

namespace App\Exceptions;

class ForbiddenException extends BaseException
{
    public function __construct(string $message = 'Không có quyền truy cập', array $errors = [])
    {
        parent::__construct($message, 403, $errors);
    }
}
