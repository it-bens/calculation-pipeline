<?php

declare(strict_types=1);

namespace ITB\CalculationPipeline\Coupon\UnitAmountDiscountCoupon\RequiredAmountFreeAmountCoupon;

use ITB\CalculationPipeline\Exception\RequestExceptionInterface;
use RuntimeException;

final class MaximumApplicationNotPositiveException extends RuntimeException implements RequestExceptionInterface
{
    /**
     * @param int $maximumApplicationCount
     * @return MaximumApplicationNotPositiveException
     */
    public static function create(int $maximumApplicationCount): MaximumApplicationNotPositiveException
    {
        return new self(
            sprintf('The maximum application count %d is not positive. This is not allowed.', $maximumApplicationCount)
        );
    }
}
