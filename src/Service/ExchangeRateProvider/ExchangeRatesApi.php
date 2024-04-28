<?php

declare(strict_types=1);

namespace App\Service\ExchangeRateProvider;

use App\Constant\DataType;
use App\DTO\ExchangeRateApi\ExchangeRatesApiLatest;
use App\Exception\ExchangeRateCannotBeAcquiredException;
use App\Service\ExchangeRateProviderInterface;
use DateTimeImmutable;
use Psr\Cache\InvalidArgumentException;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Exception\ExceptionInterface as SerializerExceptionInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\HttpClient\Exception\ExceptionInterface as HttpExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

readonly class ExchangeRatesApi implements ExchangeRateProviderInterface
{
    private const ENDPOINT     = "https://api.exchangeratesapi.io/latest";
    private const CACHE_PREFIX = "era";

    public function __construct(
        private HttpClientInterface $httpClient,
        private SerializerInterface $serializer,
        private CacheInterface      $exchangeRatesCache,
        #[Autowire(env: "EXCHANGE_RATES_API_KEY")]
        private string              $apiKey,
    ) {
    }

    /**
     * @throws ExchangeRateCannotBeAcquiredException
     */
    public function get(
        string $currencyFrom,
        string $currencyTo = ExchangeRateProviderInterface::DEFAULT_CURRENCY_TO,
    ): float {
        try {

            return $this->getExchangeTable()[$currencyFrom];
        } catch (InvalidArgumentException|HttpExceptionInterface|SerializerExceptionInterface $exception) {
            throw new ExchangeRateCannotBeAcquiredException(previous: $exception);
        }
    }

    /**
     * @throws InvalidArgumentException
     * @throws HttpExceptionInterface
     * @throws SerializerExceptionInterface
     */
    private function getExchangeTable(): array
    {
        return $this->exchangeRatesCache->get($this->calculateCacheKey(), fn() => $this->fetch());
    }

    private function calculateCacheKey(): string
    {
        return sprintf("%s_%s", self::CACHE_PREFIX, md5((new DateTimeImmutable())->format('Ymd')));
    }

    /**
     * @throws HttpExceptionInterface
     * @throws SerializerExceptionInterface
     */
    private function fetch(): array
    {
        /** @var ExchangeRatesApiLatest $exchangeResult */
        $exchangeResult = $this->serializer->deserialize(
            $this->httpClient
                ->request(
                    Request::METHOD_GET,
                    self::ENDPOINT . '?' . http_build_query(['access_key' => $this->apiKey]),
                )
                ->getContent(),
            ExchangeRatesApiLatest::class,
            DataType::JSON->value,
        );

        return $exchangeResult->rates;
    }
}
