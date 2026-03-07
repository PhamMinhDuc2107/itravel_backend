<?php

declare(strict_types=1);

namespace App\Application\Admin\UseCases;

use App\Application\Admin\DTOs\AdminUpsertServicePageDTO;
use App\Domain\Exceptions\NotFoundException;
use App\Domain\Repositories\ServicePageRepositoryInterface;
use Illuminate\Support\Facades\DB;

final class UpdateAdminServicePageUseCase
{
    public function __construct(private readonly ServicePageRepositoryInterface $servicePageRepository) {}

    /** @return array<string, mixed> */
    public function execute(int $id, AdminUpsertServicePageDTO $dto): array
    {
        return DB::transaction(function () use ($id, $dto): array {
            $item = $this->servicePageRepository->updateAndLoadById($id, $dto->toPayload(isUpdate: true));
            if ($item === null) {
                throw new NotFoundException('Service page khong ton tai');
            }

            return $item;
        });
    }
}
