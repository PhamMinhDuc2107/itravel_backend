<?php

declare(strict_types=1);

namespace App\Application\Admin\UseCases;

use App\Domain\Exceptions\NotFoundException;
use App\Domain\Repositories\NewsCategoryRepositoryInterface;

final class GetAdminNewsCategoryDetailUseCase
{
    public function __construct(private readonly NewsCategoryRepositoryInterface $newsCategoryRepository) {}

    /** @return array<string, mixed> */
    public function execute(int $id): array
    {
        $item = $this->newsCategoryRepository->findDetailById($id);
        if ($item === null) {
            throw new NotFoundException('News category khong ton tai');
        }

        return $item;
    }
}
