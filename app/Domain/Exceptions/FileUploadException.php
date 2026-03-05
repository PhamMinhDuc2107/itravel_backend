<?php

namespace App\Domain\Exceptions;

class FileUploadException extends BaseException
{
    public function __construct(string $message = 'Tải file thất bại', array $errors = [])
    {
        parent::__construct($message, 422, $errors);
    }
}
