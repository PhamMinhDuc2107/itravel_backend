<?php

declare(strict_types=1);

namespace App\Interfaces\Http\Admin\Requests;

use App\Domain\Exceptions\ForbiddenException;
use App\Domain\Exceptions\ValidationException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

abstract class BaseAdminRequest extends FormRequest
{
    protected function failedValidation(Validator $validator): void
    {
        throw new ValidationException('Du lieu khong hop le', $validator->errors()->toArray());
    }

    protected function failedAuthorization(): void
    {
        throw new ForbiddenException('Ban khong co quyen thuc hien hanh dong nay');
    }

    public function rules(): array
    {
        return [];
    }
}
