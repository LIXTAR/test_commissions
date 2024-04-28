<?php

declare(strict_types=1);

namespace App\Service;

use App\DTO\TransactionDetail;
use App\Exception\BinDataCannotBeAcquiredException;
use App\Exception\CommissionCannotBeCalculatedException;
use App\Exception\ExchangeRateCannotBeAcquiredException;

readonly class CommissionsCalculator
{
    private const EUR_COMMISSION_COEFFICIENT     = 0.01;
    private const NON_EUR_COMMISSION_COEFFICIENT = 0.02;
    private const EUR_COUNTRY_CODES              = [
        'AT',
        'BE',
        'BG',
        'CY',
        'CZ',
        'DE',
        'DK',
        'EE',
        'ES',
        'FI',
        'FR',
        'GR',
        'HR',
        'HU',
        'IE',
        'IT',
        'LT',
        'LU',
        'LV',
        'MT',
        'NL',
        'PO',
        'PT',
        'RO',
        'SE',
        'SI',
        'SK',
    ];

    public function __construct(
        private BinDataProviderInterface      $binDataProvider,
        private ExchangeRateProviderInterface $exchangeRateProvider,
    ) {
    }

    /**
     * @throws CommissionCannotBeCalculatedException
     */
    public function calculate(TransactionDetail $detail): float
    {
        try {
            return $this->convert($detail->amount, $detail->currency)
                * $this->getCoefficient($this->binDataProvider->get($detail->bin)->countryIsoCode);
        } catch (BinDataCannotBeAcquiredException|ExchangeRateCannotBeAcquiredException $exception) {
            throw new CommissionCannotBeCalculatedException(previous: $exception);
        }
    }

    /**
     * @throws ExchangeRateCannotBeAcquiredException
     */
    private function convert(string $amount, string $currencyFrom): float
    {
        return $amount / $this->exchangeRateProvider->get($currencyFrom);
    }

    private function getCoefficient(string $countryIsoCode): float
    {
        return in_array($countryIsoCode, self::EUR_COUNTRY_CODES)
            ? self::EUR_COMMISSION_COEFFICIENT
            : self::NON_EUR_COMMISSION_COEFFICIENT;
    }
}
