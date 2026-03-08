<?php

declare(strict_types=1);

namespace App\Application\Admin\UseCases;

use App\Domain\Exceptions\NotFoundException;
use App\Domain\Repositories\HotelRepositoryInterface;

final class GetAdminHotelDetailUseCase
{
    public function __construct(private readonly HotelRepositoryInterface $hotelRepository) {}

    /** @return array<string, mixed> */
    public function execute(int $id): array
    {
        $item = $this->hotelRepository->findDetailById($id);
        if ($item === null) {
            throw new NotFoundException('Hotel khong ton tai');
        }

        return $item;
    }
}
