<?php

declare(strict_types=1);

namespace App\DTO;

readonly class BinData
{
    public function __construct(
        public string $countryIsoCode,
    ) {
    }
}
