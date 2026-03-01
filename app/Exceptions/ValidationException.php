<?php

namespace App\Exceptions;

class ValidationException extends BaseException
{
    public function __construct(string $message = 'Dữ liệu không hợp lệ', array $errors = [])
    {
        parent::__construct($message, 422, $errors);
    }
}
