<?php

declare(strict_types=1);

namespace App\Application\Admin\UseCases;

use App\Application\Admin\DTOs\AdminUpsertTourDTO;
use App\Domain\Exceptions\NotFoundException;
use App\Domain\Repositories\TourRepositoryInterface;
use Illuminate\Support\Facades\DB;

final class UpdateAdminTourUseCase
{
    public function __construct(private readonly TourRepositoryInterface $tourRepository) {}

    /** @return array<string, mixed> */
    public function execute(int $id, AdminUpsertTourDTO $dto): array
    {
        return DB::transaction(function () use ($id, $dto): array {
            $item = $this->tourRepository->updateAndLoadById($id, $dto->toPayload(isUpdate: true));
            if ($item === null) {
                throw new NotFoundException('Tour khong ton tai');
            }

            return $item;
        });
    }
}
