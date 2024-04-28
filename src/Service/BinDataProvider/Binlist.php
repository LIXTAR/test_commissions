<?php

declare(strict_types=1);

namespace App\Service\BinDataProvider;

use App\Constant\DataType;
use App\DTO\BinData;
use App\DTO\Binlist\BinListResult;
use App\Exception\BinDataCannotBeAcquiredException;
use App\Service\BinDataProviderInterface;
use Psr\Cache\InvalidArgumentException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Exception\ExceptionInterface as SerializerExceptionInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\HttpClient\Exception\ExceptionInterface as HttpExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

readonly class Binlist implements BinDataProviderInterface
{
    private const ENDPOINT_TEMPLATE = "https://lookup.binlist.net/%s";
    private const CACHE_PREFIX      = "bld";

    public function __construct(
        private HttpClientInterface $httpClient,
        private SerializerInterface $serializer,
        private CacheInterface      $binDataCache,
    ) {
    }

    /**
     * @throws BinDataCannotBeAcquiredException
     */
    public function get(string $bin): BinData
    {
        try {
            return $this->binDataCache->get(
                $this->calculateCacheKey($bin),
                fn() => new BinData($this->fetch($bin)),
            );
        } catch (InvalidArgumentException|HttpExceptionInterface|SerializerExceptionInterface $exception) {
            throw new BinDataCannotBeAcquiredException(previous: $exception);
        }
    }

    private function calculateCacheKey(string $bin): string
    {
        return sprintf("%s_%s", self::CACHE_PREFIX, md5($bin));
    }

    /**
     * @throws HttpExceptionInterface
     * @throws SerializerExceptionInterface
     */
    private function fetch(string $bin): string
    {
        /** @var BinListResult $binResult */
        $binResult = $this->serializer->deserialize(
            $this->httpClient
                ->request(
                    Request::METHOD_GET,
                    sprintf(self::ENDPOINT_TEMPLATE, $bin),
                )
                ->getContent(),
            BinListResult::class,
            DataType::JSON->value,
        );

        return $binResult->country->alpha2;
    }
}
