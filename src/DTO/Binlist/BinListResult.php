<?php

declare(strict_types=1);

namespace App\DTO\Binlist;

readonly class BinListResult
{
    public function __construct(
        public Country $country,
    ) {
    }
}
