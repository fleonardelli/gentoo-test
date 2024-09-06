<?php

declare(strict_types=1);

namespace App\DTO\Request;

use App\Enum\SortDirection;
use InvalidArgumentException;
use ValueError;

final readonly class Sorting
{
    private SortDirection $sortDirection;

    public function __construct(
        private string $sortField = 'created_at',
        string $sortDirection = 'asc'
    )
    {
        try {
            $this->sortDirection = SortDirection::from($sortDirection);
        } catch (ValueError) {
            $validValues = array_map(fn(SortDirection $direction) => $direction->value, SortDirection::cases());
            throw new InvalidArgumentException(
                'Sort direction must be one of: ' . implode(', ', $validValues) . '.'
            );
        }
    }

    public function getSortField(): string
    {
        return $this->sortField;
    }

    public function getSortDirection(): SortDirection
    {
        return $this->sortDirection;
    }
}
