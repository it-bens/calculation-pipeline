<?php

declare(strict_types=1);

namespace ITB\CalculationPipeline\Tests\Product;

use Generator;
use ITB\CalculationPipeline\Product\Amount;
use ITB\CalculationPipeline\Product\AmountException\AmountTooLowException;
use PHPUnit\Framework\TestCase;

final class AmountTest extends TestCase
{
    /**
     * @return Generator
     */
    public function provideForTestGet(): Generator
    {
        $amount = new Amount(1337);

        yield [$amount, 1337];
    }

    /**
     * @return Generator
     */
    public function provideForTestWithInvalidAmount(): Generator
    {
        yield 'amount zero' => [0, AmountTooLowException::class];
        yield 'amount negative' => [-5, AmountTooLowException::class];
    }

    /**
     * @return Generator
     */
    public function provideForTestWithValidAmount(): Generator
    {
        yield '1' => [1];
        yield '1000' => [1000];
        yield '1337' => [1337];
    }

    /**
     * @dataProvider provideForTestGet
     *
     * @param Amount $amount
     * @param int $expectedAmount
     * @return void
     */
    public function testGet(Amount $amount, int $expectedAmount): void
    {
        $this->assertEquals($expectedAmount, $amount->get());
    }

    /**
     * @dataProvider provideForTestWithInvalidAmount
     *
     * @param int $amount
     * @param class-string $expectedException
     * @return void
     */
    public function testWithInvalidAmount(int $amount, string $expectedException): void
    {
        $this->expectException($expectedException);
        new Amount($amount);
    }

    /**
     * @dataProvider provideForTestWithValidAmount
     *
     * @param int $amount
     * @return void
     */
    public function testWithValidAmount(int $amount): void
    {
        $amount = new Amount($amount);
        $this->assertInstanceOf(Amount::class, $amount);
    }
}