<?php

declare(strict_types=1);

namespace App\Tests;

use App\Exception\ExchangeRateCannotBeAcquiredException;
use App\Service\ExchangeRateProvider\ExchangeRatesApi;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Contracts\Cache\CacheInterface;

final class ExchangeRatesApiExchangeRateProviderTest extends KernelTestCase
{
    private const API_KEY = "test_api_key";

    private readonly SerializerInterface $serializer;

    private readonly CacheInterface      $cache;

    protected function setUp(): void
    {
        $container        = KernelTestCase::getContainer();
        $this->serializer = $container->get('serializer');
        $this->cache      = $container->get('exchange_rates.cache');
    }

    public function testPositive(): void
    {
        $mockHttpClient                       = new MockHttpClient(
            new MockResponse(
                <<<JSON
                {
                    "success": true,
                    "timestamp": 1714262885,
                    "base": "EUR",
                    "date": "2024-04-28",
                    "rates": {
                        "AED": 3.930971,
                        "AFN": 77.388118,
                        "ALL": 100.86058,
                        "AMD": 416.943737,
                        "ANG": 1.933603,
                        "AOA": 892.66338,
                        "ARS": 937.446438,
                        "AUD": 1.638993,
                        "AWG": 1.926472,
                        "AZN": 1.823695,
                        "BAM": 1.956005,
                        "BBD": 2.166327,
                        "BDT": 117.752352,
                        "BGN": 1.9566,
                        "BHD": 0.404442,
                        "BIF": 3075.922665,
                        "BMD": 1.070262,
                        "BND": 1.460353,
                        "BOB": 7.440781,
                        "BRL": 5.475787,
                        "BSD": 1.072913,
                        "BTC": 1.6841163e-5,
                        "BTN": 89.398778,
                        "BWP": 14.768549,
                        "BYN": 3.511168,
                        "BYR": 20977.140504,
                        "BZD": 2.162627,
                        "CAD": 1.464173,
                        "CDF": 2996.734745,
                        "CHF": 0.967576,
                        "CLF": 0.036924,
                        "CLP": 1018.856878,
                        "CNY": 7.755232,
                        "CNH": 7.778885,
                        "COP": 4243.84518,
                        "CRC": 545.157187,
                        "CUC": 1.070262,
                        "CUP": 28.36195,
                        "CVE": 110.276568,
                        "CZK": 25.145495,
                        "DJF": 191.060042,
                        "DKK": 7.460589,
                        "DOP": 62.896598,
                        "DZD": 143.604064,
                        "EGP": 51.224373,
                        "ERN": 16.053934,
                        "ETB": 61.574059,
                        "EUR": 1,
                        "FJD": 2.421473,
                        "FKP": 0.859166,
                        "GBP": 0.846894,
                        "GEL": 2.868721,
                        "GGP": 0.859166,
                        "GHS": 14.532524,
                        "GIP": 0.859166,
                        "GMD": 72.697608,
                        "GNF": 9223.967595,
                        "GTQ": 8.344875,
                        "GYD": 224.463546,
                        "HKD": 8.378388,
                        "HNL": 26.493779,
                        "HRK": 7.577629,
                        "HTG": 142.154912,
                        "HUF": 393.386015,
                        "IDR": 17382.450616,
                        "ILS": 4.096852,
                        "IMP": 0.859166,
                        "INR": 89.263031,
                        "IQD": 1405.547442,
                        "IRR": 45031.285414,
                        "ISK": 150.436478,
                        "JEP": 0.859166,
                        "JMD": 167.487569,
                        "JOD": 0.758499,
                        "JPY": 169.203156,
                        "KES": 142.154912,
                        "KGS": 95.046824,
                        "KHR": 4358.457203,
                        "KMF": 491.116642,
                        "KPW": 963.236418,
                        "KRW": 1474.918143,
                        "KWD": 0.329752,
                        "KYD": 0.894094,
                        "KZT": 475.009829,
                        "LAK": 22906.402884,
                        "LBP": 96078.178611,
                        "LKR": 317.583315,
                        "LRD": 206.885734,
                        "LSL": 20.346094,
                        "LTL": 3.160207,
                        "LVL": 0.647391,
                        "LYD": 5.220548,
                        "MAD": 10.837637,
                        "MDL": 19.065,
                        "MGA": 4766.500006,
                        "MKD": 61.551457,
                        "MMK": 2253.136354,
                        "MNT": 3692.405204,
                        "MOP": 8.651508,
                        "MRU": 42.493419,
                        "MUR": 49.607061,
                        "MVR": 16.546659,
                        "MWK": 1859.695082,
                        "MXN": 18.365812,
                        "MYR": 5.10248,
                        "MZN": 67.965645,
                        "NAD": 20.346089,
                        "NGN": 1397.998386,
                        "NIO": 39.484142,
                        "NOK": 11.814415,
                        "NPR": 143.038005,
                        "NZD": 1.80194,
                        "OMR": 0.411683,
                        "PAB": 1.072913,
                        "PEN": 4.030523,
                        "PGK": 4.141434,
                        "PHP": 61.688316,
                        "PKR": 298.695333,
                        "PLN": 4.316876,
                        "PYG": 7988.83803,
                        "QAR": 3.896401,
                        "RON": 4.984252,
                        "RSD": 117.168491,
                        "RUB": 98.764202,
                        "RWF": 1384.84527,
                        "SAR": 4.013964,
                        "SBD": 9.070671,
                        "SCR": 16.074186,
                        "SDG": 627.174054,
                        "SEK": 11.654386,
                        "SGD": 1.458344,
                        "SHP": 1.352223,
                        "SLE": 24.452606,
                        "SLL": 22442.868606,
                        "SOS": 611.658807,
                        "SRD": 36.344505,
                        "STD": 22152.268151,
                        "SVC": 9.387985,
                        "SYP": 2689.066458,
                        "SZL": 20.241123,
                        "THB": 39.615799,
                        "TJS": 11.72143,
                        "TMT": 3.745918,
                        "TND": 3.369226,
                        "TOP": 2.553008,
                        "TRY": 34.792126,
                        "TTD": 7.289765,
                        "TWD": 34.893801,
                        "TZS": 2778.791496,
                        "UAH": 42.485057,
                        "UGX": 4092.429296,
                        "USD": 1.070262,
                        "UYU": 41.46435,
                        "UZS": 13561.422595,
                        "VEF": 3877081.300967,
                        "VES": 38.933568,
                        "VND": 27125.797249,
                        "VUV": 127.063717,
                        "WST": 3.000596,
                        "XAF": 656.025817,
                        "XAG": 0.039359,
                        "XAU": 0.000458,
                        "XCD": 2.892438,
                        "XDR": 0.816186,
                        "XOF": 656.025817,
                        "XPF": 119.331742,
                        "YER": 267.966956,
                        "ZAR": 20.099654,
                        "ZMK": 9633.648589,
                        "ZMW": 28.431983,
                        "ZWL": 344.624014
                    }
                }
                JSON,
            ),
        );
        $exchangeRatesApiExchangeRateProvider = new ExchangeRatesApi(
            $mockHttpClient,
            $this->serializer,
            $this->cache,
            self::API_KEY,
        );
        $this->assertEquals(344.624014, $exchangeRatesApiExchangeRateProvider->get("ZWL"));
    }

    public function testHttpClientException(): void
    {
        $mockHttpClient                       = new MockHttpClient(
            new MockResponse(
                '',
                ['http_code' => 502],
            ),
        );
        $exchangeRatesApiExchangeRateProvider = new ExchangeRatesApi(
            $mockHttpClient,
            $this->serializer,
            $this->cache,
            self::API_KEY,
        );
        $this->expectException(ExchangeRateCannotBeAcquiredException::class);
        $exchangeRatesApiExchangeRateProvider->get("ZWL");
    }

    public function testSerializerException(): void
    {
        $mockHttpClient = new MockHttpClient(
            new MockResponse(
                <<<JSON
                {
                    "success": true,
                    "timestamp": 1714262885,
                    "base": "EUR",
                    "date": "2024-04-28"
                }
                JSON,
            ),
        );
        $exchangeRatesApiExchangeRateProvider = new ExchangeRatesApi(
            $mockHttpClient,
            $this->serializer,
            $this->cache,
            self::API_KEY,
        );
        $this->expectException(ExchangeRateCannotBeAcquiredException::class);
        $exchangeRatesApiExchangeRateProvider->get("ZWL");
    }
}
