<?php

declare(strict_types=1);

namespace App\Services\ApiService;

use App\Exception\NonSearchableRelatedEntityException;
use App\Models\Comment;
use Illuminate\Database\Eloquent\Builder;

final readonly class CommentService extends BaseApiService
{
    protected function getFilterableFields(): array
    {
        return array_merge(
            $this->getModelQuery()->getModel()->getFillable(),
            ['id', 'created_at', 'updated_at']
        );
    }

    protected function getSortableFields(): array
    {
        return array_merge(
            $this->getModelQuery()->getModel()->getFillable(),
            ['id', 'created_at', 'updated_at']
        );
    }

    protected function getRelatedEntitySearchableField(): string
    {
        throw new NonSearchableRelatedEntityException('Cannot search related entities.');
    }

    protected function getModelQuery(): Builder
    {
        return Comment::query();
    }
}