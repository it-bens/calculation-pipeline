<?php

declare(strict_types=1);

namespace ITB\CalculationPipeline\Calculator;

use ITB\CalculationPipeline\CouponCollection\UnitAmountDiscountCouponCollection;
use ITB\CalculationPipeline\CouponCollection\UnitPriceDiscountCouponCollection;
use ITB\CalculationPipeline\ItemCalculation;
use ITB\CalculationPipeline\ItemCalculation\ItemCalculationSubTotal;
use ITB\CalculationPipeline\ItemCalculation\ItemCalculationTotal;
use Money\Money;

final class ItemTotalCalculator implements ItemTotalCalculatorInterface
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
    ): ItemCalculationSubTotal {
        $unitPrice = $itemCalculation->getProduct()->getUnitPrice();
        $unitAmount = $itemCalculation->getAmount();

        $discountedUnitPrice = $unitPriceDiscountCoupons->applyAllCouponsInItemCalculation(
            $unitPrice,
            $nonDiscountedOverallPrice
        );
        $reducedUnitAmount = $unitAmountDiscountCoupons->applyAllCouponsInItemCalculation(
            $unitAmount,
            $nonDiscountedOverallPrice
        );

        $subTotal = $unitPrice->multiply($unitAmount->get());
        $discountedSubTotal = $discountedUnitPrice->multiply($reducedUnitAmount->get());

        return new ItemCalculationSubTotal(
            $itemCalculation,
            $unitPrice,
            $unitAmount,
            $discountedUnitPrice,
            $reducedUnitAmount,
            $subTotal,
            $discountedSubTotal
        );
    }

    /**
     * @param ItemCalculationSubTotal $itemCalculationSubTotal
     * @return ItemCalculationTotal
     */
    public function calculateItemTotal(ItemCalculationSubTotal $itemCalculationSubTotal): ItemCalculationTotal
    {
        $taxRate = $itemCalculationSubTotal->getItemCalculation()->getProduct()->getTaxRate();

        $taxesOnPrice = $this->taxCalculator->calculateTaxOnPrice($itemCalculationSubTotal->getSubTotal(), $taxRate);
        $taxesOnDiscountedPrice = $this->taxCalculator->calculateTaxOnPrice(
            $itemCalculationSubTotal->getDiscountedSubTotal(),
            $taxRate
        );

        $total = $itemCalculationSubTotal->getSubTotal()->add($taxesOnPrice);
        $discountedTotal = $itemCalculationSubTotal->getDiscountedSubTotal()->add($taxesOnDiscountedPrice);

        return new ItemCalculationTotal(
            $itemCalculationSubTotal,
            $taxRate,
            $taxesOnPrice,
            $taxesOnDiscountedPrice,
            $total,
            $discountedTotal
        );
    }
}
