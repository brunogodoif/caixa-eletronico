<?php

namespace Tests\Unit;

use App\Domain\Exceptions\InvalidOperationException;
use App\Domain\Operation;
use PHPUnit\Framework\TestCase;

class OperationTest extends TestCase
{
    public function testCreateInstance()
    {
        $operation = new Operation(100, "2023-07-22 12:34:56");
        $this->assertInstanceOf(Operation::class, $operation);
    }

    public function testCreatefromJsonValid()
    {
        $data = [
            "saque" => [
                "valor" => 100,
                "horario" => "2023-07-22 12:34:56"
            ]
        ];

        $operation = Operation::createfromJson($data);

        $this->assertInstanceOf(Operation::class, $operation);
        $this->assertEquals(100, $operation->getValue());
        $this->assertEquals("2023-07-22 12:34:56", $operation->getCreatedAt()->format("Y-m-d H:i:s"));
    }

    public function testCreatefromJsonInvalid()
    {
        $data = [
            "valor" => 100,
            "horario" => "2023-07-22 12:34:56"
        ];

        $this->expectException(InvalidOperationException::class);
        Operation::createfromJson($data);
    }

}