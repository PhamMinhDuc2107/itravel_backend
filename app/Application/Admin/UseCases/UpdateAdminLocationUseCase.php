<?php

declare(strict_types=1);

namespace App\Application\Admin\UseCases;

use App\Application\Admin\DTOs\AdminUpsertLocationDTO;
use App\Domain\Exceptions\NotFoundException;
use App\Domain\Repositories\LocationRepositoryInterface;
use Illuminate\Support\Facades\DB;

final class UpdateAdminLocationUseCase
{
    public function __construct(private readonly LocationRepositoryInterface $locationRepository) {}

    /** @return array<string, mixed> */
    public function execute(int $id, AdminUpsertLocationDTO $dto): array
    {
        return DB::transaction(function () use ($id, $dto): array {
            $item = $this->locationRepository->updateAndLoadById($id, $dto->toPayload());
            if ($item === null) {
                throw new NotFoundException('Location khong ton tai');
            }

            return $item;
        });
    }
}
