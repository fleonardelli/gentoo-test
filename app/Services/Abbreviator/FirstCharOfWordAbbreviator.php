<?php

declare(strict_types=1);

namespace App\Services\Abbreviator;

final class FirstCharOfWordAbbreviator implements AbbreviatorInterface
{
    public function generateAbbreviation(string $content): string
    {
        $words = explode(' ', strtolower($content));
        $letters = array_map(fn($word) => $word[0], $words);

        return implode('', $letters);
    }
}