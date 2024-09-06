<?php

declare(strict_types=1);

namespace App\DTO\Request;

use InvalidArgumentException;

final readonly class Pagination
{
    public function __construct(
        private int $page = 1,
        private int $limit = 10
    ) {
        if ($page < 1) {
            throw new InvalidArgumentException('Page number must be greater than 0.');
        }
        if ($limit < 1) {
            throw new InvalidArgumentException('Limit must be greater than 0.');
        }
    }

    public function getPage(): int
    {
        return $this->page;
    }

    public function getLimit(): int
    {
        return $this->limit;
    }
}