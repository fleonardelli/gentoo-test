<?php

declare(strict_types=1);

namespace App\Services\ApiService;

use App\DTO\Request\Filtering;
use App\DTO\Request\Pagination;
use App\DTO\Request\Relations;
use App\DTO\Request\Sorting;
use App\DTO\Response\PaginatedResult;
use App\Exception\InvalidFilterException;
use App\Exception\InvalidRelationException;
use App\Exception\InvalidSortFieldException;
use App\Exception\NonSearchableRelatedEntityException;
use Illuminate\Database\Eloquent\Builder;

abstract readonly class BaseApiService implements ApiServiceInterface
{
    abstract protected function getFilterableFields(): array;
    abstract protected function getSortableFields(): array;

    /**
     * @throws NonSearchableRelatedEntityException
     */
    abstract protected function getRelatedEntitySearchableField(): string;
    abstract protected function getModelQuery(): Builder;

    /**
     * @throws InvalidSortFieldException
     * @throws InvalidFilterException
     * @throws InvalidRelationException
     */
    public function getItems(
        Pagination $pagination,
        Filtering $filtering,
        Sorting $sorting,
        Relations $relations
    ): PaginatedResult {
        $query = $this->getModelQuery();

        $this->applyFilters($query, $filtering);
        $this->applySorting($query, $sorting);
        $this->applyRelations($query, $relations);

        $totalCount = $query->count();
        // this can also be done with eloquent pagination
        $items = $query->paginate($pagination->getLimit(), ['*'], 'page', $pagination->getPage());

        return new PaginatedResult(
            result: $items->items(),
            count: $totalCount
        );
    }

    private function applyFilters(Builder $query, Filtering $filtering): void
    {
        $filterableFields = $this->getFilterableFields();

        foreach ($filtering->getFilters() as $field => $value) {
            if (in_array($field, $filterableFields, true)) {
                if ($field === 'id') {
                    $query->where($field, '=', $value);
                } else {
                    $query->where($field, 'LIKE', "%{$value}%");
                }
            } elseif (method_exists($query->getModel(), $field) || array_key_exists($field, $query->getModel()->getRelations())) {
                $query->whereHas($field, function (Builder $relationQuery) use ($value) {
                    $relationQuery->where($this->getRelatedEntitySearchableField(), 'LIKE', "%{$value}%");
                });
            } else {
                throw new InvalidFilterException("The field '{$field}' is not filterable.");
            }
        }
    }

    private function applySorting(Builder $query, Sorting $sorting): void
    {
        $sortField = $sorting->getSortField();
        $sortDirection = $sorting->getSortDirection();

        if (str_contains($sortField, ',')) {
            throw new InvalidSortFieldException('Only one sort field is allowed.');
        }

        if (in_array($sortField, $this->getSortableFields(), true)) {
            $query->orderBy($sortField, $sortDirection->value);
        } else {
            throw new InvalidSortFieldException("The field '{$sortField}' is not sortable.");
        }
    }

    private function applyRelations(Builder $query, Relations $relations): void
    {
        foreach ($relations->getRelations() as $relation) {
            if (!method_exists($query->getModel(), $relation) && !array_key_exists($relation, $query->getModel()->getRelations())) {
                throw new InvalidRelationException(
                    "The relation '{$relation}' does not exist on " . get_class($query->getModel())
                );
            }
            $query->with($relation);
        }
    }
}