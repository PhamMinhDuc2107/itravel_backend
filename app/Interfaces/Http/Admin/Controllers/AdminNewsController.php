<?php

declare(strict_types=1);

namespace App\Interfaces\Http\Admin\Controllers;

use App\Application\Admin\DTOs\AdminNewsListDTO;
use App\Application\Admin\DTOs\AdminUpsertNewsDTO;
use App\Application\Admin\UseCases\CreateAdminNewsUseCase;
use App\Application\Admin\UseCases\DeleteAdminNewsUseCase;
use App\Application\Admin\UseCases\GetAdminNewsDetailUseCase;
use App\Application\Admin\UseCases\ListAdminNewsUseCase;
use App\Application\Admin\UseCases\UpdateAdminNewsUseCase;
use App\Interfaces\Http\Admin\Requests\AdminNewsListRequest;
use App\Interfaces\Http\Admin\Requests\AdminStoreNewsRequest;
use App\Interfaces\Http\Admin\Requests\AdminUpdateNewsRequest;
use App\Interfaces\Http\Admin\Resources\AdminNewsResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

final class AdminNewsController
{
    public function index(AdminNewsListRequest $request, ListAdminNewsUseCase $useCase): JsonResponse
    {
        $dto = new AdminNewsListDTO(
            page: (int) $request->integer('page', 1),
            perPage: (int) $request->integer('per_page', 20),
            search: $request->filled('search') ? (string) $request->string('search') : null,
            searchBy: $request->filled('search_by') ? (string) $request->string('search_by') : null,
            newsCategoryId: $request->filled('news_category_id') ? (int) $request->integer('news_category_id') : null,
            status: $request->filled('status') ? (string) $request->string('status') : null,
            isFeatured: $request->has('is_featured') ? (bool) $request->boolean('is_featured') : null,
        );

        $result = $useCase->execute($dto);
        $items = array_map(function (array $item) use ($request): array {
            $resource = new AdminNewsResource($item);
            $resolved = $resource->toArray($request);

            return (array) ($resolved['data'] ?? []);
        }, $result['items']);

        return response()->json([
            'data' => $items,
            'meta' => [
                'version' => config('app.api_version', '1.0.0'),
                'timestamp' => now()->toIso8601String(),
                'message' => 'Lay danh sach news thanh cong',
                'pagination' => $result['pagination'],
            ],
        ]);
    }

    public function show(int $id, GetAdminNewsDetailUseCase $useCase, Request $request): JsonResponse
    {
        $item = $useCase->execute($id);
        $resource = (new AdminNewsResource($item))->withMeta([
            'message' => 'Lay chi tiet news thanh cong',
        ]);

        return response()->json($resource->toArray($request));
    }

    public function store(AdminStoreNewsRequest $request, CreateAdminNewsUseCase $useCase): JsonResponse
    {
        $dto = new AdminUpsertNewsDTO(
            newsCategoryId: (int) $request->integer('news_category_id'),
            authorId: $request->filled('author_id') ? (int) $request->integer('author_id') : null,
            title: (string) $request->string('title'),
            thumbnail: $request->filled('thumbnail') ? (string) $request->string('thumbnail') : null,
            excerpt: $request->filled('excerpt') ? (string) $request->input('excerpt') : null,
            content: (string) $request->input('content'),
            status: $request->filled('status') ? (string) $request->string('status') : 'draft',
            isFeatured: (bool) $request->boolean('is_featured', false),
            viewCount: (int) $request->integer('view_count', 0),
            metaTitle: $request->filled('meta_title') ? (string) $request->string('meta_title') : null,
            metaDescription: $request->filled('meta_description') ? (string) $request->string('meta_description') : null,
            publishedAt: $request->filled('published_at') ? (string) $request->input('published_at') : null,
        );

        $item = $useCase->execute($dto);
        $resource = (new AdminNewsResource($item))->withMeta([
            'message' => 'Tao news thanh cong',
        ]);

        return response()->json($resource->toArray($request), 201);
    }

    public function update(int $id, AdminUpdateNewsRequest $request, UpdateAdminNewsUseCase $useCase): JsonResponse
    {
        $dto = new AdminUpsertNewsDTO(
            newsCategoryId: (int) $request->integer('news_category_id'),
            authorId: $request->filled('author_id') ? (int) $request->integer('author_id') : null,
            title: (string) $request->string('title'),
            thumbnail: $request->filled('thumbnail') ? (string) $request->string('thumbnail') : null,
            excerpt: $request->filled('excerpt') ? (string) $request->input('excerpt') : null,
            content: (string) $request->input('content'),
            status: $request->filled('status') ? (string) $request->string('status') : 'draft',
            isFeatured: (bool) $request->boolean('is_featured', false),
            viewCount: (int) $request->integer('view_count', 0),
            metaTitle: $request->filled('meta_title') ? (string) $request->string('meta_title') : null,
            metaDescription: $request->filled('meta_description') ? (string) $request->string('meta_description') : null,
            publishedAt: $request->filled('published_at') ? (string) $request->input('published_at') : null,
        );

        $item = $useCase->execute($id, $dto);
        $resource = (new AdminNewsResource($item))->withMeta([
            'message' => 'Cap nhat news thanh cong',
        ]);

        return response()->json($resource->toArray($request));
    }

    public function destroy(int $id, DeleteAdminNewsUseCase $useCase): JsonResponse
    {
        $useCase->execute($id);

        return response()->json([
            'data' => null,
            'meta' => [
                'version' => config('app.api_version', '1.0.0'),
                'timestamp' => now()->toIso8601String(),
                'message' => 'Xoa news thanh cong',
            ],
        ]);
    }
}
