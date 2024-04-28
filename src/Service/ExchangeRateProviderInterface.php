<?php

declare(strict_types=1);

namespace App\Service;

use App\Exception\ExchangeRateCannotBeAcquiredException;

interface ExchangeRateProviderInterface
{
    public const DEFAULT_CURRENCY_TO = "EUR";

    /**
     * @throws ExchangeRateCannotBeAcquiredException
     */
    public function get(string $currencyFrom, string $currencyTo = self::DEFAULT_CURRENCY_TO): float;
}
