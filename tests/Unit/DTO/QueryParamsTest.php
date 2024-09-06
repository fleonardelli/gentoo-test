<?php

declare(strict_types=1);

namespace Tests\Unit\DTO;

use App\DTO\Request\Filtering;
use App\DTO\Request\Pagination;
use App\DTO\Request\QueryParams;
use App\DTO\Request\Relations;
use App\DTO\Request\Sorting;
use PHPUnit\Framework\TestCase;

final class QueryParamsTest extends TestCase
{
    public function test_query_params_with_all_fields(): void
    {
        // Arrange
        $queryParams = [
            'page' => 2,
            'limit' => 20,
            'sort' => 'title',
            'direction' => 'desc',
            'status' => 'active',
            'author' => 'John Doe',
            'with' => 'comments,tags',
        ];

        // Act
        $queryParamsObject = new QueryParams($queryParams);

        // Assert
        $this->assertEquals(new Pagination(2, 20), $queryParamsObject->getPaginationParams());
        $this->assertEquals(new Sorting('title', 'desc'), $queryParamsObject->getSorting());
        $this->assertEquals(
            new Filtering([
                'status' => 'active',
                'author' => 'John Doe'
            ]),
            $queryParamsObject->getFiltering()
        );
        $this->assertEquals(new Relations(['comments', 'tags']), $queryParamsObject->getRelations());
    }

    public function test_query_params_with_default_values(): void
    {
        // Arrange
        $queryParams = [];

        // Act
        $queryParamsObject = new QueryParams($queryParams);

        // Assert
        $this->assertEquals(new Pagination(1, 10), $queryParamsObject->getPaginationParams());
        $this->assertEquals(new Sorting('created_at', 'asc'), $queryParamsObject->getSorting());
        $this->assertEquals(new Filtering([]), $queryParamsObject->getFiltering());
        $this->assertEquals(new Relations([]), $queryParamsObject->getRelations());
    }

    public function test_query_params_with_missing_fields(): void
    {
        // Arrange
        $queryParams = [
            'page' => 3,
            'limit' => 15,
            'sort' => 'updated_at',
            'direction' => 'asc',
            'with' => 'authors',
        ];

        // Act
        $queryParamsObject = new QueryParams($queryParams);

        // Assert
        $this->assertEquals(new Pagination(3, 15), $queryParamsObject->getPaginationParams());
        $this->assertEquals(new Sorting('updated_at', 'asc'), $queryParamsObject->getSorting());
        $this->assertEquals(new Filtering([]), $queryParamsObject->getFiltering());
        $this->assertEquals(new Relations(['authors']), $queryParamsObject->getRelations());
    }
}