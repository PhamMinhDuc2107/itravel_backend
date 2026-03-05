<?php

namespace App\Domain\Exceptions;

class BusinessException extends BaseException
{
    public function __construct(string $message, array $errors = [])
    {
        parent::__construct($message, 422, $errors);
    }
}
