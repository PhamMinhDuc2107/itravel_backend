<?php

declare(strict_types=1);

namespace App\Infrastructure\Database\Repositories;

use App\Domain\Repositories\TourRepositoryInterface;
use App\Infrastructure\Database\Models\TourModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

final class TourRepository extends BaseRepository implements TourRepositoryInterface
{
    public function __construct(TourModel $model)
    {
        parent::__construct($model);
    }

    public function paginateForAdmin(
        int $page,
        int $perPage,
        ?string $search,
        ?string $searchBy,
        ?int $categoryId,
        ?string $status,
        ?bool $isFeatured,
        ?bool $isHot,
    ): array {
        $query = $this->newQuery()
            ->with(['category:id,name,slug'])
            ->orderByDesc('id');

        if ($search !== null && $search !== '') {
            $searchableColumns = TourModel::searchableColumns();
            $normalizedSearchBy = $searchBy !== null ? trim($searchBy) : null;

            if ($normalizedSearchBy !== null && in_array($normalizedSearchBy, $searchableColumns, true)) {
                $query->where($normalizedSearchBy, 'like', "%{$search}%");
            } else {
                $query->where(function ($builder) use ($search, $searchableColumns): void {
                    foreach ($searchableColumns as $index => $column) {
                        if ($index === 0) {
                            $builder->where($column, 'like', "%{$search}%");

                            continue;
                        }

                        $builder->orWhere($column, 'like', "%{$search}%");
                    }
                });
            }
        }

        if ($categoryId !== null) {
            $query->where('category_id', $categoryId);
        }

        if ($status !== null && $status !== '') {
            $query->where('status', $status);
        }

        if ($isFeatured !== null) {
            $query->where('is_featured', $isFeatured);
        }

        if ($isHot !== null) {
            $query->where('is_hot', $isHot);
        }

        /** @var LengthAwarePaginator $paginator */
        $paginator = $query->paginate(
            perPage: $perPage,
            columns: ['*'],
            pageName: 'page',
            page: $page,
        );

        return [
            'items' => $paginator->items(),
            'pagination' => [
                'current_page' => $paginator->currentPage(),
                'last_page' => $paginator->lastPage(),
                'per_page' => $paginator->perPage(),
                'total' => $paginator->total(),
                'from' => $paginator->firstItem(),
                'to' => $paginator->lastItem(),
            ],
        ];
    }

    public function findDetailById(int $id): ?array
    {
        $model = parent::findById($id, [
            'category:id,name,slug',
            'images:id,tour_id,url,alt,is_cover,sort',
            'itineraries:id,tour_id,day_number,title,content',
            'notes:id,tour_id,title,content,sort',
            'prices:id,tour_id,passenger_type,price,currency,includes,excludes',
            'schedules:id,tour_id,departure_date,return_date,max_slots,booked_slots,status,note',
            'priceOverrides:id,tour_id,tour_schedule_id,departure_date,adult_price,child_price,infant_price,is_active,note',
            'tourLocations:id,tour_id,location_id,role,sort',
            'tourLocations.location:id,name,slug',
            'locations:id,name,slug',
        ]);

        return $model?->toArray();
    }

    public function createAndLoad(array $payload): array
    {
        $hasTourImages = array_key_exists('tour_images', $payload);
        $tourImages = $hasTourImages ? (array) ($payload['tour_images'] ?? []) : [];

        $hasTourPriceOverrides = array_key_exists('tour_price_overrides', $payload);
        $tourPriceOverrides = $hasTourPriceOverrides ? (array) ($payload['tour_price_overrides'] ?? []) : [];

        $hasTourItineraries = array_key_exists('tour_itineraries', $payload);
        $tourItineraries = $hasTourItineraries ? (array) ($payload['tour_itineraries'] ?? []) : [];

        $hasTourNotes = array_key_exists('tour_notes', $payload);
        $tourNotes = $hasTourNotes ? (array) ($payload['tour_notes'] ?? []) : [];

        $hasTourPrices = array_key_exists('tour_prices', $payload);
        $tourPrices = $hasTourPrices ? (array) ($payload['tour_prices'] ?? []) : [];

        $hasTourSchedules = array_key_exists('tour_schedules', $payload);
        $tourSchedules = $hasTourSchedules ? (array) ($payload['tour_schedules'] ?? []) : [];

        $hasTourLocations = array_key_exists('tour_locations', $payload);
        $tourLocations = $hasTourLocations ? (array) ($payload['tour_locations'] ?? []) : [];

        unset(
            $payload['tour_images'],
            $payload['tour_price_overrides'],
            $payload['tour_itineraries'],
            $payload['tour_notes'],
            $payload['tour_prices'],
            $payload['tour_schedules'],
            $payload['tour_locations'],
        );

        $model = parent::create($payload);

        if ($hasTourImages) {
            $this->syncTourImages($model, $tourImages);
        }

        if ($hasTourPriceOverrides) {
            $this->syncTourPriceOverrides($model, $tourPriceOverrides);
        }

        if ($hasTourItineraries) {
            $this->syncTourItineraries($model, $tourItineraries);
        }

        if ($hasTourNotes) {
            $this->syncTourNotes($model, $tourNotes);
        }

        if ($hasTourPrices) {
            $this->syncTourPrices($model, $tourPrices);
        }

        if ($hasTourSchedules) {
            $this->syncTourSchedules($model, $tourSchedules);
        }

        if ($hasTourLocations) {
            $this->syncTourLocations($model, $tourLocations);
        }

        return (array) $this->newQuery()
            ->with([
                'category:id,name,slug',
                'images:id,tour_id,url,alt,is_cover,sort',
                'itineraries:id,tour_id,day_number,title,content',
                'notes:id,tour_id,title,content,sort',
                'prices:id,tour_id,passenger_type,price,currency,includes,excludes',
                'schedules:id,tour_id,departure_date,return_date,max_slots,booked_slots,status,note',
                'priceOverrides:id,tour_id,tour_schedule_id,departure_date,adult_price,child_price,infant_price,is_active,note',
                'tourLocations:id,tour_id,location_id,role,sort',
                'tourLocations.location:id,name,slug',
                'locations:id,name,slug',
            ])
            ->findOrFail($model->getKey())
            ->toArray();
    }

    public function updateAndLoadById(int $id, array $payload): ?array
    {
        if (parent::findById($id) === null) {
            return null;
        }

        $hasTourImages = array_key_exists('tour_images', $payload);
        $tourImages = $hasTourImages ? (array) ($payload['tour_images'] ?? []) : [];

        $hasTourPriceOverrides = array_key_exists('tour_price_overrides', $payload);
        $tourPriceOverrides = $hasTourPriceOverrides ? (array) ($payload['tour_price_overrides'] ?? []) : [];

        $hasTourItineraries = array_key_exists('tour_itineraries', $payload);
        $tourItineraries = $hasTourItineraries ? (array) ($payload['tour_itineraries'] ?? []) : [];

        $hasTourNotes = array_key_exists('tour_notes', $payload);
        $tourNotes = $hasTourNotes ? (array) ($payload['tour_notes'] ?? []) : [];

        $hasTourPrices = array_key_exists('tour_prices', $payload);
        $tourPrices = $hasTourPrices ? (array) ($payload['tour_prices'] ?? []) : [];

        $hasTourSchedules = array_key_exists('tour_schedules', $payload);
        $tourSchedules = $hasTourSchedules ? (array) ($payload['tour_schedules'] ?? []) : [];

        $hasTourLocations = array_key_exists('tour_locations', $payload);
        $tourLocations = $hasTourLocations ? (array) ($payload['tour_locations'] ?? []) : [];

        unset(
            $payload['tour_images'],
            $payload['tour_price_overrides'],
            $payload['tour_itineraries'],
            $payload['tour_notes'],
            $payload['tour_prices'],
            $payload['tour_schedules'],
            $payload['tour_locations'],
        );

        $updated = parent::update($id, $payload);

        if ($hasTourImages) {
            $this->syncTourImages($updated, $tourImages);
        }

        if ($hasTourPriceOverrides) {
            $this->syncTourPriceOverrides($updated, $tourPriceOverrides);
        }

        if ($hasTourItineraries) {
            $this->syncTourItineraries($updated, $tourItineraries);
        }

        if ($hasTourNotes) {
            $this->syncTourNotes($updated, $tourNotes);
        }

        if ($hasTourPrices) {
            $this->syncTourPrices($updated, $tourPrices);
        }

        if ($hasTourSchedules) {
            $this->syncTourSchedules($updated, $tourSchedules);
        }

        if ($hasTourLocations) {
            $this->syncTourLocations($updated, $tourLocations);
        }

        return (array) $this->newQuery()
            ->with([
                'category:id,name,slug',
                'images:id,tour_id,url,alt,is_cover,sort',
                'itineraries:id,tour_id,day_number,title,content',
                'notes:id,tour_id,title,content,sort',
                'prices:id,tour_id,passenger_type,price,currency,includes,excludes',
                'schedules:id,tour_id,departure_date,return_date,max_slots,booked_slots,status,note',
                'priceOverrides:id,tour_id,tour_schedule_id,departure_date,adult_price,child_price,infant_price,is_active,note',
                'tourLocations:id,tour_id,location_id,role,sort',
                'tourLocations.location:id,name,slug',
                'locations:id,name,slug',
            ])
            ->findOrFail($id)
            ->toArray();
    }

    public function deleteExistingById(int $id): bool
    {
        if (parent::findById($id) === null) {
            return false;
        }

        return parent::delete($id);
    }

    /** @param array<int, array<string, mixed>> $tourImages */
    private function syncTourImages(Model $tour, array $tourImages): void
    {
        $normalized = [];
        foreach ($tourImages as $item) {
            if (!is_array($item)) {
                continue;
            }

            $url = isset($item['url']) && is_string($item['url']) ? trim($item['url']) : '';
            if ($url === '') {
                continue;
            }

            $normalized[] = [
                'url' => $url,
                'alt' => isset($item['alt']) && is_string($item['alt']) && $item['alt'] !== '' ? $item['alt'] : null,
                'is_cover' => isset($item['is_cover']) ? (bool) $item['is_cover'] : false,
                'sort' => isset($item['sort']) ? (int) $item['sort'] : 0,
            ];
        }

        $tour->images()->delete();
        if ($normalized !== []) {
            $tour->images()->createMany($normalized);
        }
    }

    /** @param array<int, array<string, mixed>> $tourPriceOverrides */
    private function syncTourPriceOverrides(Model $tour, array $tourPriceOverrides): void
    {
        $normalized = [];
        foreach ($tourPriceOverrides as $item) {
            if (!is_array($item)) {
                continue;
            }

            $tourScheduleId = isset($item['tour_schedule_id']) ? (int) $item['tour_schedule_id'] : 0;
            $departureDate = isset($item['departure_date']) ? (string) $item['departure_date'] : '';
            if ($tourScheduleId <= 0 || $departureDate === '') {
                continue;
            }

            $normalized[] = [
                'tour_schedule_id' => $tourScheduleId,
                'departure_date' => $departureDate,
                'adult_price' => isset($item['adult_price']) ? (int) $item['adult_price'] : null,
                'child_price' => isset($item['child_price']) ? (int) $item['child_price'] : null,
                'infant_price' => isset($item['infant_price']) ? (int) $item['infant_price'] : null,
                'is_active' => isset($item['is_active']) ? (bool) $item['is_active'] : true,
                'note' => isset($item['note']) && is_string($item['note']) && $item['note'] !== '' ? $item['note'] : null,
            ];
        }

        $tour->priceOverrides()->delete();
        if ($normalized !== []) {
            $tour->priceOverrides()->createMany($normalized);
        }
    }

    /** @param array<int, array<string, mixed>> $tourItineraries */
    private function syncTourItineraries(Model $tour, array $tourItineraries): void
    {
        $normalized = [];
        foreach ($tourItineraries as $item) {
            if (!is_array($item)) {
                continue;
            }

            $dayNumber = isset($item['day_number']) ? (int) $item['day_number'] : 0;
            $title = isset($item['title']) && is_string($item['title']) ? trim($item['title']) : '';
            if ($dayNumber <= 0 || $title === '') {
                continue;
            }

            $normalized[] = [
                'day_number' => $dayNumber,
                'title' => $title,
                'content' => isset($item['content']) && is_string($item['content']) && $item['content'] !== '' ? $item['content'] : null,
            ];
        }

        $tour->itineraries()->delete();
        if ($normalized !== []) {
            $tour->itineraries()->createMany($normalized);
        }
    }

    /** @param array<int, array<string, mixed>> $tourNotes */
    private function syncTourNotes(Model $tour, array $tourNotes): void
    {
        $normalized = [];
        foreach ($tourNotes as $item) {
            if (!is_array($item)) {
                continue;
            }

            $title = isset($item['title']) && is_string($item['title']) ? trim($item['title']) : '';
            if ($title === '') {
                continue;
            }

            $normalized[] = [
                'title' => $title,
                'content' => isset($item['content']) && is_string($item['content']) && $item['content'] !== '' ? $item['content'] : null,
                'sort' => isset($item['sort']) ? (int) $item['sort'] : 0,
            ];
        }

        $tour->notes()->delete();
        if ($normalized !== []) {
            $tour->notes()->createMany($normalized);
        }
    }

    /** @param array<int, array<string, mixed>> $tourPrices */
    private function syncTourPrices(Model $tour, array $tourPrices): void
    {
        $normalized = [];
        foreach ($tourPrices as $item) {
            if (!is_array($item)) {
                continue;
            }

            $passengerType = isset($item['passenger_type']) ? (int) $item['passenger_type'] : -1;
            $price = isset($item['price']) ? (int) $item['price'] : -1;
            if ($passengerType < 0 || $price < 0) {
                continue;
            }

            $normalized[] = [
                'passenger_type' => $passengerType,
                'price' => $price,
                'currency' => isset($item['currency']) && is_string($item['currency']) && $item['currency'] !== '' ? $item['currency'] : 'VND',
                'includes' => isset($item['includes']) && is_string($item['includes']) && $item['includes'] !== '' ? $item['includes'] : null,
                'excludes' => isset($item['excludes']) && is_string($item['excludes']) && $item['excludes'] !== '' ? $item['excludes'] : null,
            ];
        }

        $tour->prices()->delete();
        if ($normalized !== []) {
            $tour->prices()->createMany($normalized);
        }
    }

    /** @param array<int, array<string, mixed>> $tourSchedules */
    private function syncTourSchedules(Model $tour, array $tourSchedules): void
    {
        $normalized = [];
        foreach ($tourSchedules as $item) {
            if (!is_array($item)) {
                continue;
            }

            $departureDate = isset($item['departure_date']) && is_string($item['departure_date']) ? trim($item['departure_date']) : '';
            if ($departureDate === '') {
                continue;
            }

            $normalized[] = [
                'departure_date' => $departureDate,
                'return_date' => isset($item['return_date']) && is_string($item['return_date']) && $item['return_date'] !== '' ? $item['return_date'] : null,
                'max_slots' => isset($item['max_slots']) ? (int) $item['max_slots'] : 0,
                'booked_slots' => isset($item['booked_slots']) ? (int) $item['booked_slots'] : 0,
                'status' => isset($item['status']) ? (int) $item['status'] : 0,
                'note' => isset($item['note']) && is_string($item['note']) && $item['note'] !== '' ? $item['note'] : null,
            ];
        }

        $tour->schedules()->delete();
        if ($normalized !== []) {
            $tour->schedules()->createMany($normalized);
        }
    }

    /** @param array<int, array<string, mixed>> $tourLocations */
    private function syncTourLocations(Model $tour, array $tourLocations): void
    {
        $normalized = [];
        foreach ($tourLocations as $item) {
            if (!is_array($item)) {
                continue;
            }

            $locationId = isset($item['location_id']) ? (int) $item['location_id'] : 0;
            if ($locationId <= 0) {
                continue;
            }

            $normalized[] = [
                'location_id' => $locationId,
                'role' => isset($item['role']) ? (int) $item['role'] : 1,
                'sort' => isset($item['sort']) ? (int) $item['sort'] : 0,
            ];
        }

        $tour->tourLocations()->delete();
        if ($normalized !== []) {
            $tour->tourLocations()->createMany($normalized);
        }
    }
}
