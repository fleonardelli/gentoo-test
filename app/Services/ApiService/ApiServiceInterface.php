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

interface ApiServiceInterface
{
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
    ): PaginatedResult;
}