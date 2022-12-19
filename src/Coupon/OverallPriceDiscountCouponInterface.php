<?php

declare(strict_types=1);

namespace ITB\CalculationPipeline\Coupon;

use ITB\CalculationPipeline\CouponInterface;
use Money\Money;

interface OverallPriceDiscountCouponInterface extends CouponInterface
{
    /**
     * Applies the coupon to the "intermediate" overall price. A Negative overall price will be set to 0.
     * The checkApplicabilityInCalculation method is called within to validate that all conflicts were resolved.
     *
     * @param Money $intermediateOverallPrice
     * @param Money $nonDiscountedOverallPrice
     * @return Money
     */
    public function applyInCalculation(Money $intermediateOverallPrice, Money $nonDiscountedOverallPrice): Money;
}
