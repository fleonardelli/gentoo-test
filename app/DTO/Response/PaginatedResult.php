<?php

declare(strict_types=1);

namespace App\DTO\Response;

final readonly class PaginatedResult
{
    /**
     * @param array<int, mixed> $result
     * @param int $count
     */
    public function __construct(
        private array $result,
        private int $count
    ) {
    }

    /**
     * @return array<int, mixed>
     */
    public function getResult(): array
    {
        return $this->result;
    }

    public function getCount(): int
    {
        return $this->count;
    }
}
