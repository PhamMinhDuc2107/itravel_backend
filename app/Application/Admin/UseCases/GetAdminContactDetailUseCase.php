<?php

declare(strict_types=1);

namespace App\Application\Admin\UseCases;

use App\Domain\Exceptions\NotFoundException;
use App\Domain\Repositories\ContactRepositoryInterface;

final class GetAdminContactDetailUseCase
{
    public function __construct(private readonly ContactRepositoryInterface $contactRepository) {}

    /** @return array<string, mixed> */
    public function execute(int $id): array
    {
        $item = $this->contactRepository->findDetailById($id);
        if ($item === null) {
            throw new NotFoundException('Contact khong ton tai');
        }

        return $item;
    }
}
