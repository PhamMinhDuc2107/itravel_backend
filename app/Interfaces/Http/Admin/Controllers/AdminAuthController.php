<?php

declare(strict_types=1);

namespace App\Interfaces\Http\Admin\Controllers;

use App\Application\Admin\DTOs\AdminLoginDTO;
use App\Application\Admin\DTOs\AdminLogoutDTO;
use App\Application\Admin\DTOs\AdminRefreshTokenDTO;
use App\Application\Admin\UseCases\AdminLoginUseCase;
use App\Application\Admin\UseCases\AdminLogoutUseCase;
use App\Application\Admin\UseCases\AdminRefreshTokenUseCase;
use App\Application\Admin\UseCases\GetAdminProfileUseCase;
use App\Interfaces\Http\Admin\Requests\AdminLoginRequest;
use App\Interfaces\Http\Admin\Requests\AdminLogoutRequest;
use App\Interfaces\Http\Admin\Requests\AdminRefreshTokenRequest;
use App\Interfaces\Http\Admin\Resources\AdminAuthResource;
use App\Interfaces\Http\Admin\Resources\AdminProfileResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AdminAuthController
{
    public function login(AdminLoginRequest $request, AdminLoginUseCase $useCase): JsonResponse
    {
        $dto = new AdminLoginDTO(
            email: (string) $request->string('email'),
            password: (string) $request->string('password'),
            userAgent: $request->userAgent(),
            ipAddress: $request->ip(),
            context: (array) $request->input('context', []),
        );

        $result = $useCase->execute($dto);

        $resource = (new AdminAuthResource($result))->withMeta([
            'message' => 'Dang nhap admin thanh cong',
        ]);

        return response()->json($resource->toArray($request));
    }

    public function refresh(AdminRefreshTokenRequest $request, AdminRefreshTokenUseCase $useCase): JsonResponse
    {
        $dto = new AdminRefreshTokenDTO(
            adminId: (int) $request->integer('admin_id'),
            refreshToken: (string) $request->string('refresh_token'),
            data: (array) $request->input('data', []),
            context: (array) $request->input('context', []),
        );

        $result = $useCase->execute($dto);

        $resource = (new AdminAuthResource($result))->withMeta([
            'message' => 'Lam moi token thanh cong',
        ]);

        return response()->json($resource->toArray($request));
    }

    public function me(Request $request, GetAdminProfileUseCase $useCase): JsonResponse
    {
        $adminId = (int) $request->attributes->get('admin_id');
        $admin = $useCase->execute($adminId);

        $resource = (new AdminProfileResource($admin))->withMeta([
            'message' => 'Lay thong tin admin thanh cong',
        ]);

        return response()->json($resource->toArray($request));
    }

    public function logout(AdminLogoutRequest $request, AdminLogoutUseCase $useCase): JsonResponse
    {
        $dto = new AdminLogoutDTO(
            adminId: (int) $request->attributes->get('admin_id'),
            refreshToken: $request->input('refresh_token'),
        );

        $useCase->execute($dto);

        return response()->json([
            'data' => null,
            'meta' => [
                'message' => 'Dang xuat admin thanh cong',
            ],
        ]);
    }
}
