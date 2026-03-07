<?php

declare(strict_types=1);

namespace App\Application\Admin\UseCases;

use App\Application\Admin\DTOs\AdminUpsertNewsCategoryDTO;
use App\Domain\Exceptions\NotFoundException;
use App\Domain\Repositories\NewsCategoryRepositoryInterface;
use Illuminate\Support\Facades\DB;

final class UpdateAdminNewsCategoryUseCase
{
    public function __construct(private readonly NewsCategoryRepositoryInterface $newsCategoryRepository) {}

    /** @return array<string, mixed> */
    public function execute(int $id, AdminUpsertNewsCategoryDTO $dto): array
    {
        return DB::transaction(function () use ($id, $dto): array {
            $item = $this->newsCategoryRepository->updateAndLoadById($id, $dto->toPayload());
            if ($item === null) {
                throw new NotFoundException('News category khong ton tai');
            }

            return $item;
        });
    }
}
