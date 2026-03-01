<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Validators\Failure;

abstract class BaseImport implements ToCollection, WithHeadingRow, WithValidation, SkipsOnError, SkipsOnFailure
{
    protected array $failures = [];
    protected array $errors = [];

    abstract public function collection(Collection $rows): void;

    abstract public function rules(): array;

    public function onError(\Throwable $error): void
    {
        $this->errors[] = $error->getMessage();
    }

    public function onFailure(Failure ...$failures): void
    {
        $this->failures = array_merge($this->failures, $failures);
    }

    public function failures(): array
    {
        return $this->failures;
    }

    public function errors(): array
    {
        return $this->errors;
    }

    public function getFailureMessages(): array
    {
        $messages = [];

        foreach ($this->failures as $failure) {
            $messages[] = sprintf(
                'Dòng %d: %s',
                $failure->row(),
                implode(', ', $failure->errors())
            );
        }

        return $messages;
    }
}
