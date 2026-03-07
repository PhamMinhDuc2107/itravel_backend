<?php

declare(strict_types=1);

namespace App\Infrastructure\Services\External;

use App\Domain\Exceptions\FileUploadException;
use App\Infrastructure\Services\Contracts\FileStorageServiceInterface;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

final class FileSystemManager implements FileStorageServiceInterface
{
    private array $allowedExtensions;
    private int $maxSizeInMb;

    public function __construct(array $allowedExtensions = ['jpg', 'jpeg', 'png', 'pdf', 'doc', 'docx'], int $maxSizeInMb = 10)
    {
        $this->allowedExtensions = $allowedExtensions;
        $this->maxSizeInMb = $maxSizeInMb;
    }

    public function upload(UploadedFile $file, string $folder, string $disk = 'public'): string
    {
        $this->validateFile($file);

        $extension = $file->getClientOriginalExtension();
        $fileName = Str::uuid() . '.' . $extension;
        $path = $folder . '/' . $fileName;

        Storage::disk($disk)->putFileAs($folder, $file, $fileName);

        return $path;
    }

    public function uploadMultiple(array $files, string $folder, string $disk = 'public'): array
    {
        $paths = [];

        foreach ($files as $file) {
            if ($file instanceof UploadedFile) {
                $paths[] = $this->upload($file, $folder, $disk);
            }
        }

        return $paths;
    }

    public function delete(string $path, string $disk = 'public'): bool
    {
        if (Storage::disk($disk)->exists($path)) {
            return Storage::disk($disk)->delete($path);
        }

        return false;
    }

    public function deleteMultiple(array $paths, string $disk = 'public'): void
    {
        foreach ($paths as $path) {
            $this->delete($path, $disk);
        }
    }

    public function getUrl(string $path, string $disk = 'public'): string
    {
        if ($disk === 'public') {
            return '/storage/' . ltrim($path, '/');
        }

        return $path;
    }

    public function exists(string $path, string $disk = 'public'): bool
    {
        return Storage::disk($disk)->exists($path);
    }

    public function move(string $from, string $to, string $disk = 'public'): bool
    {
        return Storage::disk($disk)->move($from, $to);
    }

    private function validateFile(UploadedFile $file): void
    {
        $extension = strtolower($file->getClientOriginalExtension());

        if (!in_array($extension, $this->allowedExtensions)) {
            throw new FileUploadException(
                'Định dạng file không được phép',
                ['allowed_extensions' => $this->allowedExtensions, 'uploaded' => $extension]
            );
        }

        $fileSizeInMb = $file->getSize() / 1024 / 1024;

        if ($fileSizeInMb > $this->maxSizeInMb) {
            throw new FileUploadException(
                'Kích thước file vượt quá giới hạn',
                ['max_size_mb' => $this->maxSizeInMb, 'uploaded_size_mb' => round($fileSizeInMb, 2)]
            );
        }
    }
}
