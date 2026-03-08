<?php

declare(strict_types=1);

namespace App\Interfaces\Http\Admin\Controllers;

use App\Application\Admin\DTOs\AdminCompanySettingListDTO;
use App\Application\Admin\DTOs\AdminUpsertCompanySettingDTO;
use App\Application\Admin\UseCases\CreateAdminCompanySettingUseCase;
use App\Application\Admin\UseCases\DeleteAdminCompanySettingUseCase;
use App\Application\Admin\UseCases\GetAdminCompanySettingDetailUseCase;
use App\Application\Admin\UseCases\ListAdminCompanySettingsUseCase;
use App\Application\Admin\UseCases\UpdateAdminCompanySettingUseCase;
use App\Interfaces\Http\Admin\Requests\AdminCompanySettingListRequest;
use App\Interfaces\Http\Admin\Requests\AdminStoreCompanySettingRequest;
use App\Interfaces\Http\Admin\Requests\AdminUpdateCompanySettingRequest;
use App\Interfaces\Http\Admin\Resources\AdminCompanySettingResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

final class AdminCompanySettingController
{
    public function index(AdminCompanySettingListRequest $request, ListAdminCompanySettingsUseCase $useCase): JsonResponse
    {
        $dto = new AdminCompanySettingListDTO(
            page: (int) $request->integer('page', 1),
            perPage: (int) $request->integer('per_page', 20),
            search: $request->filled('search') ? (string) $request->string('search') : null,
            searchBy: $request->filled('search_by') ? (string) $request->string('search_by') : null,
        );

        $result = $useCase->execute($dto);
        $items = array_map(static fn(array $item): array => (array) ((new AdminCompanySettingResource($item))->toArray($request)['data'] ?? []), $result['items']);

        return response()->json([
            'data' => $items,
            'meta' => [
                'version' => config('app.api_version', '1.0.0'),
                'timestamp' => now()->toIso8601String(),
                'message' => 'Lay danh sach company settings thanh cong',
                'pagination' => $result['pagination'],
            ],
        ]);
    }

    public function show(int $id, GetAdminCompanySettingDetailUseCase $useCase, Request $request): JsonResponse
    {
        $item = $useCase->execute($id);
        $resource = (new AdminCompanySettingResource($item))->withMeta(['message' => 'Lay chi tiet company setting thanh cong']);

        return response()->json($resource->toArray($request));
    }

    public function store(AdminStoreCompanySettingRequest $request, CreateAdminCompanySettingUseCase $useCase): JsonResponse
    {
        $item = $useCase->execute(new AdminUpsertCompanySettingDTO($request->validated()));
        $resource = (new AdminCompanySettingResource($item))->withMeta(['message' => 'Tao company setting thanh cong']);

        return response()->json($resource->toArray($request), 201);
    }

    public function update(int $id, AdminUpdateCompanySettingRequest $request, UpdateAdminCompanySettingUseCase $useCase): JsonResponse
    {
        $item = $useCase->execute($id, new AdminUpsertCompanySettingDTO($request->validated()));
        $resource = (new AdminCompanySettingResource($item))->withMeta(['message' => 'Cap nhat company setting thanh cong']);

        return response()->json($resource->toArray($request));
    }

    public function destroy(int $id, DeleteAdminCompanySettingUseCase $useCase): JsonResponse
    {
        $useCase->execute($id);

        return response()->json([
            'data' => null,
            'meta' => [
                'version' => config('app.api_version', '1.0.0'),
                'timestamp' => now()->toIso8601String(),
                'message' => 'Xoa company setting thanh cong',
            ],
        ]);
    }
}
