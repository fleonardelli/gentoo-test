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
    /**
     * @dataProvider queryParamsProvider
     */
    public function test_query_params(array $inputParams, Pagination $expectedPagination, Sorting $expectedSorting, Filtering $expectedFiltering, Relations $expectedRelations): void
    {
        // Act
        $queryParamsObject = new QueryParams($inputParams);

        // Assert
        $this->assertEquals($expectedPagination, $queryParamsObject->getPaginationParams());
        $this->assertEquals($expectedSorting, $queryParamsObject->getSorting());
        $this->assertEquals($expectedFiltering, $queryParamsObject->getFiltering());
        $this->assertEquals($expectedRelations, $queryParamsObject->getRelations());
    }

    public static function queryParamsProvider(): array
    {
        return [
            'all fields provided' => [
                [
                    'page' => 2,
                    'limit' => 20,
                    'sort' => 'title',
                    'direction' => 'desc',
                    'status' => 'active',
                    'author' => 'John Doe',
                    'with' => 'comments,tags',
                ],
                new Pagination(2, 20),
                new Sorting('title', 'desc'),
                new Filtering(['status' => 'active', 'author' => 'John Doe']),
                new Relations(['comments', 'tags']),
            ],
            'default values' => [
                [],
                new Pagination(1, 10),
                new Sorting('created_at', 'asc'),
                new Filtering([]),
                new Relations([]),
            ],
            'missing fields' => [
                [
                    'page' => 3,
                    'limit' => 15,
                    'sort' => 'updated_at',
                    'direction' => 'asc',
                    'with' => 'authors',
                ],
                new Pagination(3, 15),
                new Sorting('updated_at', 'asc'),
                new Filtering([]),
                new Relations(['authors']),
            ],
        ];
    }
}
