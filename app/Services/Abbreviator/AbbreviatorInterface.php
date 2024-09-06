<?php

declare(strict_types=1);

namespace App\Services\Abbreviator;

interface AbbreviatorInterface
{
    /**
     * Generate abbreviation for the given content.
     *
     * @param string $content
     * @return string
     */
    public function generateAbbreviation(string $content): string;
}