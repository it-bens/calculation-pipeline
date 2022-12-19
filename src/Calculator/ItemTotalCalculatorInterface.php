<?php

declare(strict_types=1);

namespace ITB\CalculationPipeline\Calculator;

use ITB\CalculationPipeline\CouponCollection\UnitAmountDiscountCouponCollection;
use ITB\CalculationPipeline\CouponCollection\UnitPriceDiscountCouponCollection;
use ITB\CalculationPipeline\ItemCalculation;
use ITB\CalculationPipeline\ItemCalculation\ItemCalculationSubTotal;
use ITB\CalculationPipeline\ItemCalculation\ItemCalculationTotal;
use Money\Money;

interface ItemTotalCalculatorInterface
{
    /**
     * @param ItemCalculation $itemCalculation
     * @param UnitPriceDiscountCouponCollection $unitPriceDiscountCoupons
     * @param UnitAmountDiscountCouponCollection $unitAmountDiscountCoupons
     * @param Money $nonDiscountedOverallPrice
     * @return ItemCalculationSubTotal
     */
    public function calculateItemSubTotal(
        ItemCalculation $itemCalculation,
        UnitPriceDiscountCouponCollection $unitPriceDiscountCoupons,
        UnitAmountDiscountCouponCollection $unitAmountDiscountCoupons,
        Money $nonDiscountedOverallPrice
    ): ItemCalculationSubTotal;

    /**
     * @param ItemCalculationSubTotal $itemCalculationSubTotal
     * @return ItemCalculationTotal
     */
    public function calculateItemTotal(ItemCalculationSubTotal $itemCalculationSubTotal): ItemCalculationTotal;
}
