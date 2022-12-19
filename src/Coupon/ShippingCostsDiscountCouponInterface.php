<?php

declare(strict_types=1);

namespace ITB\CalculationPipeline\Coupon;

use ITB\CalculationPipeline\CouponInterface;
use Money\Money;

interface ShippingCostsDiscountCouponInterface extends CouponInterface
{
    /**
     * @param Money $intermediateShippingCosts
     * @param Money $nonDiscountedOverallPrice
     * @return Money
     */
    public function applyInShippingCostsCalculation(
        Money $intermediateShippingCosts,
        Money $nonDiscountedOverallPrice
    ): Money;
}
