<?php

declare(strict_types=1);

namespace App\Application\Admin\UseCases;

use App\Application\Admin\DTOs\AdminUpsertNewsDTO;
use App\Domain\Exceptions\NotFoundException;
use App\Domain\Repositories\NewsRepositoryInterface;
use Illuminate\Support\Facades\DB;

final class UpdateAdminNewsUseCase
{
    public function __construct(private readonly NewsRepositoryInterface $newsRepository) {}

    /** @return array<string, mixed> */
    public function execute(int $id, AdminUpsertNewsDTO $dto): array
    {
        return DB::transaction(function () use ($id, $dto): array {
            $item = $this->newsRepository->updateAndLoadById($id, $dto->toPayload());
            if ($item === null) {
                throw new NotFoundException('News khong ton tai');
            }

            return $item;
        });
    }
}
