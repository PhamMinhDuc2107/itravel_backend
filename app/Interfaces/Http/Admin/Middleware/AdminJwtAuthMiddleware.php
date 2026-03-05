<?php

declare(strict_types=1);

namespace App\Interfaces\Http\Admin\Middleware;

use App\Domain\Exceptions\UnauthorizedException;
use App\Domain\Repositories\AdminRepositoryInterface;
use App\Infrastructure\Services\Contracts\JwtServiceInterface;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminJwtAuthMiddleware
{
    public function __construct(
        private readonly JwtServiceInterface $jwtService,
        private readonly AdminRepositoryInterface $adminRepository,
    ) {}

    public function handle(Request $request, Closure $next): Response
    {
        $authHeader = $request->header('Authorization', '');
        if (!preg_match('/^Bearer\s+(.+)$/i', $authHeader, $matches)) {
            throw new UnauthorizedException('Vui long cung cap Bearer token');
        }

        $token = trim($matches[1]);
        $payload = $this->jwtService->validateAccessToken($token);
        $adminId = $this->jwtService->getAdminIdFromToken($token);
        $admin = $this->adminRepository->findActiveById($adminId);

        if ($admin === null) {
            throw new UnauthorizedException('Admin khong ton tai hoac da bi khoa');
        }

        $request->attributes->set('admin_id', $adminId);
        $request->attributes->set('admin', $admin);
        $request->attributes->set('token_payload', $payload);

        return $next($request);
    }
}
