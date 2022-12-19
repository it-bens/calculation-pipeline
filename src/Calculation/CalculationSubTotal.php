<?php

declare(strict_types=1);

namespace ITB\CalculationPipeline\Calculation;

use ITB\CalculationPipeline\ItemCalculation\ItemCalculationSubTotalCollection;
use Money\Money;

final class CalculationSubTotal
{
    /** @var ItemCalculationSubTotalCollection */
    private $itemCalculationSubTotals;
    /** @var Money */
    private $subTotal;
    /** @var Money */
    private $discountedSubTotal;

    /**
     * @param ItemCalculationSubTotalCollection $itemCalculationSubTotals
     * @param Money $subTotal
     * @param Money $discountedSubTotal
     */
    public function __construct(
        ItemCalculationSubTotalCollection $itemCalculationSubTotals,
        Money $subTotal,
        Money $discountedSubTotal
    ) {
        $this->itemCalculationSubTotals = $itemCalculationSubTotals;
        $this->subTotal = $subTotal;
        $this->discountedSubTotal = $discountedSubTotal;
    }

    /**
     * @return Money
     */
    public function getDiscountedSubTotal(): Money
    {
        return $this->discountedSubTotal;
    }

    /**
     * @return ItemCalculationSubTotalCollection
     */
    public function getItemCalculationSubTotals(): ItemCalculationSubTotalCollection
    {
        return $this->itemCalculationSubTotals;
    }

    /**
     * @return Money
     */
    public function getSubTotal(): Money
    {
        return $this->subTotal;
    }
}
