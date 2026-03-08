<?php

declare(strict_types=1);

namespace App\Application\Admin\UseCases;

use App\Domain\Exceptions\NotFoundException;
use App\Domain\Repositories\ServicePageRepositoryInterface;

final class GetAdminServicePageDetailUseCase
{
    public function __construct(private readonly ServicePageRepositoryInterface $servicePageRepository) {}

    /** @return array<string, mixed> */
    public function execute(int $id): array
    {
        $item = $this->servicePageRepository->findDetailById($id);
        if ($item === null) {
            throw new NotFoundException('Service page khong ton tai');
        }

        return $item;
    }
}
