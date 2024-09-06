<?php

declare(strict_types=1);

namespace App\DTO\Request;

final readonly class QueryParams
{
    private Pagination $pagination;
    private Sorting $sorting;
    private Filtering $filtering;
    private Relations $relations;

    /**
     * @param array<string, mixed> $queryParams
     */
    public function __construct(array $queryParams)
    {
        $this->pagination = new Pagination(
            page: (int) ($queryParams['page'] ?? 1),
            limit: (int) ($queryParams['limit'] ?? 10)
        );

        $this->sorting = new Sorting(
            sortField: $queryParams['sort'] ?? 'created_at',
            sortDirection: $queryParams['direction'] ?? 'asc'
        );

        $this->filtering = new Filtering(
            filters: array_filter(
                $queryParams,
                fn($key) => !in_array($key, ['page', 'limit', 'sort', 'direction', 'with'], true),
                ARRAY_FILTER_USE_KEY
            )
        );

        $this->relations = new Relations(
            relations: isset($queryParams['with']) ? explode(',', $queryParams['with']) : []
        );
    }

    public function getPaginationParams(): Pagination
    {
        return $this->pagination;
    }

    public function getSorting(): Sorting
    {
        return $this->sorting;
    }

    public function getFiltering(): Filtering
    {
        return $this->filtering;
    }

    public function getRelations(): Relations
    {
        return $this->relations;
    }
}