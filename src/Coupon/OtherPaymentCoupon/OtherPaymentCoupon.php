<?php

declare(strict_types=1);

namespace ITB\CalculationPipeline\Coupon\OtherPaymentCoupon;

use ITB\CalculationPipeline\Coupon\Coupon;
use ITB\CalculationPipeline\Coupon\OtherPaymentCouponInterface;
use Money\Money;

final class OtherPaymentCoupon extends Coupon implements OtherPaymentCouponInterface
{
    /** @var Money */
    private $absoluteValue;

    /**
     * A coupon acting as a payment method that reduces the overall price. The absolute discount is a gross value.
     *
     * @param string $name
     * @param Money $absoluteValue
     * @param Money $nonDiscountedOverallPrice
     */
    public function __construct(string $name, Money $absoluteValue, Money $nonDiscountedOverallPrice)
    {
        parent::__construct($name, $nonDiscountedOverallPrice);

        $this->absoluteValue = $absoluteValue;
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

        $reducedOverallPrice = $intermediateOverallPrice->subtract($this->absoluteValue);

        // Returns the reduced overall price or 0 if it's below 0.
        return Money::max($reducedOverallPrice, new Money(0, $intermediateOverallPrice->getCurrency()));
    }
}