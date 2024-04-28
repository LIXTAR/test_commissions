<?php

declare(strict_types=1);

namespace App\DTO\ExchangeRateApi;

readonly class ExchangeRatesApiLatest
{
    public function __construct(
        public array $rates,
    ) {
    }
}
