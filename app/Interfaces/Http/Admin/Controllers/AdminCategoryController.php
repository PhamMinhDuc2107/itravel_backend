<?php

declare(strict_types=1);

namespace App\Interfaces\Http\Admin\Controllers;

use App\Application\Admin\DTOs\AdminCategoryListDTO;
use App\Application\Admin\DTOs\AdminUpsertCategoryDTO;
use App\Application\Admin\UseCases\CreateAdminCategoryUseCase;
use App\Application\Admin\UseCases\DeleteAdminCategoryUseCase;
use App\Application\Admin\UseCases\GetAdminCategoryDetailUseCase;
use App\Application\Admin\UseCases\ListAdminCategoriesUseCase;
use App\Application\Admin\UseCases\UpdateAdminCategoryUseCase;
use App\Interfaces\Http\Admin\Requests\AdminCategoryListRequest;
use App\Interfaces\Http\Admin\Requests\AdminStoreCategoryRequest;
use App\Interfaces\Http\Admin\Requests\AdminUpdateCategoryRequest;
use App\Interfaces\Http\Admin\Resources\AdminCategoryResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

final class AdminCategoryController
{
    public function index(AdminCategoryListRequest $request, ListAdminCategoriesUseCase $useCase): JsonResponse
    {
        $dto = new AdminCategoryListDTO(
            page: (int) $request->integer('page', 1),
            perPage: (int) $request->integer('per_page', 20),
            search: $request->filled('search') ? (string) $request->string('search') : null,
            searchBy: $request->filled('search_by') ? (string) $request->string('search_by') : null,
            parentId: $request->filled('parent_id') ? (int) $request->integer('parent_id') : null,
            isActive: $request->has('is_active') ? (bool) $request->boolean('is_active') : null,
            isFeatured: $request->has('is_featured') ? (bool) $request->boolean('is_featured') : null,
            type: $request->filled('type') ? (string) $request->string('type') : null,
        );

        $result = $useCase->execute($dto);
        $items = array_map(function (array $item) use ($request): array {
            $resource = new AdminCategoryResource($item);
            $resolved = $resource->toArray($request);

            return (array) ($resolved['data'] ?? []);
        }, $result['items']);

        return response()->json([
            'data' => $items,
            'meta' => [
                'version' => config('app.api_version', '1.0.0'),
                'timestamp' => now()->toIso8601String(),
                'message' => 'Lay danh sach categories thanh cong',
                'pagination' => $result['pagination'],
            ],
        ]);
    }

    public function show(int $id, GetAdminCategoryDetailUseCase $useCase, Request $request): JsonResponse
    {
        $item = $useCase->execute($id);
        $resource = (new AdminCategoryResource($item))->withMeta([
            'message' => 'Lay chi tiet category thanh cong',
        ]);

        return response()->json($resource->toArray($request));
    }

    public function store(AdminStoreCategoryRequest $request, CreateAdminCategoryUseCase $useCase): JsonResponse
    {
        $dto = new AdminUpsertCategoryDTO(
            parentId: $request->filled('parent_id') ? (int) $request->integer('parent_id') : null,
            name: (string) $request->string('name'),
            type: $request->filled('type') ? (string) $request->string('type') : null,
            description: $request->filled('description') ? (string) $request->input('description') : null,
            sort: (int) $request->integer('sort', 0),
            isActive: (bool) $request->boolean('is_active', true),
            isFeatured: (bool) $request->boolean('is_featured', false),
            metaTitle: $request->filled('meta_title') ? (string) $request->string('meta_title') : null,
            metaDescription: $request->filled('meta_description') ? (string) $request->string('meta_description') : null,
        );

        $item = $useCase->execute($dto);
        $resource = (new AdminCategoryResource($item))->withMeta([
            'message' => 'Tao category thanh cong',
        ]);

        return response()->json($resource->toArray($request), 201);
    }

    public function update(int $id, AdminUpdateCategoryRequest $request, UpdateAdminCategoryUseCase $useCase): JsonResponse
    {
        $dto = new AdminUpsertCategoryDTO(
            parentId: $request->filled('parent_id') ? (int) $request->integer('parent_id') : null,
            name: (string) $request->string('name'),
            type: $request->filled('type') ? (string) $request->string('type') : null,
            description: $request->filled('description') ? (string) $request->input('description') : null,
            sort: (int) $request->integer('sort', 0),
            isActive: (bool) $request->boolean('is_active', true),
            isFeatured: (bool) $request->boolean('is_featured', false),
            metaTitle: $request->filled('meta_title') ? (string) $request->string('meta_title') : null,
            metaDescription: $request->filled('meta_description') ? (string) $request->string('meta_description') : null,
        );

        $item = $useCase->execute($id, $dto);
        $resource = (new AdminCategoryResource($item))->withMeta([
            'message' => 'Cap nhat category thanh cong',
        ]);

        return response()->json($resource->toArray($request));
    }

    public function destroy(int $id, DeleteAdminCategoryUseCase $useCase): JsonResponse
    {
        $useCase->execute($id);

        return response()->json([
            'data' => null,
            'meta' => [
                'version' => config('app.api_version', '1.0.0'),
                'timestamp' => now()->toIso8601String(),
                'message' => 'Xoa category thanh cong',
            ],
        ]);
    }
}
