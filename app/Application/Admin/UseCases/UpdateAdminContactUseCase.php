<?php

declare(strict_types=1);

namespace App\Application\Admin\UseCases;

use App\Application\Admin\DTOs\AdminUpsertContactDTO;
use App\Domain\Exceptions\NotFoundException;
use App\Domain\Repositories\ContactRepositoryInterface;
use Illuminate\Support\Facades\DB;

final class UpdateAdminContactUseCase
{
    public function __construct(private readonly ContactRepositoryInterface $contactRepository) {}

    /** @return array<string, mixed> */
    public function execute(int $id, AdminUpsertContactDTO $dto): array
    {
        return DB::transaction(function () use ($id, $dto): array {
            $item = $this->contactRepository->updateAndLoadById($id, $dto->toPayload());
            if ($item === null) {
                throw new NotFoundException('Contact khong ton tai');
            }

            return $item;
        });
    }
}
