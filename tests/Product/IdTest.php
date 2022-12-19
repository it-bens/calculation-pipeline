<?php

declare(strict_types=1);

namespace ITB\CalculationPipeline\Tests\Product;

use Generator;
use ITB\CalculationPipeline\Product\Id;
use ITB\CalculationPipeline\Product\IdException\IdBlankException;
use PHPUnit\Framework\TestCase;

final class IdTest extends TestCase
{
    /**
     * @return Generator
     */
    public function provideForTestGet(): Generator
    {
        $id = new Id('09491eb1-2eb0-4f2c-b7d8-8c8518a63129');

        yield [$id, '09491eb1-2eb0-4f2c-b7d8-8c8518a63129'];
    }

    /**
     * @return Generator
     */
    public function provideForTestWithInvalidId(): Generator
    {
        yield 'blank id' => ['', IdBlankException::class];
    }

    /**
     * @return Generator
     */
    public function provideForTestWithValidId(): Generator
    {
        yield 'UUIDv4' => ['09491eb1-2eb0-4f2c-b7d8-8c8518a63129'];
        yield 'zero' => ['0'];
        yield 'number' => ['1337'];
    }

    /**
     * @dataProvider provideForTestGet
     *
     * @param Id $id
     * @param string $expectedId
     * @return void
     */
    public function testGet(Id $id, string $expectedId): void
    {
        $this->assertEquals($expectedId, $id->get());
    }

    /**
     * @dataProvider provideForTestWithInvalidId
     *
     * @param string $id
     * @param class-string $expectedException
     * @return void
     */
    public function testWithInvalidId(string $id, string $expectedException): void
    {
        $this->expectException($expectedException);
        new Id($id);
    }

    /**
     * @dataProvider provideForTestWithValidId
     *
     * @param string $id
     * @return void
     */
    public function testWithValidId(string $id): void
    {
        $id = new Id($id);
        $this->assertInstanceOf(Id::class, $id);
    }
}