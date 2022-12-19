<?php

declare(strict_types=1);

namespace ITB\CalculationPipeline\Coupon\UnitAmountDiscountCoupon\RequiredAmountFreeAmountCoupon;

use ITB\CalculationPipeline\Exception\RequestExceptionInterface;
use RuntimeException;

final class FreeAmountGraterThanRequiredAmountException extends RuntimeException implements RequestExceptionInterface
{
    /**
     * @param int $freeAmount
     * @param int $requiredAmount
     * @return FreeAmountGraterThanRequiredAmountException
     */
    public static function create(int $freeAmount, int $requiredAmount): FreeAmountGraterThanRequiredAmountException
    {
        return new self(
            sprintf(
                'The free amount %d is greater than the required amount %d. This is not allowed.',
                $freeAmount,
                $requiredAmount
            )
        );
    }
}
