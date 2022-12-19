<?php

declare(strict_types=1);

namespace ITB\CalculationPipeline\ItemCalculation;

use ITB\CalculationPipeline\Calculator\ItemTotalCalculatorInterface;
use ITB\CalculationPipeline\ItemCalculation;
use Iterator;
use Money\Currency;
use Money\Money;

/**
 * @implements Iterator<ItemCalculationSubTotal>
 */
final class ItemCalculationSubTotalCollection implements Iterator
{
    /** @var int */
    private $index = 0;

    /** @var ItemCalculationSubTotal[] */
    private $itemCalculationSubTotals;
    /** @var Currency */
    private $currency;

    /** @var Money */
    private $subTotalSum;
    /** @var Money */
    private $discountedSubTotalSum;

    /**
     * @param ItemCalculationSubTotal[] $itemCalculationSubTotals
     */
    public function __construct(array $itemCalculationSubTotals, Currency $currency)
    {
        $this->itemCalculationSubTotals = [];
        foreach ($itemCalculationSubTotals as $itemCalculationSubTotal) {
            if (!$itemCalculationSubTotal instanceof ItemCalculationSubTotal) {
                // TODO: throw exception
            }

            $this->itemCalculationSubTotals[] = $itemCalculationSubTotal;
        }

        $this->subTotalSum = $this->calculateSubTotalSum();
        $this->discountedSubTotalSum = $this->calculateDiscountedSubTotalSum();

        $this->currency = $currency;
    }

    /**
     * @param ItemCalculation[] $itemCalculations
     * @param Money $nonDiscountedOverallPrice
     * @param ItemTotalCalculatorInterface $itemTotalCalculator
     * @return ItemCalculationSubTotalCollection
     */
    public static function fromItemCalculation(
        array $itemCalculations,
        Money $nonDiscountedOverallPrice,
        ItemTotalCalculatorInterface $itemTotalCalculator
    ): ItemCalculationSubTotalCollection {
        $itemCalculationSubTotals = [];
        foreach ($itemCalculations as $itemCalculation) {
            if (!$itemCalculation instanceof ItemCalculation) {
                // TODO: throw exception
            }

            $itemCalculationSubTotals[] = $itemCalculation->calculateSubTotal(
                $itemTotalCalculator,
                $nonDiscountedOverallPrice
            );
        }

        return new self($itemCalculationSubTotals, $nonDiscountedOverallPrice->getCurrency());
    }

    /**
     * @return ItemCalculationSubTotal
     */
    public function current(): ItemCalculationSubTotal
    {
        return $this->itemCalculationSubTotals[$this->index];
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
    public function getDiscountedSubTotalSum(): Money
    {
        return $this->discountedSubTotalSum;
    }

    /**
     * @return Money
     */
    public function getSubTotalSum(): Money
    {
        return $this->subTotalSum;
    }

    /**
     * @return int
     */
    public function key(): int
    {
        return $this->index;
    }

    /**
     * @return void
     */
    public function next()
    {
        $this->index++;
    }

    /**
     * @return void
     */
    public function rewind()
    {
        $this->index = 0;
    }

    /**
     * @return bool
     */
    public function valid(): bool
    {
        return array_key_exists($this->index, $this->itemCalculationSubTotals);
    }

    /**
     * @return Money
     */
    private function calculateDiscountedSubTotalSum(): Money
    {
        $discountedSubTotalSum = new Money(0, $this->currency);
        foreach ($this->itemCalculationSubTotals as $itemCalculationSubtotal) {
            $discountedSubTotalSum = $discountedSubTotalSum->add($itemCalculationSubtotal->getDiscountedSubTotal());
        }

        return $discountedSubTotalSum;
    }

    /**
     * @return Money
     */
    private function calculateSubTotalSum(): Money
    {
        $subTotalSum = new Money(0, $this->currency);
        foreach ($this->itemCalculationSubTotals as $itemCalculationSubtotal) {
            $subTotalSum = $subTotalSum->add($itemCalculationSubtotal->getSubTotal());
        }

        return $subTotalSum;
    }
}
