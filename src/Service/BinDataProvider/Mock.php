<?php

declare(strict_types=1);

namespace App\Service\BinDataProvider;

use App\DTO\BinData;
use App\Service\BinDataProviderInterface;

readonly class Mock implements BinDataProviderInterface
{

    public function get(string $bin): BinData
    {
        return new BinData(
            match ($bin) {
                "45417360" => "JP",
                "45717360" => "DK",
                "516793", "4745030" => "LT",
                default => "US"
            },
        );
    }
}
