<?php

declare(strict_types=1);

namespace App\Infrastructure\Services\Contracts;

use Illuminate\Http\UploadedFile;

interface FileStorageServiceInterface
{
    public function upload(UploadedFile $file, string $folder, string $disk = 'public'): string;

    /** @param array<int, UploadedFile> $files
     *  @return array<int, string>
     */
    public function uploadMultiple(array $files, string $folder, string $disk = 'public'): array;

    public function delete(string $path, string $disk = 'public'): bool;

    /** @param array<int, string> $paths */
    public function deleteMultiple(array $paths, string $disk = 'public'): void;

    public function getUrl(string $path, string $disk = 'public'): string;

    public function exists(string $path, string $disk = 'public'): bool;

    public function move(string $from, string $to, string $disk = 'public'): bool;
}
