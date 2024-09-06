<?php

declare(strict_types=1);

namespace App\DTO\Request;

final readonly class Relations
{
    /**
     * @param array<string> $relations
     */
    public function __construct(private array $relations)
    {
    }

    /**
     * @return array<string>
     */
    public function getRelations(): array
    {
        return $this->relations;
    }
}