<?php

namespace App\Exceptions;

class NotFoundException extends BaseException
{
    public function __construct(string $message = 'Không tìm thấy dữ liệu', array $errors = [])
    {
        parent::__construct($message, 404, $errors);
    }
}
