<?php

declare(strict_types=1);

namespace App\Application\Admin\UseCases;

use App\Application\Admin\DTOs\AdminUpsertHotelTypeDTO;
use App\Domain\Repositories\HotelTypeRepositoryInterface;
use Illuminate\Support\Facades\DB;

final class CreateAdminHotelTypeUseCase
{
    public function __construct(private readonly HotelTypeRepositoryInterface $hotelTypeRepository) {}

    /** @return array<string, mixed> */
    public function execute(AdminUpsertHotelTypeDTO $dto): array
    {
        return DB::transaction(fn(): array => $this->hotelTypeRepository->createAndLoad($dto->toPayload()));
    }
}
