<?php

declare(strict_types=1);

namespace App\Interfaces\Http\Admin\Controllers;

use App\Application\Admin\DTOs\AdminContactListDTO;
use App\Application\Admin\DTOs\AdminUpsertContactDTO;
use App\Application\Admin\UseCases\CreateAdminContactUseCase;
use App\Application\Admin\UseCases\DeleteAdminContactUseCase;
use App\Application\Admin\UseCases\GetAdminContactDetailUseCase;
use App\Application\Admin\UseCases\ListAdminContactsUseCase;
use App\Application\Admin\UseCases\UpdateAdminContactUseCase;
use App\Interfaces\Http\Admin\Requests\AdminContactListRequest;
use App\Interfaces\Http\Admin\Requests\AdminStoreContactRequest;
use App\Interfaces\Http\Admin\Requests\AdminUpdateContactRequest;
use App\Interfaces\Http\Admin\Resources\AdminContactResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

final class AdminContactController
{
    public function index(AdminContactListRequest $request, ListAdminContactsUseCase $useCase): JsonResponse
    {
        $dto = new AdminContactListDTO(
            page: (int) $request->integer('page', 1),
            perPage: (int) $request->integer('per_page', 20),
            search: $request->filled('search') ? (string) $request->string('search') : null,
            searchBy: $request->filled('search_by') ? (string) $request->string('search_by') : null,
            status: $request->filled('status') ? (string) $request->string('status') : null,
            resolvedBy: $request->filled('resolved_by') ? (int) $request->integer('resolved_by') : null,
        );

        $result = $useCase->execute($dto);
        $items = array_map(function (array $item) use ($request): array {
            $resource = new AdminContactResource($item);
            $resolved = $resource->toArray($request);

            return (array) ($resolved['data'] ?? []);
        }, $result['items']);

        return response()->json([
            'data' => $items,
            'meta' => [
                'version' => config('app.api_version', '1.0.0'),
                'timestamp' => now()->toIso8601String(),
                'message' => 'Lay danh sach contacts thanh cong',
                'pagination' => $result['pagination'],
            ],
        ]);
    }

    public function show(int $id, GetAdminContactDetailUseCase $useCase, Request $request): JsonResponse
    {
        $item = $useCase->execute($id);
        $resource = (new AdminContactResource($item))->withMeta(['message' => 'Lay chi tiet contact thanh cong']);

        return response()->json($resource->toArray($request));
    }

    public function store(AdminStoreContactRequest $request, CreateAdminContactUseCase $useCase): JsonResponse
    {
        $dto = new AdminUpsertContactDTO(
            fullName: (string) $request->string('full_name'),
            email: (string) $request->string('email'),
            phone: $request->filled('phone') ? (string) $request->string('phone') : null,
            subject: $request->filled('subject') ? (string) $request->string('subject') : null,
            message: (string) $request->input('message'),
            status: $request->filled('status') ? (string) $request->string('status') : null,
            adminNote: $request->filled('admin_note') ? (string) $request->input('admin_note') : null,
            resolvedBy: $request->filled('resolved_by') ? (int) $request->integer('resolved_by') : null,
            resolvedAt: $request->filled('resolved_at') ? (string) $request->input('resolved_at') : null,
            ipAddress: $request->filled('ip_address') ? (string) $request->input('ip_address') : $request->ip(),
        );

        $item = $useCase->execute($dto);
        $resource = (new AdminContactResource($item))->withMeta(['message' => 'Tao contact thanh cong']);

        return response()->json($resource->toArray($request), 201);
    }

    public function update(int $id, AdminUpdateContactRequest $request, UpdateAdminContactUseCase $useCase): JsonResponse
    {
        $dto = new AdminUpsertContactDTO(
            fullName: (string) $request->string('full_name'),
            email: (string) $request->string('email'),
            phone: $request->filled('phone') ? (string) $request->string('phone') : null,
            subject: $request->filled('subject') ? (string) $request->string('subject') : null,
            message: (string) $request->input('message'),
            status: $request->filled('status') ? (string) $request->string('status') : null,
            adminNote: $request->filled('admin_note') ? (string) $request->input('admin_note') : null,
            resolvedBy: $request->filled('resolved_by') ? (int) $request->integer('resolved_by') : null,
            resolvedAt: $request->filled('resolved_at') ? (string) $request->input('resolved_at') : null,
            ipAddress: $request->filled('ip_address') ? (string) $request->input('ip_address') : $request->ip(),
        );

        $item = $useCase->execute($id, $dto);
        $resource = (new AdminContactResource($item))->withMeta(['message' => 'Cap nhat contact thanh cong']);

        return response()->json($resource->toArray($request));
    }

    public function destroy(int $id, DeleteAdminContactUseCase $useCase): JsonResponse
    {
        $useCase->execute($id);

        return response()->json([
            'data' => null,
            'meta' => [
                'version' => config('app.api_version', '1.0.0'),
                'timestamp' => now()->toIso8601String(),
                'message' => 'Xoa contact thanh cong',
            ],
        ]);
    }
}
