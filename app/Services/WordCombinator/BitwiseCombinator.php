<?php

declare(strict_types=1);

namespace App\Services\WordCombinator;

final class BitwiseCombinator implements WordCombinatorInterface
{
    public function generateCombinations(array $words): array
    {
        $combinations = [];
        $numWords = count($words);

        // Generate combinations using bitwise operations
        for ($i = 1; $i < (1 << $numWords); $i++) {
            $combination = [];
            for ($j = 0; $j < $numWords; $j++) {
                if ($i & (1 << $j)) {
                    $combination[] = $words[$j];
                }
            }
            $combinations[] = implode(' ', $combination);
        }

        return $combinations;
    }
}