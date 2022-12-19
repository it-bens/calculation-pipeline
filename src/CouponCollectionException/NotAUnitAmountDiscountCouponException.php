<?php

declare(strict_types=1);

namespace ITB\CalculationPipeline\CouponCollectionException;

use ITB\CalculationPipeline\Coupon\UnitAmountDiscountCouponInterface;
use ITB\CalculationPipeline\Exception\CalculationExceptionInterface;
use ITB\CalculationPipeline\Exception\RequestExceptionInterface;
use RuntimeException;

final class NotAUnitAmountDiscountCouponException extends RuntimeException implements
    RequestExceptionInterface,
    CalculationExceptionInterface
{
    /**
     * @param int $index
     * @return NotAUnitAmountDiscountCouponException
     */
    public static function create(int $index): NotAUnitAmountDiscountCouponException
    {
        return new self(
            sprintf(
                'The value of the coupon array at index %d is not an instance of %s.',
                $index,
                UnitAmountDiscountCouponInterface::class
            )
        );
    }
}
