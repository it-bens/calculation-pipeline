<?php

declare(strict_types=1);

namespace ITB\CalculationPipeline\Coupon;

use ITB\CalculationPipeline\CouponInterface;
use ITB\CalculationPipeline\ItemCalculation;
use Money\Money;

interface UnitPriceDiscountCouponInterface extends CouponInterface
{
    /**
     * Applies the coupon to the "intermediate" unit price. Negative unit prices will be set to 0.
     * The checkApplicabilityInCalculation method is called within to validate that all problems were resolved.
     *
     * @param Money $unitPrice
     * @param Money $nonDiscountedOverallPrice
     * @return Money
     */
    public function applyInItemCalculation(Money $unitPrice, Money $nonDiscountedOverallPrice): Money;

    /**
     * Checks weather this coupon's requirements are fulfilled to be used for a specific item calculation (including item).
     *
     * @param ItemCalculation $itemCalculation
     * @return bool
     */
    public function checkApplicabilityForItemCalculation(ItemCalculation $itemCalculation): bool;
}
