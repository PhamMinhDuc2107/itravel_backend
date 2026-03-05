<?php

namespace App\Domain\Exceptions;

use RuntimeException;

abstract class BaseException extends RuntimeException
{
    protected int $statusCode;
    protected array $errors;

    public function __construct(string $message, int $statusCode = 500, array $errors = [])
    {
        parent::__construct($message);
        $this->statusCode = $statusCode;
        $this->errors = $errors;
    }

    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    public function getErrors(): array
    {
        return $this->errors;
    }

    public function toArray(): array
    {
        return [
            'message' => $this->getMessage(),
            'errors' => $this->errors,
        ];
    }
}
