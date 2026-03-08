<?php

declare(strict_types=1);

namespace App\Application\Admin\UseCases;

use App\Domain\Exceptions\NotFoundException;
use App\Domain\Repositories\LocationRepositoryInterface;

final class GetAdminLocationDetailUseCase
{
    public function __construct(private readonly LocationRepositoryInterface $locationRepository) {}

    /** @return array<string, mixed> */
    public function execute(int $id): array
    {
        $item = $this->locationRepository->findDetailById($id);
        if ($item === null) {
            throw new NotFoundException('Location khong ton tai');
        }

        return $item;
    }
}
