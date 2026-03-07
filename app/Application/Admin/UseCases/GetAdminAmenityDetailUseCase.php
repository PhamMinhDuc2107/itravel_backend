<?php

declare(strict_types=1);

namespace App\Application\Admin\UseCases;

use App\Domain\Exceptions\NotFoundException;
use App\Domain\Repositories\AmenityRepositoryInterface;

final class GetAdminAmenityDetailUseCase
{
    public function __construct(private readonly AmenityRepositoryInterface $amenityRepository) {}

    /** @return array<string, mixed> */
    public function execute(int $id): array
    {
        $item = $this->amenityRepository->findDetailById($id);
        if ($item === null) {
            throw new NotFoundException('Amenity khong ton tai');
        }

        return $item;
    }
}
