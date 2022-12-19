<?php

declare(strict_types=1);

namespace ITB\CalculationPipeline;

use ITB\CalculationPipeline\CouponException\OverallValueTooLowForCouponException;
use Money\Money;

interface CouponInterface
{
    /**
     * @return string
     */
    public function __toString(): string;

    /**
     * Checks if the passed CouponCollection contains reach the minimal overall price for the coupon application.
     *
     * @param Money $overallPrice
     * @return void
     *
     * @throws OverallValueTooLowForCouponException
     */
    public function checkApplicabilityInCalculation(Money $overallPrice);

    /**
     * @return string
     */
    public function getName(): string;
}
