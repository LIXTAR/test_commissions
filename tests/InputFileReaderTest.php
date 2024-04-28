<?php

declare(strict_types=1);

namespace App\Tests;

use App\DTO\TransactionDetail;
use App\Service\InputFileReader;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Serializer\Exception\ExceptionInterface as SerializerExceptionInterface;

final class InputFileReaderTest extends KernelTestCase
{
    private readonly InputFileReader $inputFileReader;

    private readonly string          $tempFilePath;

    protected function setUp(): void
    {
        $this->inputFileReader = KernelTestCase::getContainer()->get(InputFileReader::class);
        $this->tempFilePath    =
            KernelTestCase::getContainer()->getParameter('kernel.project_dir') . '/var/input_file.txt';

        if (file_exists($this->tempFilePath)) {
            unlink($this->tempFilePath);
        }
    }

    public function testPositive(): void
    {
        file_put_contents(
            $this->tempFilePath,
            <<<JSON
            {"bin":"45717360","amount":"100.00","currency":"EUR"}
            JSON
        );

        /** @var TransactionDetail $detail */
        foreach ($this->inputFileReader->readOne($this->tempFilePath) as $detail) {
            $this->assertEquals("45717360", $detail->bin);
            $this->assertEquals("100.00", $detail->amount);
            $this->assertEquals("EUR", $detail->currency);
        }
    }

    public function testSerializerException(): void
    {
        file_put_contents($this->tempFilePath, 'unexpected input string');
        $this->expectException(SerializerExceptionInterface::class);
        $this->inputFileReader->readOne($this->tempFilePath)->current();
    }
}
