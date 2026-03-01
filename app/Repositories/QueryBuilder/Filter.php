<?php

namespace App\Repositories\QueryBuilder;

use App\Constants\QueryParam;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;

class Filter
{
    protected array $filters;

    public function __construct(array $filters = [])
    {
        $this->filters = $filters;
    }

    public function apply(Builder $query, ?array $filters = null): Builder
    {
        $filters = $filters ?? $this->filters;

        foreach ($filters as $field => $conditions) {
            if (is_array($conditions)) {
                foreach ($conditions as $operator => $value) {
                    $this->applyCondition($query, $field, $operator, $value);
                }
            } else {
                $query->where($field, $conditions);
            }
        }

        return $query;
    }

    protected function applyCondition(Builder $query, string $field, string $operator, mixed $value): void
    {
        if (is_string($value) && preg_match('/^\d{4}-\d{2}$/', $value)) {
            $carbon = Carbon::createFromFormat('Y-m', $value);

            switch ($operator) {
                case QueryParam::OPERATOR_EQ:
                    $query->whereBetween($field, [
                        $carbon->copy()->startOfMonth()->toDateString(),
                        $carbon->copy()->endOfMonth()->toDateString(),
                    ]);
                    return;

                case QueryParam::OPERATOR_GTE:
                    $query->whereDate($field, '>=', $carbon->startOfMonth()->toDateString());
                    return;

                case QueryParam::OPERATOR_LTE:
                    $query->whereDate($field, '<=', $carbon->endOfMonth()->toDateString());
                    return;

                case QueryParam::OPERATOR_GT:
                    $query->whereDate($field, '>', $carbon->endOfMonth()->toDateString());
                    return;

                case QueryParam::OPERATOR_LT:
                    $query->whereDate($field, '<', $carbon->startOfMonth()->toDateString());
                    return;
            }
        }

        switch ($operator) {
            case QueryParam::OPERATOR_EQ:
                $query->where($field, '=', $value);
                break;
            case QueryParam::OPERATOR_NE:
                $query->where($field, '!=', $value);
                break;
            case QueryParam::OPERATOR_GT:
                $query->where($field, '>', $value);
                break;
            case QueryParam::OPERATOR_GTE:
                $query->where($field, '>=', $value);
                break;
            case QueryParam::OPERATOR_LT:
                $query->where($field, '<', $value);
                break;
            case QueryParam::OPERATOR_LTE:
                $query->where($field, '<=', $value);
                break;
            case QueryParam::OPERATOR_BETWEEN:
                $values = is_string($value) ? explode(',', $value) : (array) $value;
                if (count($values) === 2) {
                    $query->whereBetween($field, $values);
                }
                break;
            case QueryParam::OPERATOR_NOT_BETWEEN:
                $values = is_string($value) ? explode(',', $value) : (array) $value;
                if (count($values) === 2) {
                    $query->whereNotBetween($field, $values);
                }
                break;
            case QueryParam::OPERATOR_IN:
                $values = is_string($value) ? explode(',', $value) : (array) $value;
                $query->whereIn($field, $values);
                break;
            case QueryParam::OPERATOR_NOT_IN:
                $values = is_string($value) ? explode(',', $value) : (array) $value;
                $query->whereNotIn($field, $values);
                break;
            case QueryParam::OPERATOR_LIKE:
                $query->where($field, 'like', "%{$value}%");
                break;
            case QueryParam::OPERATOR_NOT_LIKE:
                $query->where($field, 'not like', "%{$value}%");
                break;
            default:
                $query->where($field, $operator, $value);
        }
    }
}
