<?php

declare(strict_types=1);

namespace App\Application\Admin\UseCases;

use App\Domain\Exceptions\NotFoundException;
use App\Domain\Repositories\CategoryRepositoryInterface;

final class GetAdminCategoryDetailUseCase
{
    public function __construct(private readonly CategoryRepositoryInterface $categoryRepository) {}

    /** @return array<string, mixed> */
    public function execute(int $id): array
    {
        $item = $this->categoryRepository->findDetailById($id);
        if ($item === null) {
            throw new NotFoundException('Category khong ton tai');
        }

        return $item;
    }
}
