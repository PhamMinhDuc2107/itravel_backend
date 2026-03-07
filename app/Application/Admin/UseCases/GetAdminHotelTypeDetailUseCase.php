<?php

declare(strict_types=1);

namespace App\Application\Admin\UseCases;

use App\Domain\Exceptions\NotFoundException;
use App\Domain\Repositories\HotelTypeRepositoryInterface;

final class GetAdminHotelTypeDetailUseCase
{
    public function __construct(private readonly HotelTypeRepositoryInterface $hotelTypeRepository) {}

    /** @return array<string, mixed> */
    public function execute(int $id): array
    {
        $item = $this->hotelTypeRepository->findDetailById($id);
        if ($item === null) {
            throw new NotFoundException('Hotel type khong ton tai');
        }

        return $item;
    }
}
