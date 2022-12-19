<?php

declare(strict_types=1);

namespace ITB\CalculationPipeline\CouponCollectionException;

use ITB\CalculationPipeline\Coupon\UnitPriceDiscountCouponInterface;
use ITB\CalculationPipeline\Exception\CalculationExceptionInterface;
use ITB\CalculationPipeline\Exception\RequestExceptionInterface;
use RuntimeException;

final class NotAUnitPriceDiscountCouponException extends RuntimeException implements
    RequestExceptionInterface,
    CalculationExceptionInterface
{
    /**
     * @param int $index
     * @return NotAUnitPriceDiscountCouponException
     */
    public static function create(int $index): NotAUnitPriceDiscountCouponException
    {
        return new self(
            sprintf(
                'The value of the coupon array at index %d is not an instance of %s.',
                $index,
                UnitPriceDiscountCouponInterface::class
            )
        );
    }
}
