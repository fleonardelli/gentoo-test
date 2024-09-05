<?php

declare(strict_types=1);

namespace App\Services;

final class WordCombinatorService
{
    /**
     * Generate all unique combinations of the given words.
     *
     * @param array $words
     * @return array
     */
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

    /**
     * Generate abbreviation for the given content.
     *
     * @param string $content
     * @return string
     */
    public function generateAbbreviation(string $content): string
    {
        $words = explode(' ', strtolower($content));
        $letters = array_map(fn($word) => $word[0], $words);

        return implode('', $letters);
    }
}