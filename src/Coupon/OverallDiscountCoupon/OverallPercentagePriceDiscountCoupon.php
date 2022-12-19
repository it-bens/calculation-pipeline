<?php

declare(strict_types=1);

namespace ITB\CalculationPipeline\Coupon\OverallDiscountCoupon;

use ITB\CalculationPipeline\Coupon\Coupon;
use ITB\CalculationPipeline\Coupon\OverallPriceDiscountCouponInterface;
use ITB\CalculationPipeline\Discount\PercentageDiscount;
use Money\Money;

final class OverallPercentagePriceDiscountCoupon extends Coupon implements OverallPriceDiscountCouponInterface
{
    /** @var PercentageDiscount */
    private $overallPercentageDiscount;

    /**
     * An overall percentage discount coupon is added to the overall calculation but applied to every unit of a product.
     *
     * There is no black- or whitelisting for products.
     *
     * @param string $name
     * @param PercentageDiscount $overallPercentageDiscount
     * @param Money $nonDiscountedOverallPrice
     */
    public function __construct(
        string $name,
        PercentageDiscount $overallPercentageDiscount,
        Money $nonDiscountedOverallPrice
    ) {
        parent::__construct($name, $nonDiscountedOverallPrice);

        $this->overallPercentageDiscount = $overallPercentageDiscount;
    }

    /**
     * @param Money $intermediateOverallPrice
     * @param Money $nonDiscountedOverallPrice
     * @return Money
     */
    public function applyInCalculation(Money $intermediateOverallPrice, Money $nonDiscountedOverallPrice): Money
    {
        // The checks are repeated because the calling class should clear any conflicts before starting the calculation with coupons.
        $this->checkApplicabilityInCalculation($nonDiscountedOverallPrice);

        return $intermediateOverallPrice->multiply($this->overallPercentageDiscount->getDiscountFactor());
    }
}
