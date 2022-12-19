<?php

declare(strict_types=1);

namespace ITB\CalculationPipeline\Calculation;

use ITB\CalculationPipeline\ItemCalculation\ItemCalculationTotalCollection;
use Money\Money;

final class CalculationTotal
{
    /** @var CalculationSubTotal */
    private $calculationSubTotal;
    /** @var ItemCalculationTotalCollection */
    private $itemCalculationTotals;

    /** @var Money */
    private $shippingCosts;
    /** @var Money */
    private $discountedShippingCosts;

    /** @var Money */
    private $total;
    /** @var Money */
    private $discountedTotal;

    /**
     * @param CalculationSubTotal $calculationSubTotal
     * @param ItemCalculationTotalCollection $itemCalculationTotals
     * @param Money $shippingCosts
     * @param Money $discountedShippingCosts
     * @param Money $total
     * @param Money $discountedTotal
     */
    public function __construct(
        CalculationSubTotal $calculationSubTotal,
        ItemCalculationTotalCollection $itemCalculationTotals,
        Money $shippingCosts,
        Money $discountedShippingCosts,
        Money $total,
        Money $discountedTotal
    ) {
        $this->calculationSubTotal = $calculationSubTotal;
        $this->itemCalculationTotals = $itemCalculationTotals;
        $this->shippingCosts = $shippingCosts;
        $this->discountedShippingCosts = $discountedShippingCosts;
        $this->total = $total;
        $this->discountedTotal = $discountedTotal;
    }

    /**
     * @return CalculationSubTotal
     */
    public function getCalculationSubTotal(): CalculationSubTotal
    {
        return $this->calculationSubTotal;
    }

    /**
     * @return Money
     */
    public function getDiscountedShippingCosts(): Money
    {
        return $this->discountedShippingCosts;
    }

    /**
     * @return Money
     */
    public function getDiscountedTotal(): Money
    {
        return $this->discountedTotal;
    }

    /**
     * @return ItemCalculationTotalCollection
     */
    public function getItemCalculationTotals(): ItemCalculationTotalCollection
    {
        return $this->itemCalculationTotals;
    }

    /**
     * @return Money
     */
    public function getShippingCosts(): Money
    {
        return $this->shippingCosts;
    }

    /**
     * @return Money
     */
    public function getTotal(): Money
    {
        return $this->total;
    }
}
