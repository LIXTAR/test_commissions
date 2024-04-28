<?php

declare(strict_types=1);

namespace App\Tests;

use App\Exception\BinDataCannotBeAcquiredException;
use App\Service\BinDataProvider\Binlist;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\HttpClient\MockHttpClient;
use Symfony\Component\HttpClient\Response\MockResponse;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Contracts\Cache\CacheInterface;

final class BinlistBinDataProviderTest extends KernelTestCase
{
    private readonly SerializerInterface $serializer;

    private readonly CacheInterface      $cache;

    protected function setUp(): void
    {
        $container        = KernelTestCase::getContainer();
        $this->serializer = $container->get('serializer');
        $this->cache      = $container->get('bin_data.cache');
    }

    public function testPositive(): void
    {
        $mockHttpClient         = new MockHttpClient(
            new MockResponse(
                <<<JSON
                {
                    "number":{},
                    "scheme":"visa",
                    "type":"debit",
                    "brand":"Visa Classic",
                    "country":{
                        "numeric":"440",
                        "alpha2":"LT",
                        "name":"Lithuania",
                        "emoji":"🇱🇹",
                        "currency":"EUR",
                        "latitude":56,
                        "longitude":24
                    },
                    "bank":{
                        "name":"Uab Finansines Paslaugos Contis"
                    }
                }
                JSON
            ),
        );
        $binlistBinDataProvider = new Binlist(
            $mockHttpClient,
            $this->serializer,
            $this->cache,
        );
        $this->assertEquals(
            'LT',
            $binlistBinDataProvider->get("4745030")->countryIsoCode,
        );
    }

    public function testHttpClientException(): void
    {
        $mockHttpClient         = new MockHttpClient(
            new MockResponse(
                '',
                ['http_code' => 429],
            ),
        );
        $binlistBinDataProvider = new Binlist(
            $mockHttpClient,
            $this->serializer,
            $this->cache,
        );
        $this->expectException(BinDataCannotBeAcquiredException::class);
        $binlistBinDataProvider->get("4745030");
    }

    public function testSerializerException(): void
    {
        $mockHttpClient         = new MockHttpClient(
            new MockResponse(
                <<<JSON
                {
                    "number":{},
                    "scheme":"visa",
                    "type":"debit",
                    "brand":"Visa Classic",
                    "country":{
                        "numeric":"440",
                        "alpha":"LT",
                        "name":"Lithuania",
                        "emoji":"🇱🇹",
                        "currency":"EUR",
                        "latitude":56,
                        "longitude":24
                    },
                    "bank":{
                        "name":"Uab Finansines Paslaugos Contis"
                    }
                }
                JSON
            ),
        );
        $binlistBinDataProvider = new Binlist(
            $mockHttpClient,
            $this->serializer,
            $this->cache,
        );
        $this->expectException(BinDataCannotBeAcquiredException::class);
        $binlistBinDataProvider->get("4745030");
    }
}
