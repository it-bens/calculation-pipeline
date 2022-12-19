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

final class TotalCalculator implements TotalCalculatorInterface
{
    /** @var TaxCalculatorInterface */
    private $taxCalculator;

    /**
     * @param TaxCalculatorInterface $taxCalculator
     */
    public function __construct(TaxCalculatorInterface $taxCalculator)
    {
        $this->taxCalculator = $taxCalculator;
    }

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
    ): CalculationGrandTotal {
        $grandTotal = $calculationTotal->getTotal();
        $grandTotal = $otherPaymentCoupons->applyAllCouponsInCalculation($grandTotal, $nonDiscountedOverallPrice);

        $discountedGrandTotal = $calculationTotal->getDiscountedTotal();
        $discountedGrandTotal = $otherPaymentCoupons->applyAllCouponsInCalculation(
            $discountedGrandTotal,
            $nonDiscountedOverallPrice
        );

        return new CalculationGrandTotal($calculationTotal, $grandTotal, $discountedGrandTotal);
    }

    /**
     * @param ItemCalculationSubTotalCollection $itemCalculationSubTotals
     * @return CalculationSubTotal
     */
    public function calculateSubTotal(ItemCalculationSubTotalCollection $itemCalculationSubTotals): CalculationSubTotal
    {
        $subTotal = $itemCalculationSubTotals->getSubTotalSum();
        $discountedSubTotal = $itemCalculationSubTotals->getDiscountedSubTotalSum();

        return new CalculationSubTotal($itemCalculationSubTotals, $subTotal, $discountedSubTotal);
    }

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
    ): CalculationTotal {
        $total = $itemCalculationTotals->getTotalSum();

        $discountedTotal = $itemCalculationTotals->getDiscountedTotalSum();
        $discountedTotal = $overallPriceDiscountCoupons->applyAllCouponsInCalculation(
            $discountedTotal,
            $nonDiscountedOverallPrice
        );

        $discountedShippingCosts = $shippingCostsDiscountCoupons->applyAllCouponsInCalculation(
            $shippingCosts,
            $nonDiscountedOverallPrice
        );

        $nonItemTaxRate = $this->taxCalculator->calculateTaxRateOnNonItemPrice(
            $calculationSubTotal->getItemCalculationSubTotals(),
            $nonDiscountedOverallPrice->getCurrency()
        );
        $shippingCosts = $shippingCosts->multiply(1 + $nonItemTaxRate->getTaxFactor());
        $discountedShippingCosts = $discountedShippingCosts->multiply(1 + $nonItemTaxRate->getTaxFactor());

        $total = $total->add($shippingCosts);
        $discountedTotal = $discountedTotal->add($discountedShippingCosts);

        return new CalculationTotal(
            $calculationSubTotal,
            $itemCalculationTotals,
            $shippingCosts,
            $discountedShippingCosts,
            $total,
            $discountedTotal
        );
    }
}
