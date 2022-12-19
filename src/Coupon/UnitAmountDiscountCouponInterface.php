<?php

declare(strict_types=1);

namespace ITB\CalculationPipeline\Coupon;

use ITB\CalculationPipeline\CouponInterface;
use ITB\CalculationPipeline\ItemCalculation;
use ITB\CalculationPipeline\Product\Amount;
use Money\Money;

interface UnitAmountDiscountCouponInterface extends CouponInterface
{
    /**
     * Applies the coupon to the "intermediate" unit price. Negative unit prices will be set to 0.
     * The checkApplicabilityInCalculation method is called within to validate that all problems were resolved.
     *
     * @param Amount $unitAmount
     * @param Money $nonDiscountedOverallPrice
     * @return Amount
     */
    public function applyInItemCalculation(Amount $unitAmount, Money $nonDiscountedOverallPrice): Amount;

    /**
     * Checks weather this coupon's requirements are fulfilled to be used for a specific item calculation (including item).
     *
     * @param ItemCalculation $itemCalculation
     * @return bool
     */
    public function checkApplicabilityForItemCalculation(ItemCalculation $itemCalculation): bool;
}
