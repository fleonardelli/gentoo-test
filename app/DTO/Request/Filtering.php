<?php

declare(strict_types=1);

namespace App\DTO\Request;

final readonly class Filtering
{

    /**
     * @param array<string, mixed> $filters
     */
    public function __construct(private array $filters)
    {
    }

    /**
     * @return array<string, mixed>
     */
    public function getFilters(): array
    {
        return $this->filters;
    }
}