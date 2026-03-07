<?php

declare(strict_types=1);

namespace App\Interfaces\Http\Admin\Controllers;

use App\Application\Admin\DTOs\AdminLocationListDTO;
use App\Application\Admin\DTOs\AdminUpsertLocationDTO;
use App\Application\Admin\UseCases\CreateAdminLocationUseCase;
use App\Application\Admin\UseCases\DeleteAdminLocationUseCase;
use App\Application\Admin\UseCases\GetAdminLocationDetailUseCase;
use App\Application\Admin\UseCases\ListAdminLocationsUseCase;
use App\Application\Admin\UseCases\UpdateAdminLocationUseCase;
use App\Interfaces\Http\Admin\Requests\AdminLocationListRequest;
use App\Interfaces\Http\Admin\Requests\AdminStoreLocationRequest;
use App\Interfaces\Http\Admin\Requests\AdminUpdateLocationRequest;
use App\Interfaces\Http\Admin\Resources\AdminLocationResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

final class AdminLocationController
{
    public function index(AdminLocationListRequest $request, ListAdminLocationsUseCase $useCase): JsonResponse
    {
        $dto = new AdminLocationListDTO(
            page: (int) $request->integer('page', 1),
            perPage: (int) $request->integer('per_page', 20),
            search: $request->filled('search') ? (string) $request->string('search') : null,
            searchBy: $request->filled('search_by') ? (string) $request->string('search_by') : null,
            parentId: $request->filled('parent_id') ? (int) $request->integer('parent_id') : null,
            isActive: $request->has('is_active') ? (bool) $request->boolean('is_active') : null,
            isFeatured: $request->has('is_featured') ? (bool) $request->boolean('is_featured') : null,
            isDomestic: $request->has('is_domestic') ? (bool) $request->boolean('is_domestic') : null,
            type: $request->filled('type') ? (string) $request->string('type') : null,
        );

        $result = $useCase->execute($dto);
        $items = array_map(function (array $item) use ($request): array {
            $resource = new AdminLocationResource($item);
            $resolved = $resource->toArray($request);

            return (array) ($resolved['data'] ?? []);
        }, $result['items']);

        return response()->json([
            'data' => $items,
            'meta' => [
                'version' => config('app.api_version', '1.0.0'),
                'timestamp' => now()->toIso8601String(),
                'message' => 'Lay danh sach locations thanh cong',
                'pagination' => $result['pagination'],
            ],
        ]);
    }

    public function show(int $id, GetAdminLocationDetailUseCase $useCase, Request $request): JsonResponse
    {
        $item = $useCase->execute($id);
        $resource = (new AdminLocationResource($item))->withMeta([
            'message' => 'Lay chi tiet location thanh cong',
        ]);

        return response()->json($resource->toArray($request));
    }

    public function store(AdminStoreLocationRequest $request, CreateAdminLocationUseCase $useCase): JsonResponse
    {
        $dto = new AdminUpsertLocationDTO(
            parentId: $request->filled('parent_id') ? (int) $request->integer('parent_id') : null,
            name: (string) $request->string('name'),
            type: $request->filled('type') ? (string) $request->string('type') : null,
            code: $request->filled('code') ? (string) $request->string('code') : null,
            thumbnail: $request->filled('thumbnail') ? (string) $request->string('thumbnail') : null,
            banner: $request->filled('banner') ? (string) $request->string('banner') : null,
            description: $request->filled('description') ? (string) $request->input('description') : null,
            isActive: (bool) $request->boolean('is_active', true),
            isFeatured: (bool) $request->boolean('is_featured', false),
            isDomestic: (bool) $request->boolean('is_domestic', false),
            sortOrder: (int) $request->integer('sort_order', 0),
            latitude: $request->filled('latitude') ? (string) $request->input('latitude') : null,
            longitude: $request->filled('longitude') ? (string) $request->input('longitude') : null,
            metaTitle: $request->filled('meta_title') ? (string) $request->string('meta_title') : null,
            metaDescription: $request->filled('meta_description') ? (string) $request->string('meta_description') : null,
        );

        $item = $useCase->execute($dto);
        $resource = (new AdminLocationResource($item))->withMeta([
            'message' => 'Tao location thanh cong',
        ]);

        return response()->json($resource->toArray($request), 201);
    }

    public function update(int $id, AdminUpdateLocationRequest $request, UpdateAdminLocationUseCase $useCase): JsonResponse
    {
        $dto = new AdminUpsertLocationDTO(
            parentId: $request->filled('parent_id') ? (int) $request->integer('parent_id') : null,
            name: (string) $request->string('name'),
            type: $request->filled('type') ? (string) $request->string('type') : null,
            code: $request->filled('code') ? (string) $request->string('code') : null,
            thumbnail: $request->filled('thumbnail') ? (string) $request->string('thumbnail') : null,
            banner: $request->filled('banner') ? (string) $request->string('banner') : null,
            description: $request->filled('description') ? (string) $request->input('description') : null,
            isActive: (bool) $request->boolean('is_active', true),
            isFeatured: (bool) $request->boolean('is_featured', false),
            isDomestic: (bool) $request->boolean('is_domestic', false),
            sortOrder: (int) $request->integer('sort_order', 0),
            latitude: $request->filled('latitude') ? (string) $request->input('latitude') : null,
            longitude: $request->filled('longitude') ? (string) $request->input('longitude') : null,
            metaTitle: $request->filled('meta_title') ? (string) $request->string('meta_title') : null,
            metaDescription: $request->filled('meta_description') ? (string) $request->string('meta_description') : null,
        );

        $item = $useCase->execute($id, $dto);
        $resource = (new AdminLocationResource($item))->withMeta([
            'message' => 'Cap nhat location thanh cong',
        ]);

        return response()->json($resource->toArray($request));
    }

    public function destroy(int $id, DeleteAdminLocationUseCase $useCase): JsonResponse
    {
        $useCase->execute($id);

        return response()->json([
            'data' => null,
            'meta' => [
                'version' => config('app.api_version', '1.0.0'),
                'timestamp' => now()->toIso8601String(),
                'message' => 'Xoa location thanh cong',
            ],
        ]);
    }
}
