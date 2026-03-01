<?php

namespace App\Enums;

enum HttpStatusEnum: int
{
    case OK = 200;
    case CREATED = 201;
    case ACCEPTED = 202;
    case NO_CONTENT = 204;
    case BAD_REQUEST = 400;
    case UNAUTHORIZED = 401;
    case FORBIDDEN = 403;
    case NOT_FOUND = 404;
    case METHOD_NOT_ALLOWED = 405;
    case CONFLICT = 409;
    case UNPROCESSABLE_ENTITY = 422;
    case TOO_MANY_REQUESTS = 429;
    case INTERNAL_SERVER_ERROR = 500;
    case SERVICE_UNAVAILABLE = 503;

    public function message(): string
    {
        return match($this) {
            self::OK => 'Success',
            self::CREATED => 'Created successfully',
            self::ACCEPTED => 'Accepted',
            self::NO_CONTENT => 'No content',
            self::BAD_REQUEST => 'Bad request',
            self::UNAUTHORIZED => 'Unauthorized',
            self::FORBIDDEN => 'Forbidden',
            self::NOT_FOUND => 'Not found',
            self::METHOD_NOT_ALLOWED => 'Method not allowed',
            self::CONFLICT => 'Conflict',
            self::UNPROCESSABLE_ENTITY => 'Unprocessable entity',
            self::TOO_MANY_REQUESTS => 'Too many requests',
            self::INTERNAL_SERVER_ERROR => 'Internal server error',
            self::SERVICE_UNAVAILABLE => 'Service unavailable',
        };
    }
}
