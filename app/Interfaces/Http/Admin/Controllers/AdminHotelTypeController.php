<?php

declare(strict_types=1);

namespace App\Interfaces\Http\Admin\Controllers;

use App\Application\Admin\DTOs\AdminHotelTypeListDTO;
use App\Application\Admin\DTOs\AdminUpsertHotelTypeDTO;
use App\Application\Admin\UseCases\CreateAdminHotelTypeUseCase;
use App\Application\Admin\UseCases\DeleteAdminHotelTypeUseCase;
use App\Application\Admin\UseCases\GetAdminHotelTypeDetailUseCase;
use App\Application\Admin\UseCases\ListAdminHotelTypesUseCase;
use App\Application\Admin\UseCases\UpdateAdminHotelTypeUseCase;
use App\Interfaces\Http\Admin\Requests\AdminHotelTypeListRequest;
use App\Interfaces\Http\Admin\Requests\AdminStoreHotelTypeRequest;
use App\Interfaces\Http\Admin\Requests\AdminUpdateHotelTypeRequest;
use App\Interfaces\Http\Admin\Resources\AdminHotelTypeResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

final class AdminHotelTypeController
{
    public function index(AdminHotelTypeListRequest $request, ListAdminHotelTypesUseCase $useCase): JsonResponse
    {
        $dto = new AdminHotelTypeListDTO(
            page: (int) $request->integer('page', 1),
            perPage: (int) $request->integer('per_page', 20),
            search: $request->filled('search') ? (string) $request->string('search') : null,
            searchBy: $request->filled('search_by') ? (string) $request->string('search_by') : null,
            isActive: $request->has('is_active') ? (bool) $request->boolean('is_active') : null,
        );

        $result = $useCase->execute($dto);
        $items = array_map(function (array $item) use ($request): array {
            $resource = new AdminHotelTypeResource($item);
            $resolved = $resource->toArray($request);

            return (array) ($resolved['data'] ?? []);
        }, $result['items']);

        return response()->json([
            'data' => $items,
            'meta' => [
                'version' => config('app.api_version', '1.0.0'),
                'timestamp' => now()->toIso8601String(),
                'message' => 'Lay danh sach hotel types thanh cong',
                'pagination' => $result['pagination'],
            ],
        ]);
    }

    public function show(int $id, GetAdminHotelTypeDetailUseCase $useCase, Request $request): JsonResponse
    {
        $item = $useCase->execute($id);
        $resource = (new AdminHotelTypeResource($item))->withMeta(['message' => 'Lay chi tiet hotel type thanh cong']);

        return response()->json($resource->toArray($request));
    }

    public function store(AdminStoreHotelTypeRequest $request, CreateAdminHotelTypeUseCase $useCase): JsonResponse
    {
        $dto = new AdminUpsertHotelTypeDTO(
            name: (string) $request->string('name'),
            icon: $request->filled('icon') ? (string) $request->string('icon') : null,
            isActive: (bool) $request->boolean('is_active', true),
            sort: (int) $request->integer('sort', 0),
        );

        $item = $useCase->execute($dto);
        $resource = (new AdminHotelTypeResource($item))->withMeta(['message' => 'Tao hotel type thanh cong']);

        return response()->json($resource->toArray($request), 201);
    }

    public function update(int $id, AdminUpdateHotelTypeRequest $request, UpdateAdminHotelTypeUseCase $useCase): JsonResponse
    {
        $dto = new AdminUpsertHotelTypeDTO(
            name: (string) $request->string('name'),
            icon: $request->filled('icon') ? (string) $request->string('icon') : null,
            isActive: (bool) $request->boolean('is_active', true),
            sort: (int) $request->integer('sort', 0),
        );

        $item = $useCase->execute($id, $dto);
        $resource = (new AdminHotelTypeResource($item))->withMeta(['message' => 'Cap nhat hotel type thanh cong']);

        return response()->json($resource->toArray($request));
    }

    public function destroy(int $id, DeleteAdminHotelTypeUseCase $useCase): JsonResponse
    {
        $useCase->execute($id);

        return response()->json([
            'data' => null,
            'meta' => [
                'version' => config('app.api_version', '1.0.0'),
                'timestamp' => now()->toIso8601String(),
                'message' => 'Xoa hotel type thanh cong',
            ],
        ]);
    }
}
