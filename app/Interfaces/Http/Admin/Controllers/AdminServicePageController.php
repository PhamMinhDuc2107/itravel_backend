<?php

declare(strict_types=1);

namespace App\Interfaces\Http\Admin\Controllers;

use App\Application\Admin\DTOs\AdminServicePageListDTO;
use App\Application\Admin\DTOs\AdminUpsertServicePageDTO;
use App\Application\Admin\UseCases\CreateAdminServicePageUseCase;
use App\Application\Admin\UseCases\DeleteAdminServicePageUseCase;
use App\Application\Admin\UseCases\GetAdminServicePageDetailUseCase;
use App\Application\Admin\UseCases\ListAdminServicePagesUseCase;
use App\Application\Admin\UseCases\UpdateAdminServicePageUseCase;
use App\Interfaces\Http\Admin\Requests\AdminServicePageListRequest;
use App\Interfaces\Http\Admin\Requests\AdminStoreServicePageRequest;
use App\Interfaces\Http\Admin\Requests\AdminUpdateServicePageRequest;
use App\Interfaces\Http\Admin\Resources\AdminServicePageResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

final class AdminServicePageController
{
    public function index(AdminServicePageListRequest $request, ListAdminServicePagesUseCase $useCase): JsonResponse
    {
        $dto = new AdminServicePageListDTO(
            page: (int) $request->integer('page', 1),
            perPage: (int) $request->integer('per_page', 20),
            search: $request->filled('search') ? (string) $request->string('search') : null,
            searchBy: $request->filled('search_by') ? (string) $request->string('search_by') : null,
            categoryId: $request->filled('category_id') ? (int) $request->integer('category_id') : null,
            isActive: $request->has('is_active') ? (bool) $request->boolean('is_active') : null,
            isFeatured: $request->has('is_featured') ? (bool) $request->boolean('is_featured') : null,
        );

        $result = $useCase->execute($dto);
        $items = array_map(function (array $item) use ($request): array {
            $resource = new AdminServicePageResource($item);
            $resolved = $resource->toArray($request);

            return (array) ($resolved['data'] ?? []);
        }, $result['items']);

        return response()->json([
            'data' => $items,
            'meta' => [
                'version' => config('app.api_version', '1.0.0'),
                'timestamp' => now()->toIso8601String(),
                'message' => 'Lay danh sach service pages thanh cong',
                'pagination' => $result['pagination'],
            ],
        ]);
    }

    public function show(int $id, GetAdminServicePageDetailUseCase $useCase, Request $request): JsonResponse
    {
        $item = $useCase->execute($id);
        $resource = (new AdminServicePageResource($item))->withMeta([
            'message' => 'Lay chi tiet service page thanh cong',
        ]);

        return response()->json($resource->toArray($request));
    }

    public function store(AdminStoreServicePageRequest $request, CreateAdminServicePageUseCase $useCase): JsonResponse
    {
        $dto = new AdminUpsertServicePageDTO(
            categoryId: (int) $request->integer('category_id'),
            name: (string) $request->string('name'),
            thumbnail: $request->filled('thumbnail') ? (string) $request->string('thumbnail') : null,
            excerpt: $request->filled('excerpt') ? (string) $request->input('excerpt') : null,
            content: (string) $request->input('content'),
            isFeatured: (bool) $request->boolean('is_featured', false),
            isActive: (bool) $request->boolean('is_active', true),
            sortOrder: (int) $request->integer('sort_order', 0),
            metaTitle: $request->filled('meta_title') ? (string) $request->string('meta_title') : null,
            metaDescription: $request->filled('meta_description') ? (string) $request->string('meta_description') : null,
            actorId: null,
        );

        $item = $useCase->execute($dto);
        $resource = (new AdminServicePageResource($item))->withMeta([
            'message' => 'Tao service page thanh cong',
        ]);

        return response()->json($resource->toArray($request), 201);
    }

    public function update(int $id, AdminUpdateServicePageRequest $request, UpdateAdminServicePageUseCase $useCase): JsonResponse
    {
        $dto = new AdminUpsertServicePageDTO(
            categoryId: (int) $request->integer('category_id'),
            name: (string) $request->string('name'),
            thumbnail: $request->filled('thumbnail') ? (string) $request->string('thumbnail') : null,
            excerpt: $request->filled('excerpt') ? (string) $request->input('excerpt') : null,
            content: (string) $request->input('content'),
            isFeatured: (bool) $request->boolean('is_featured', false),
            isActive: (bool) $request->boolean('is_active', true),
            sortOrder: (int) $request->integer('sort_order', 0),
            metaTitle: $request->filled('meta_title') ? (string) $request->string('meta_title') : null,
            metaDescription: $request->filled('meta_description') ? (string) $request->string('meta_description') : null,
            actorId: null,
        );

        $item = $useCase->execute($id, $dto);
        $resource = (new AdminServicePageResource($item))->withMeta([
            'message' => 'Cap nhat service page thanh cong',
        ]);

        return response()->json($resource->toArray($request));
    }

    public function destroy(int $id, DeleteAdminServicePageUseCase $useCase): JsonResponse
    {
        $useCase->execute($id);

        return response()->json([
            'data' => null,
            'meta' => [
                'version' => config('app.api_version', '1.0.0'),
                'timestamp' => now()->toIso8601String(),
                'message' => 'Xoa service page thanh cong',
            ],
        ]);
    }
}
