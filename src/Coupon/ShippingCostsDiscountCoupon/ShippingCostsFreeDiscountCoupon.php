<?php

declare(strict_types=1);

namespace ITB\CalculationPipeline\Coupon\ShippingCostsDiscountCoupon;

use ITB\CalculationPipeline\Coupon\Coupon;
use ITB\CalculationPipeline\Coupon\ShippingCostsDiscountCouponInterface;
use Money\Money;

final class ShippingCostsFreeDiscountCoupon extends Coupon implements ShippingCostsDiscountCouponInterface
{
    /**
     * A free shipping costs coupon sets the shipping costs to 0 (if the minimal overall price is reached).
     *
     * @param string $name
     * @param Money $nonDiscountedOverallPrice
     */
    public function __construct(string $name, Money $nonDiscountedOverallPrice)
    {
        parent::__construct($name, $nonDiscountedOverallPrice);
    }

    /**
     * @param Money $intermediateShippingCosts
     * @param Money $nonDiscountedOverallPrice
     * @return Money
     */
    public function applyInShippingCostsCalculation(
        Money $intermediateShippingCosts,
        Money $nonDiscountedOverallPrice
    ): Money {
        // The checks are repeated because the calling class should clear any problems before starting the calculation with coupons.
        $this->checkApplicabilityInCalculation($nonDiscountedOverallPrice);

        return new Money(0, $intermediateShippingCosts->getCurrency());
    }
}
