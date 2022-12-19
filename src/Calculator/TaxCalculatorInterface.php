<?php

declare(strict_types=1);

namespace ITB\CalculationPipeline\Calculator;

use ITB\CalculationPipeline\CalculationRequest\TaxRate;
use ITB\CalculationPipeline\ItemCalculation\ItemCalculationSubTotalCollection;
use Money\Currency;
use Money\Money;

interface TaxCalculatorInterface
{
    /**
     * @param Money $itemPrice
     * @param TaxRate $taxRate
     * @return Money
     */
    public function calculateTaxOnPrice(Money $itemPrice, TaxRate $taxRate): Money;

    /**
     * @param ItemCalculationSubTotalCollection $itemCalculationSubTotals
     * @param Currency $currency
     * @return TaxRate
     */
    public function calculateTaxRateOnNonItemPrice(
        ItemCalculationSubTotalCollection $itemCalculationSubTotals,
        Currency $currency
    ): TaxRate;
}
