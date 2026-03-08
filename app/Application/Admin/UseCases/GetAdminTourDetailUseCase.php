<?php

declare(strict_types=1);

namespace App\Application\Admin\UseCases;

use App\Domain\Exceptions\NotFoundException;
use App\Domain\Repositories\TourRepositoryInterface;

final class GetAdminTourDetailUseCase
{
    public function __construct(private readonly TourRepositoryInterface $tourRepository) {}

    /** @return array<string, mixed> */
    public function execute(int $id): array
    {
        $item = $this->tourRepository->findDetailById($id);
        if ($item === null) {
            throw new NotFoundException('Tour khong ton tai');
        }

        return $item;
    }
}
