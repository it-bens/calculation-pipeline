<?php

declare(strict_types=1);

namespace ITB\CalculationPipeline\Calculator;

use ITB\CalculationPipeline\CalculationRequest\TaxRate;
use ITB\CalculationPipeline\ItemCalculation\ItemCalculationSubTotal;
use Money\Currency;
use Money\Money;

final class TaxCalculator implements TaxCalculatorInterface
{
    /**
     * @param Money $itemPrice
     * @param TaxRate $taxRate
     * @return Money
     */
    public function calculateTaxOnPrice(Money $itemPrice, TaxRate $taxRate): Money
    {
        return $itemPrice->multiply($taxRate->getTaxFactor());
    }

    /**
     * @param Money $nonItemPrice
     * @param ItemCalculationSubTotal[] $itemCalculationSubTotals
     * @return TaxRate
     */
    public function calculateTaxRateOnNonItemPrice(array $itemCalculationSubTotals, Currency $currency): TaxRate
    {
        $subTotal = new Money(0, $currency);
        foreach ($itemCalculationSubTotals as $itemCalculationSubTotal) {
            $subTotal = $subTotal->add($itemCalculationSubTotal->getSubTotal());
        }

        $weightedTaxRate = 0;
        foreach ($itemCalculationSubTotals as $itemCalculationSubTotal) {
            $weight = $itemCalculationSubTotal->getSubTotal()->ratioOf($subTotal);
            $weightedTaxRate = $weightedTaxRate + (float)bcmul(
                (string)$itemCalculationSubTotal->getItemCalculation()->getProduct()->getTaxRate()->get(),
                $weight
            );
        }

        return new TaxRate($weightedTaxRate);
    }
}
