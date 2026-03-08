<?php

declare(strict_types=1);

namespace App\Interfaces\Http\Admin\Controllers;

use App\Application\Admin\DTOs\AdminNewsCategoryListDTO;
use App\Application\Admin\DTOs\AdminUpsertNewsCategoryDTO;
use App\Application\Admin\UseCases\CreateAdminNewsCategoryUseCase;
use App\Application\Admin\UseCases\DeleteAdminNewsCategoryUseCase;
use App\Application\Admin\UseCases\GetAdminNewsCategoryDetailUseCase;
use App\Application\Admin\UseCases\ListAdminNewsCategoriesUseCase;
use App\Application\Admin\UseCases\UpdateAdminNewsCategoryUseCase;
use App\Interfaces\Http\Admin\Requests\AdminNewsCategoryListRequest;
use App\Interfaces\Http\Admin\Requests\AdminStoreNewsCategoryRequest;
use App\Interfaces\Http\Admin\Requests\AdminUpdateNewsCategoryRequest;
use App\Interfaces\Http\Admin\Resources\AdminNewsCategoryResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

final class AdminNewsCategoryController
{
    public function index(AdminNewsCategoryListRequest $request, ListAdminNewsCategoriesUseCase $useCase): JsonResponse
    {
        $dto = new AdminNewsCategoryListDTO(
            page: (int) $request->integer('page', 1),
            perPage: (int) $request->integer('per_page', 20),
            search: $request->filled('search') ? (string) $request->string('search') : null,
            searchBy: $request->filled('search_by') ? (string) $request->string('search_by') : null,
            parentId: $request->filled('parent_id') ? (int) $request->integer('parent_id') : null,
            isActive: $request->has('is_active') ? (bool) $request->boolean('is_active') : null,
        );

        $result = $useCase->execute($dto);
        $items = array_map(function (array $item) use ($request): array {
            $resource = new AdminNewsCategoryResource($item);
            $resolved = $resource->toArray($request);

            return (array) ($resolved['data'] ?? []);
        }, $result['items']);

        return response()->json([
            'data' => $items,
            'meta' => [
                'version' => config('app.api_version', '1.0.0'),
                'timestamp' => now()->toIso8601String(),
                'message' => 'Lay danh sach news categories thanh cong',
                'pagination' => $result['pagination'],
            ],
        ]);
    }

    public function show(int $id, GetAdminNewsCategoryDetailUseCase $useCase, Request $request): JsonResponse
    {
        $item = $useCase->execute($id);
        $resource = (new AdminNewsCategoryResource($item))->withMeta([
            'message' => 'Lay chi tiet news category thanh cong',
        ]);

        return response()->json($resource->toArray($request));
    }

    public function store(AdminStoreNewsCategoryRequest $request, CreateAdminNewsCategoryUseCase $useCase): JsonResponse
    {
        $dto = new AdminUpsertNewsCategoryDTO(
            parentId: $request->filled('parent_id') ? (int) $request->integer('parent_id') : null,
            name: (string) $request->string('name'),
            thumbnail: $request->filled('thumbnail') ? (string) $request->string('thumbnail') : null,
            description: $request->filled('description') ? (string) $request->input('description') : null,
            isActive: (bool) $request->boolean('is_active', true),
            sort: (int) $request->integer('sort', 0),
        );

        $item = $useCase->execute($dto);
        $resource = (new AdminNewsCategoryResource($item))->withMeta([
            'message' => 'Tao news category thanh cong',
        ]);

        return response()->json($resource->toArray($request), 201);
    }

    public function update(int $id, AdminUpdateNewsCategoryRequest $request, UpdateAdminNewsCategoryUseCase $useCase): JsonResponse
    {
        $dto = new AdminUpsertNewsCategoryDTO(
            parentId: $request->filled('parent_id') ? (int) $request->integer('parent_id') : null,
            name: (string) $request->string('name'),
            thumbnail: $request->filled('thumbnail') ? (string) $request->string('thumbnail') : null,
            description: $request->filled('description') ? (string) $request->input('description') : null,
            isActive: (bool) $request->boolean('is_active', true),
            sort: (int) $request->integer('sort', 0),
        );

        $item = $useCase->execute($id, $dto);
        $resource = (new AdminNewsCategoryResource($item))->withMeta([
            'message' => 'Cap nhat news category thanh cong',
        ]);

        return response()->json($resource->toArray($request));
    }

    public function destroy(int $id, DeleteAdminNewsCategoryUseCase $useCase): JsonResponse
    {
        $useCase->execute($id);

        return response()->json([
            'data' => null,
            'meta' => [
                'version' => config('app.api_version', '1.0.0'),
                'timestamp' => now()->toIso8601String(),
                'message' => 'Xoa news category thanh cong',
            ],
        ]);
    }
}
