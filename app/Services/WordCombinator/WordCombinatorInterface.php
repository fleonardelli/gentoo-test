<?php

declare(strict_types=1);

namespace App\Services\WordCombinator;

interface WordCombinatorInterface
{
    /**
     * Generate all unique combinations of the given words.
     *
     * @param array $words
     * @return array
     */
    public function generateCombinations(array $words): array;
}