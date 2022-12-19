<?php

declare(strict_types=1);

namespace ITB\CalculationPipeline\ItemCalculation;

use ITB\CalculationPipeline\Calculator\ItemTotalCalculatorInterface;
use Money\Currency;
use Money\Money;

final class ItemCalculationTotalCollection
{
    /** @var ItemCalculationTotal[] */
    private $itemCalculationTotals;

    /** @var Money */
    private $totalSum;
    /** @var Money */
    private $discountedTotalSum;

    /** @var Currency */
    private $currency;

    /**
     * @param ItemCalculationTotal[] $itemCalculationTotals
     */
    public function __construct(array $itemCalculationTotals, Currency $currency)
    {
        // The array-key reset with array_values() is not required because a new array with numeric keys is created.
        $this->itemCalculationTotals = [];
        foreach ($itemCalculationTotals as $itemCalculationTotal) {
            if (!$itemCalculationTotal instanceof ItemCalculationTotal) {
                // TODO: throw exception
            }

            $this->itemCalculationTotals[] = $itemCalculationTotal;
        }

        $this->totalSum = $this->calculateTotalSum();
        $this->discountedTotalSum = $this->calculateDiscountedTotalSum();

        $this->currency = $currency;
    }

    /**
     * @param ItemCalculationSubTotalCollection $itemCalculationSubTotals
     * @param ItemTotalCalculatorInterface $itemTotalCalculator
     * @return ItemCalculationTotalCollection
     */
    public static function fromItemCalculationSubtotals(
        ItemCalculationSubTotalCollection $itemCalculationSubTotals,
        ItemTotalCalculatorInterface $itemTotalCalculator
    ): ItemCalculationTotalCollection {
        $itemCalculationTotals = [];
        foreach ($itemCalculationSubTotals as $itemCalculationSubTotal) {
            $itemCalculation = $itemCalculationSubTotal->getItemCalculation();
            $itemCalculationSubTotals[] = $itemCalculation->calculateTotal(
                $itemCalculationSubTotal,
                $itemTotalCalculator
            );
        }

        return new self($itemCalculationTotals, $itemCalculationSubTotals->getCurrency());
    }

    /**
     * @return Currency
     */
    public function getCurrency(): Currency
    {
        return $this->currency;
    }

    /**
     * @return Money
     */
    public function getDiscountedTotalSum(): Money
    {
        return $this->discountedTotalSum;
    }

    /**
     * @return Money
     */
    public function getTotalSum(): Money
    {
        return $this->totalSum;
    }

    /**
     * @return Money
     */
    private function calculateDiscountedTotalSum(): Money
    {
        $discountedTotalSum = new Money(0, $this->currency);
        foreach ($this->itemCalculationTotals as $itemCalculationTotal) {
            $discountedTotalSum = $discountedTotalSum->add($itemCalculationTotal->getDiscountedTotal());
        }

        return $discountedTotalSum;
    }

    /**
     * @return Money
     */
    private function calculateTotalSum(): Money
    {
        $totalSum = new Money(0, $this->currency);
        foreach ($this->itemCalculationTotals as $itemCalculationTotal) {
            $totalSum = $totalSum->add($itemCalculationTotal->getTotal());
        }

        return $totalSum;
    }
}
