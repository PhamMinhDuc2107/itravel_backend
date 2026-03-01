<?php

namespace App\Http\Requests;

use App\Exceptions\ForbiddenException;
use App\Exceptions\ValidationException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

abstract class BaseRequest extends FormRequest
{
    protected function failedValidation(Validator $validator): void
    {
        throw new ValidationException(
            'Dữ liệu không hợp lệ',
            $validator->errors()->toArray()
        );
    }

    protected function failedAuthorization(): void
    {
        throw new ForbiddenException('Bạn không có quyền thực hiện hành động này');
    }

    public function rules(): array
    {
        return [];
    }
}
