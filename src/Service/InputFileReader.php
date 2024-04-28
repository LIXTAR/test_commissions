<?php

declare(strict_types=1);

namespace App\Service;

use App\Constant\DataType;
use App\DTO\TransactionDetail;
use Iterator;
use Symfony\Component\Serializer\Exception\ExceptionInterface as SerializerExceptionInterfaceAlias;
use Symfony\Component\Serializer\SerializerInterface;

class InputFileReader
{
    private array $handles;

    public function __construct(private readonly SerializerInterface $serializer)
    {
    }

    /**
     * @throws SerializerExceptionInterfaceAlias
     */
    public function readOne(string $inputFilePath): Iterator
    {
        $this->handles[md5($inputFilePath)] ??= fopen($inputFilePath, 'r');

        while ($rowRow = fgets($this->handles[md5($inputFilePath)])) {
            yield $this->serializer->deserialize(
                $rowRow,
                TransactionDetail::class,
                DataType::JSON->value,
            );
        }
    }
}
