<?php

declare(strict_types=1);

namespace App\Application\Admin\UseCases;

use App\Application\Admin\DTOs\AdminUpsertHotelDTO;
use App\Domain\Repositories\HotelRepositoryInterface;
use Illuminate\Support\Facades\DB;

final class CreateAdminHotelUseCase
{
    public function __construct(private readonly HotelRepositoryInterface $hotelRepository) {}

    /** @return array<string, mixed> */
    public function execute(AdminUpsertHotelDTO $dto): array
    {
        return DB::transaction(fn(): array => $this->hotelRepository->createAndLoad($dto->toPayload()));
    }
}
