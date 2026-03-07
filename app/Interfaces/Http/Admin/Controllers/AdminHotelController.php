<?php

declare(strict_types=1);

namespace App\Interfaces\Http\Admin\Controllers;

use App\Application\Admin\DTOs\AdminHotelListDTO;
use App\Application\Admin\DTOs\AdminUpsertHotelDTO;
use App\Application\Admin\UseCases\CreateAdminHotelUseCase;
use App\Application\Admin\UseCases\DeleteAdminHotelUseCase;
use App\Application\Admin\UseCases\GetAdminHotelDetailUseCase;
use App\Application\Admin\UseCases\ListAdminHotelsUseCase;
use App\Application\Admin\UseCases\UpdateAdminHotelUseCase;
use App\Infrastructure\Services\Contracts\FileStorageServiceInterface;
use App\Interfaces\Http\Admin\Requests\AdminHotelListRequest;
use App\Interfaces\Http\Admin\Requests\AdminStoreHotelRequest;
use App\Interfaces\Http\Admin\Requests\AdminUpdateHotelRequest;
use App\Interfaces\Http\Admin\Resources\AdminHotelResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;

final class AdminHotelController
{
    public function __construct(private readonly FileStorageServiceInterface $fileStorageService) {}

    public function index(AdminHotelListRequest $request, ListAdminHotelsUseCase $useCase): JsonResponse
    {
        $dto = new AdminHotelListDTO(
            page: (int) $request->integer('page', 1),
            perPage: (int) $request->integer('per_page', 20),
            search: $request->filled('search') ? (string) $request->string('search') : null,
            searchBy: $request->filled('search_by') ? (string) $request->string('search_by') : null,
            locationId: $request->filled('location_id') ? (int) $request->integer('location_id') : null,
            hotelTypeId: $request->filled('hotel_type_id') ? (int) $request->integer('hotel_type_id') : null,
            isActive: $request->has('is_active') ? (bool) $request->boolean('is_active') : null,
            isFeatured: $request->has('is_featured') ? (bool) $request->boolean('is_featured') : null,
        );

        $result = $useCase->execute($dto);
        $items = array_map(function (array $item) use ($request): array {
            $resource = new AdminHotelResource($item);
            $resolved = $resource->toArray($request);

            return (array) ($resolved['data'] ?? []);
        }, $result['items']);

        return response()->json([
            'data' => $items,
            'meta' => [
                'version' => config('app.api_version', '1.0.0'),
                'timestamp' => now()->toIso8601String(),
                'message' => 'Lay danh sach hotels thanh cong',
                'pagination' => $result['pagination'],
            ],
        ]);
    }

    public function show(int $id, GetAdminHotelDetailUseCase $useCase, Request $request): JsonResponse
    {
        $item = $useCase->execute($id);
        $resource = (new AdminHotelResource($item))->withMeta([
            'message' => 'Lay chi tiet hotel thanh cong',
        ]);

        return response()->json($resource->toArray($request));
    }

    public function store(AdminStoreHotelRequest $request, CreateAdminHotelUseCase $useCase): JsonResponse
    {
        $thumbnail = $this->resolveThumbnail($request, 'hotels/thumbnails');
        $hotelImages = $this->resolveHotelImages($request);
        $amenityIds = $this->resolveAmenityIds($request);

        $dto = new AdminUpsertHotelDTO(
            locationId: (int) $request->integer('location_id'),
            hotelTypeId: (int) $request->integer('hotel_type_id'),
            name: (string) $request->string('name'),
            thumbnail: $thumbnail,
            starRating: $request->filled('star_rating') ? (int) $request->integer('star_rating') : null,
            address: $request->filled('address') ? (string) $request->string('address') : null,
            ward: $request->filled('ward') ? (string) $request->string('ward') : null,
            district: $request->filled('district') ? (string) $request->string('district') : null,
            latitude: $request->filled('latitude') ? (string) $request->input('latitude') : null,
            longitude: $request->filled('longitude') ? (string) $request->input('longitude') : null,
            googleMapUrl: $request->filled('google_map_url') ? (string) $request->string('google_map_url') : null,
            description: $request->filled('description') ? (string) $request->input('description') : null,
            priceFrom: $request->filled('price_from') ? (string) $request->input('price_from') : null,
            isFreeCancel: (bool) $request->boolean('is_free_cancel', false),
            isPayLater: (bool) $request->boolean('is_pay_later', false),
            isFeatured: (bool) $request->boolean('is_featured', false),
            isActive: (bool) $request->boolean('is_active', true),
            ratingScore: $request->filled('rating_score') ? (string) $request->input('rating_score') : null,
            ratingCount: (int) $request->integer('rating_count', 0),
            metaTitle: $request->filled('meta_title') ? (string) $request->string('meta_title') : null,
            metaDescription: $request->filled('meta_description') ? (string) $request->string('meta_description') : null,
            actorId: null,
            hotelImages: $hotelImages,
            amenityIds: $amenityIds,
        );

        $item = $useCase->execute($dto);
        $resource = (new AdminHotelResource($item))->withMeta([
            'message' => 'Tao hotel thanh cong',
        ]);

        return response()->json($resource->toArray($request), 201);
    }

    public function update(int $id, AdminUpdateHotelRequest $request, UpdateAdminHotelUseCase $useCase): JsonResponse
    {
        $thumbnail = $this->resolveThumbnail($request, 'hotels/thumbnails');
        $hotelImages = $this->resolveHotelImages($request);
        $amenityIds = $this->resolveAmenityIds($request);

        $dto = new AdminUpsertHotelDTO(
            locationId: (int) $request->integer('location_id'),
            hotelTypeId: (int) $request->integer('hotel_type_id'),
            name: (string) $request->string('name'),
            thumbnail: $thumbnail,
            starRating: $request->filled('star_rating') ? (int) $request->integer('star_rating') : null,
            address: $request->filled('address') ? (string) $request->string('address') : null,
            ward: $request->filled('ward') ? (string) $request->string('ward') : null,
            district: $request->filled('district') ? (string) $request->string('district') : null,
            latitude: $request->filled('latitude') ? (string) $request->input('latitude') : null,
            longitude: $request->filled('longitude') ? (string) $request->input('longitude') : null,
            googleMapUrl: $request->filled('google_map_url') ? (string) $request->string('google_map_url') : null,
            description: $request->filled('description') ? (string) $request->input('description') : null,
            priceFrom: $request->filled('price_from') ? (string) $request->input('price_from') : null,
            isFreeCancel: (bool) $request->boolean('is_free_cancel', false),
            isPayLater: (bool) $request->boolean('is_pay_later', false),
            isFeatured: (bool) $request->boolean('is_featured', false),
            isActive: (bool) $request->boolean('is_active', true),
            ratingScore: $request->filled('rating_score') ? (string) $request->input('rating_score') : null,
            ratingCount: (int) $request->integer('rating_count', 0),
            metaTitle: $request->filled('meta_title') ? (string) $request->string('meta_title') : null,
            metaDescription: $request->filled('meta_description') ? (string) $request->string('meta_description') : null,
            actorId: null,
            hotelImages: $hotelImages,
            amenityIds: $amenityIds,
        );

        $item = $useCase->execute($id, $dto);
        $resource = (new AdminHotelResource($item))->withMeta([
            'message' => 'Cap nhat hotel thanh cong',
        ]);

        return response()->json($resource->toArray($request));
    }

    public function destroy(int $id, DeleteAdminHotelUseCase $useCase): JsonResponse
    {
        $useCase->execute($id);

        return response()->json([
            'data' => null,
            'meta' => [
                'version' => config('app.api_version', '1.0.0'),
                'timestamp' => now()->toIso8601String(),
                'message' => 'Xoa hotel thanh cong',
            ],
        ]);
    }

    /**
     * @return array<int, array{url: string, alt: string|null, is_cover: bool, sort_order: int}>|null
     */
    private function resolveHotelImages(Request $request): ?array
    {
        if (!$request->has('hotel_images')) {
            return null;
        }

        $inputItems = (array) $request->input('hotel_images', []);
        $fileItems = (array) $request->file('hotel_images', []);
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
                $url = $this->uploadImage($file, 'hotels/images');
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
                'sort_order' => isset($input['sort_order']) ? (int) $input['sort_order'] : 0,
            ];
        }

        return $normalized;
    }

    /** @return array<int, int>|null */
    private function resolveAmenityIds(Request $request): ?array
    {
        if (!$request->has('amenity_ids')) {
            return null;
        }

        $amenityIds = array_map(
            static fn($id): int => (int) $id,
            array_values((array) $request->input('amenity_ids', [])),
        );

        return array_values(array_unique(array_filter($amenityIds, static fn(int $id): bool => $id > 0)));
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
