<?php

declare(strict_types=1);

namespace ITB\CalculationPipeline\Calculator;

use ITB\CalculationPipeline\Calculation\CalculationGrandTotal;
use ITB\CalculationPipeline\Calculation\CalculationSubTotal;
use ITB\CalculationPipeline\Calculation\CalculationTotal;
use ITB\CalculationPipeline\CouponCollection\OtherPaymentCouponCollection;
use ITB\CalculationPipeline\CouponCollection\OverallPriceDiscountCouponCollection;
use ITB\CalculationPipeline\CouponCollection\ShippingCostsDiscountCouponCollection;
use ITB\CalculationPipeline\ItemCalculation\ItemCalculationSubTotalCollection;
use ITB\CalculationPipeline\ItemCalculation\ItemCalculationTotalCollection;
use Money\Money;

interface TotalCalculatorInterface
{
    /**
     * @param CalculationTotal $calculationTotal
     * @param OtherPaymentCouponCollection $otherPaymentCoupons
     * @param Money $nonDiscountedOverallPrice
     * @return CalculationGrandTotal
     */
    public function calculateGrandTotal(
        CalculationTotal $calculationTotal,
        OtherPaymentCouponCollection $otherPaymentCoupons,
        Money $nonDiscountedOverallPrice
    ): CalculationGrandTotal;

    /**
     * @param ItemCalculationSubTotalCollection $itemCalculationSubTotals
     * @return CalculationSubTotal
     */
    public function calculateSubTotal(ItemCalculationSubTotalCollection $itemCalculationSubTotals): CalculationSubTotal;

    /**
     * @param CalculationSubTotal $calculationSubTotal
     * @param ItemCalculationTotalCollection $itemCalculationTotals
     * @param OverallPriceDiscountCouponCollection $overallPriceDiscountCoupons
     * @param ShippingCostsDiscountCouponCollection $shippingCostsDiscountCoupons
     * @param Money $shippingCosts
     * @param Money $nonDiscountedOverallPrice
     * @return CalculationTotal
     */
    public function calculateTotal(
        CalculationSubTotal $calculationSubTotal,
        ItemCalculationTotalCollection $itemCalculationTotals,
        OverallPriceDiscountCouponCollection $overallPriceDiscountCoupons,
        ShippingCostsDiscountCouponCollection $shippingCostsDiscountCoupons,
        Money $shippingCosts,
        Money $nonDiscountedOverallPrice
    ): CalculationTotal;
}
