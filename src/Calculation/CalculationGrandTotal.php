<?php

declare(strict_types=1);

namespace ITB\CalculationPipeline\Calculation;

use Money\Money;

final class CalculationGrandTotal
{
    /** @var CalculationTotal */
    private $calculationTotal;

    /** @var Money */
    private $grandTotal;
    /** @var Money */
    private $discountedGrandTotal;

    /**
     * @param CalculationTotal $calculationTotal
     * @param Money $grandTotal
     * @param Money $discountedGrandTotal
     */
    public function __construct(CalculationTotal $calculationTotal, Money $grandTotal, Money $discountedGrandTotal)
    {
        $this->calculationTotal = $calculationTotal;
        $this->grandTotal = $grandTotal;
        $this->discountedGrandTotal = $discountedGrandTotal;
    }

    /**
     * @return CalculationTotal
     */
    public function getCalculationTotal(): CalculationTotal
    {
        return $this->calculationTotal;
    }

    /**
     * @return Money
     */
    public function getDiscountedGrandTotal(): Money
    {
        return $this->discountedGrandTotal;
    }

    /**
     * @return Money
     */
    public function getGrandTotal(): Money
    {
        return $this->grandTotal;
    }
}
