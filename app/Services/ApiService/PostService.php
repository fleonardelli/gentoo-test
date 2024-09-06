<?php

declare(strict_types=1);

namespace App\Services\ApiService;

use App\Models\Post;
use Illuminate\Database\Eloquent\Builder;

final readonly class PostService extends BaseApiService
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
        // This works for this example, but if we want to scalate it a [relatedEntity => field] would be more suitable,
        // or even [relatedEntity => [..., field] depending on the use case. At some point I'd avoid over abstracting code,
        // as it tends to backfire.
        return 'content';
    }

    protected function getModelQuery(): Builder
    {
        return Post::query();
    }
}