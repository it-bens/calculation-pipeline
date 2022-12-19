<?php

declare(strict_types=1);

namespace ITB\CalculationPipeline\Coupon\ShippingCostsDiscountCoupon;

use ITB\CalculationPipeline\Coupon\Coupon;
use ITB\CalculationPipeline\Coupon\ShippingCostsDiscountCouponInterface;
use ITB\CalculationPipeline\Discount\PercentageDiscount;
use Money\Money;

final class ShippingCostsPercentageDiscountCoupon extends Coupon implements ShippingCostsDiscountCouponInterface
{
    /** @var PercentageDiscount */
    private $percentageShippingCostsDiscount;

    /**
     * A shipping costs percentage coupon reduces the shipping costs by a given percentage (if the minimal overall price is reached).
     *
     * @param string $name
     * @param PercentageDiscount $percentageShippingCostsDiscount
     * @param Money $nonDiscountedOverallPrice
     */
    public function __construct(
        string $name,
        PercentageDiscount $percentageShippingCostsDiscount,
        Money $nonDiscountedOverallPrice
    ) {
        parent::__construct($name, $nonDiscountedOverallPrice);

        $this->percentageShippingCostsDiscount = $percentageShippingCostsDiscount;
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

        return $intermediateShippingCosts->multiply($this->percentageShippingCostsDiscount->getDiscountFactor());
    }
}
