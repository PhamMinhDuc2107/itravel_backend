<?php

declare(strict_types=1);

namespace App\Interfaces\Http\Admin\Controllers;

use App\Application\Admin\DTOs\AdminAmenityListDTO;
use App\Application\Admin\DTOs\AdminUpsertAmenityDTO;
use App\Application\Admin\UseCases\CreateAdminAmenityUseCase;
use App\Application\Admin\UseCases\DeleteAdminAmenityUseCase;
use App\Application\Admin\UseCases\GetAdminAmenityDetailUseCase;
use App\Application\Admin\UseCases\ListAdminAmenitiesUseCase;
use App\Application\Admin\UseCases\UpdateAdminAmenityUseCase;
use App\Interfaces\Http\Admin\Requests\AdminAmenityListRequest;
use App\Interfaces\Http\Admin\Requests\AdminStoreAmenityRequest;
use App\Interfaces\Http\Admin\Requests\AdminUpdateAmenityRequest;
use App\Interfaces\Http\Admin\Resources\AdminAmenityResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

final class AdminAmenityController
{
    public function index(AdminAmenityListRequest $request, ListAdminAmenitiesUseCase $useCase): JsonResponse
    {
        $dto = new AdminAmenityListDTO(
            page: (int) $request->integer('page', 1),
            perPage: (int) $request->integer('per_page', 20),
            search: $request->filled('search') ? (string) $request->string('search') : null,
            searchBy: $request->filled('search_by') ? (string) $request->string('search_by') : null,
            type: $request->filled('type') ? (string) $request->string('type') : null,
            isActive: $request->has('is_active') ? (bool) $request->boolean('is_active') : null,
        );

        $result = $useCase->execute($dto);
        $items = array_map(function (array $item) use ($request): array {
            $resource = new AdminAmenityResource($item);
            $resolved = $resource->toArray($request);

            return (array) ($resolved['data'] ?? []);
        }, $result['items']);

        return response()->json([
            'data' => $items,
            'meta' => [
                'version' => config('app.api_version', '1.0.0'),
                'timestamp' => now()->toIso8601String(),
                'message' => 'Lay danh sach amenities thanh cong',
                'pagination' => $result['pagination'],
            ],
        ]);
    }

    public function show(int $id, GetAdminAmenityDetailUseCase $useCase, Request $request): JsonResponse
    {
        $item = $useCase->execute($id);
        $resource = (new AdminAmenityResource($item))->withMeta(['message' => 'Lay chi tiet amenity thanh cong']);

        return response()->json($resource->toArray($request));
    }

    public function store(AdminStoreAmenityRequest $request, CreateAdminAmenityUseCase $useCase): JsonResponse
    {
        $dto = new AdminUpsertAmenityDTO(
            name: (string) $request->string('name'),
            icon: $request->filled('icon') ? (string) $request->string('icon') : null,
            type: $request->filled('type') ? (string) $request->string('type') : null,
            isActive: (bool) $request->boolean('is_active', true),
            sortOrder: (int) $request->integer('sort_order', 0),
        );

        $item = $useCase->execute($dto);
        $resource = (new AdminAmenityResource($item))->withMeta(['message' => 'Tao amenity thanh cong']);

        return response()->json($resource->toArray($request), 201);
    }

    public function update(int $id, AdminUpdateAmenityRequest $request, UpdateAdminAmenityUseCase $useCase): JsonResponse
    {
        $dto = new AdminUpsertAmenityDTO(
            name: (string) $request->string('name'),
            icon: $request->filled('icon') ? (string) $request->string('icon') : null,
            type: $request->filled('type') ? (string) $request->string('type') : null,
            isActive: (bool) $request->boolean('is_active', true),
            sortOrder: (int) $request->integer('sort_order', 0),
        );

        $item = $useCase->execute($id, $dto);
        $resource = (new AdminAmenityResource($item))->withMeta(['message' => 'Cap nhat amenity thanh cong']);

        return response()->json($resource->toArray($request));
    }

    public function destroy(int $id, DeleteAdminAmenityUseCase $useCase): JsonResponse
    {
        $useCase->execute($id);

        return response()->json([
            'data' => null,
            'meta' => [
                'version' => config('app.api_version', '1.0.0'),
                'timestamp' => now()->toIso8601String(),
                'message' => 'Xoa amenity thanh cong',
            ],
        ]);
    }
}
