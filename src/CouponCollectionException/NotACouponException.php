<?php

declare(strict_types=1);

namespace ITB\CalculationPipeline\CouponCollectionException;

use ITB\CalculationPipeline\CouponInterface;
use ITB\CalculationPipeline\Exception\CalculationExceptionInterface;
use ITB\CalculationPipeline\Exception\RequestExceptionInterface;
use RuntimeException;

final class NotACouponException extends RuntimeException implements
    RequestExceptionInterface,
    CalculationExceptionInterface
{
    /**
     * @param int $index
     * @return NotACouponException
     */
    public static function create(int $index): NotACouponException
    {
        return new self(
            sprintf(
                'The value of the coupon array at index %d is not an instance of %s.',
                $index,
                CouponInterface::class
            )
        );
    }
}
