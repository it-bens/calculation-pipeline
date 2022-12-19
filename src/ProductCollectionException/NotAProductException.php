<?php

declare(strict_types=1);

namespace ITB\CalculationPipeline\ProductCollectionException;

use ITB\CalculationPipeline\Exception\RequestExceptionInterface;
use ITB\CalculationPipeline\Product;
use RuntimeException;

final class NotAProductException extends RuntimeException implements RequestExceptionInterface
{
    /**
     * @param int $index
     * @return NotAProductException
     */
    public static function create(int $index): NotAProductException
    {
        return new self(
            sprintf('The value of the product array at index %d is not an instance of %s.', $index, Product::class)
        );
    }
}
