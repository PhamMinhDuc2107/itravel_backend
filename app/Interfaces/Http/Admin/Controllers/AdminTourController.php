<?php

declare(strict_types=1);

namespace App\Interfaces\Http\Admin\Controllers;

use App\Application\Admin\DTOs\AdminTourListDTO;
use App\Application\Admin\DTOs\AdminUpsertTourDTO;
use App\Application\Admin\UseCases\CreateAdminTourUseCase;
use App\Application\Admin\UseCases\DeleteAdminTourUseCase;
use App\Application\Admin\UseCases\GetAdminTourDetailUseCase;
use App\Application\Admin\UseCases\ListAdminToursUseCase;
use App\Application\Admin\UseCases\UpdateAdminTourUseCase;
use App\Infrastructure\Services\Contracts\FileStorageServiceInterface;
use App\Interfaces\Http\Admin\Requests\AdminStoreTourRequest;
use App\Interfaces\Http\Admin\Requests\AdminTourListRequest;
use App\Interfaces\Http\Admin\Requests\AdminUpdateTourRequest;
use App\Interfaces\Http\Admin\Resources\AdminTourResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;

final class AdminTourController
{
    public function __construct(private readonly FileStorageServiceInterface $fileStorageService) {}

    public function index(AdminTourListRequest $request, ListAdminToursUseCase $useCase): JsonResponse
    {
        $dto = new AdminTourListDTO(
            page: (int) $request->integer('page', 1),
            perPage: (int) $request->integer('per_page', 20),
            search: $request->filled('search') ? (string) $request->string('search') : null,
            searchBy: $request->filled('search_by') ? (string) $request->string('search_by') : null,
            categoryId: $request->filled('category_id') ? (int) $request->integer('category_id') : null,
            status: $request->filled('status') ? (string) $request->string('status') : null,
            isFeatured: $request->has('is_featured') ? (bool) $request->boolean('is_featured') : null,
            isHot: $request->has('is_hot') ? (bool) $request->boolean('is_hot') : null,
        );

        $result = $useCase->execute($dto);
        $items = array_map(function (array $item) use ($request): array {
            $resource = new AdminTourResource($item);
            $resolved = $resource->toArray($request);

            return (array) ($resolved['data'] ?? []);
        }, $result['items']);

        return response()->json([
            'data' => $items,
            'meta' => [
                'version' => config('app.api_version', '1.0.0'),
                'timestamp' => now()->toIso8601String(),
                'message' => 'Lay danh sach tours thanh cong',
                'pagination' => $result['pagination'],
            ],
        ]);
    }

    public function show(int $id, GetAdminTourDetailUseCase $useCase, Request $request): JsonResponse
    {
        $item = $useCase->execute($id);
        $resource = (new AdminTourResource($item))->withMeta([
            'message' => 'Lay chi tiet tour thanh cong',
        ]);

        return response()->json($resource->toArray($request));
    }

    public function store(AdminStoreTourRequest $request, CreateAdminTourUseCase $useCase): JsonResponse
    {
        $thumbnail = $this->resolveThumbnail($request, 'tours/thumbnails');
        $tourImages = $this->resolveTourImages($request);
        $tourPriceOverrides = $this->resolveTourPriceOverrides($request);
        $tourItineraries = $this->resolveTourItineraries($request);
        $tourNotes = $this->resolveTourNotes($request);
        $tourPrices = $this->resolveTourPrices($request);
        $tourSchedules = $this->resolveTourSchedules($request);
        $tourLocations = $this->resolveTourLocations($request);

        $dto = new AdminUpsertTourDTO(
            categoryId: (int) $request->integer('category_id'),
            code: $request->filled('code') ? (string) $request->string('code') : null,
            title: (string) $request->string('title'),
            thumbnail: $thumbnail,
            description: $request->filled('description') ? (string) $request->input('description') : null,
            durationDays: $request->filled('duration_days') ? (int) $request->integer('duration_days') : null,
            durationNights: $request->filled('duration_nights') ? (int) $request->integer('duration_nights') : null,
            departureFrom: $request->filled('departure_from') ? (string) $request->string('departure_from') : null,
            destination: $request->filled('destination') ? (string) $request->string('destination') : null,
            attractions: $request->filled('attractions') ? (string) $request->input('attractions') : null,
            cuisine: $request->filled('cuisine') ? (string) $request->input('cuisine') : null,
            suitableFor: $request->filled('suitable_for') ? (string) $request->input('suitable_for') : null,
            status: $request->filled('status') ? (string) $request->string('status') : null,
            isFeatured: (bool) $request->boolean('is_featured', false),
            isHot: (bool) $request->boolean('is_hot', false),
            viewCount: (int) $request->integer('view_count', 0),
            metaTitle: $request->filled('meta_title') ? (string) $request->string('meta_title') : null,
            metaDescription: $request->filled('meta_description') ? (string) $request->string('meta_description') : null,
            actorId: null,
            tourImages: $tourImages,
            tourPriceOverrides: $tourPriceOverrides,
            tourItineraries: $tourItineraries,
            tourNotes: $tourNotes,
            tourPrices: $tourPrices,
            tourSchedules: $tourSchedules,
            tourLocations: $tourLocations,
        );

        $item = $useCase->execute($dto);
        $resource = (new AdminTourResource($item))->withMeta([
            'message' => 'Tao tour thanh cong',
        ]);

        return response()->json($resource->toArray($request), 201);
    }

    public function update(int $id, AdminUpdateTourRequest $request, UpdateAdminTourUseCase $useCase): JsonResponse
    {
        $thumbnail = $this->resolveThumbnail($request, 'tours/thumbnails');
        $tourImages = $this->resolveTourImages($request);
        $tourPriceOverrides = $this->resolveTourPriceOverrides($request);
        $tourItineraries = $this->resolveTourItineraries($request);
        $tourNotes = $this->resolveTourNotes($request);
        $tourPrices = $this->resolveTourPrices($request);
        $tourSchedules = $this->resolveTourSchedules($request);
        $tourLocations = $this->resolveTourLocations($request);

        $dto = new AdminUpsertTourDTO(
            categoryId: (int) $request->integer('category_id'),
            code: $request->filled('code') ? (string) $request->string('code') : null,
            title: (string) $request->string('title'),
            thumbnail: $thumbnail,
            description: $request->filled('description') ? (string) $request->input('description') : null,
            durationDays: $request->filled('duration_days') ? (int) $request->integer('duration_days') : null,
            durationNights: $request->filled('duration_nights') ? (int) $request->integer('duration_nights') : null,
            departureFrom: $request->filled('departure_from') ? (string) $request->string('departure_from') : null,
            destination: $request->filled('destination') ? (string) $request->string('destination') : null,
            attractions: $request->filled('attractions') ? (string) $request->input('attractions') : null,
            cuisine: $request->filled('cuisine') ? (string) $request->input('cuisine') : null,
            suitableFor: $request->filled('suitable_for') ? (string) $request->input('suitable_for') : null,
            status: $request->filled('status') ? (string) $request->string('status') : null,
            isFeatured: (bool) $request->boolean('is_featured', false),
            isHot: (bool) $request->boolean('is_hot', false),
            viewCount: (int) $request->integer('view_count', 0),
            metaTitle: $request->filled('meta_title') ? (string) $request->string('meta_title') : null,
            metaDescription: $request->filled('meta_description') ? (string) $request->string('meta_description') : null,
            actorId: null,
            tourImages: $tourImages,
            tourPriceOverrides: $tourPriceOverrides,
            tourItineraries: $tourItineraries,
            tourNotes: $tourNotes,
            tourPrices: $tourPrices,
            tourSchedules: $tourSchedules,
            tourLocations: $tourLocations,
        );

        $item = $useCase->execute($id, $dto);
        $resource = (new AdminTourResource($item))->withMeta([
            'message' => 'Cap nhat tour thanh cong',
        ]);

        return response()->json($resource->toArray($request));
    }

    public function destroy(int $id, DeleteAdminTourUseCase $useCase): JsonResponse
    {
        $useCase->execute($id);

        return response()->json([
            'data' => null,
            'meta' => [
                'version' => config('app.api_version', '1.0.0'),
                'timestamp' => now()->toIso8601String(),
                'message' => 'Xoa tour thanh cong',
            ],
        ]);
    }

    /**
     * @return array<int, array{url: string, alt: string|null, is_cover: bool, sort: int}>|null
     */
    private function resolveTourImages(Request $request): ?array
    {
        if (!$request->has('tour_images')) {
            return null;
        }

        $inputItems = (array) $request->input('tour_images', []);
        $fileItems = (array) $request->file('tour_images', []);
        $normalized = [];

        $indexes = array_values(array_unique(array_merge(array_keys($inputItems), array_keys($fileItems))));

        foreach ($indexes as $index) {
            $item = $inputItems[$index] ?? [];
            $input = is_array($item) ? $item : [];
            $file = is_array($fileItems[$index] ?? null)
                ? ($fileItems[$index]['file'] ?? null)
                : null;

            $url = null;
            if ($file instanceof UploadedFile) {
                $url = $this->uploadImage($file, 'tours/images');
            } elseif (isset($input['url']) && is_string($input['url']) && $input['url'] !== '') {
                $url = $input['url'];
            }

            if ($url === null) {
                continue;
            }

            $normalized[] = [
                'url' => $url,
                'alt' => isset($input['alt']) && is_string($input['alt']) && $input['alt'] !== '' ? $input['alt'] : null,
                'is_cover' => isset($input['is_cover']) ? (bool) $input['is_cover'] : false,
                'sort' => isset($input['sort']) ? (int) $input['sort'] : 0,
            ];
        }

        return $normalized;
    }

    /**
     * @return array<int, array{tour_schedule_id: int, departure_date: string, adult_price: int|null, child_price: int|null, infant_price: int|null, is_active: bool, note: string|null}>|null
     */
    private function resolveTourPriceOverrides(Request $request): ?array
    {
        $hasOverrides = $request->has('tour_price_overrides') || $request->has('tour_overrides');
        if (!$hasOverrides) {
            return null;
        }

        $items = (array) ($request->input('tour_price_overrides') ?? $request->input('tour_overrides') ?? []);
        $normalized = [];

        foreach ($items as $item) {
            if (!is_array($item)) {
                continue;
            }

            $departureDate = isset($item['departure_date']) ? (string) $item['departure_date'] : '';
            if ($departureDate === '') {
                continue;
            }

            $normalized[] = [
                'tour_schedule_id' => (int) ($item['tour_schedule_id'] ?? 0),
                'departure_date' => $departureDate,
                'adult_price' => isset($item['adult_price']) ? (int) $item['adult_price'] : null,
                'child_price' => isset($item['child_price']) ? (int) $item['child_price'] : null,
                'infant_price' => isset($item['infant_price']) ? (int) $item['infant_price'] : null,
                'is_active' => isset($item['is_active']) ? (bool) $item['is_active'] : true,
                'note' => isset($item['note']) && is_string($item['note']) ? $item['note'] : null,
            ];
        }

        return $normalized;
    }

    /** @return array<int, array{day_number: int, title: string, content: string|null}>|null */
    private function resolveTourItineraries(Request $request): ?array
    {
        if (!$request->has('tour_itineraries')) {
            return null;
        }

        $items = (array) $request->input('tour_itineraries', []);

        return array_values(array_map(static fn(array $item): array => [
            'day_number' => (int) ($item['day_number'] ?? 1),
            'title' => (string) ($item['title'] ?? ''),
            'content' => isset($item['content']) && is_string($item['content']) ? $item['content'] : null,
        ], array_filter($items, static fn($item): bool => is_array($item))));
    }

    /** @return array<int, array{title: string, content: string|null, sort: int}>|null */
    private function resolveTourNotes(Request $request): ?array
    {
        if (!$request->has('tour_notes')) {
            return null;
        }

        $items = (array) $request->input('tour_notes', []);

        return array_values(array_map(static fn(array $item): array => [
            'title' => (string) ($item['title'] ?? ''),
            'content' => isset($item['content']) && is_string($item['content']) ? $item['content'] : null,
            'sort' => (int) ($item['sort'] ?? 0),
        ], array_filter($items, static fn($item): bool => is_array($item))));
    }

    /** @return array<int, array{passenger_type: int, price: int, currency: string, includes: string|null, excludes: string|null}>|null */
    private function resolveTourPrices(Request $request): ?array
    {
        if (!$request->has('tour_prices')) {
            return null;
        }

        $items = (array) $request->input('tour_prices', []);

        return array_values(array_map(static fn(array $item): array => [
            'passenger_type' => (int) ($item['passenger_type'] ?? 0),
            'price' => (int) ($item['price'] ?? 0),
            'currency' => isset($item['currency']) && is_string($item['currency']) && $item['currency'] !== '' ? $item['currency'] : 'VND',
            'includes' => isset($item['includes']) && is_string($item['includes']) ? $item['includes'] : null,
            'excludes' => isset($item['excludes']) && is_string($item['excludes']) ? $item['excludes'] : null,
        ], array_filter($items, static fn($item): bool => is_array($item))));
    }

    /** @return array<int, array{departure_date: string, return_date: string|null, max_slots: int, booked_slots: int, status: int, note: string|null}>|null */
    private function resolveTourSchedules(Request $request): ?array
    {
        if (!$request->has('tour_schedules')) {
            return null;
        }

        $items = (array) $request->input('tour_schedules', []);

        return array_values(array_map(static fn(array $item): array => [
            'departure_date' => (string) ($item['departure_date'] ?? ''),
            'return_date' => isset($item['return_date']) && is_string($item['return_date']) && $item['return_date'] !== '' ? $item['return_date'] : null,
            'max_slots' => (int) ($item['max_slots'] ?? 0),
            'booked_slots' => (int) ($item['booked_slots'] ?? 0),
            'status' => (int) ($item['status'] ?? 0),
            'note' => isset($item['note']) && is_string($item['note']) ? $item['note'] : null,
        ], array_filter($items, static fn($item): bool => is_array($item))));
    }

    /** @return array<int, array{location_id: int, role: int, sort: int}>|null */
    private function resolveTourLocations(Request $request): ?array
    {
        if (!$request->has('tour_locations')) {
            return null;
        }

        $items = (array) $request->input('tour_locations', []);

        return array_values(array_map(static fn(array $item): array => [
            'location_id' => (int) ($item['location_id'] ?? 0),
            'role' => (int) ($item['role'] ?? 1),
            'sort' => (int) ($item['sort'] ?? 0),
        ], array_filter($items, static fn($item): bool => is_array($item))));
    }

    private function uploadImage(UploadedFile $file, string $folder): string
    {
        $path = $this->fileStorageService->upload($file, $folder, 'public');

        return $this->fileStorageService->getUrl($path, 'public');
    }

    private function resolveThumbnail(Request $request, string $folder): ?string
    {
        $thumbnailFile = $request->file('thumbnail_file');
        if ($thumbnailFile instanceof UploadedFile) {
            return $this->uploadImage($thumbnailFile, $folder);
        }

        return $request->filled('thumbnail') ? (string) $request->string('thumbnail') : null;
    }
}
