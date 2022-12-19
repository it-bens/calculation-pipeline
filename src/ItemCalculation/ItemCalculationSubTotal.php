<?php

declare(strict_types=1);

namespace ITB\CalculationPipeline\ItemCalculation;

use ITB\CalculationPipeline\ItemCalculation;
use ITB\CalculationPipeline\Product\Amount;
use Money\Money;

final class ItemCalculationSubTotal
{
    /** @var ItemCalculation */
    private $itemCalculation;

    /** @var Money */
    private $unitPrice;
    /** @var Amount */
    private $amount;
    /** @var Money */
    private $subTotal;

    /** @var Money */
    private $discountedUnitPrice;
    /** @var Amount */
    private $reducedAmount;
    /** @var Money */
    private $discountedSubTotal;

    /**
     * @param ItemCalculation $itemCalculation
     * @param Money $unitPrice
     * @param Amount $amount
     * @param Money $discountedUnitPrice
     * @param Amount $reducedAmount
     * @param Money $subTotal
     * @param Money $discountedSubTotal
     */
    public function __construct(
        ItemCalculation $itemCalculation,
        Money $unitPrice,
        Amount $amount,
        Money $discountedUnitPrice,
        Amount $reducedAmount,
        Money $subTotal,
        Money $discountedSubTotal
    ) {
        $this->itemCalculation = $itemCalculation;
        $this->unitPrice = $unitPrice;
        $this->amount = $amount;
        $this->discountedUnitPrice = $discountedUnitPrice;
        $this->reducedAmount = $reducedAmount;
        $this->subTotal = $subTotal;
        $this->discountedSubTotal = $discountedSubTotal;
    }

    /**
     * @return Amount
     */
    public function getAmount(): Amount
    {
        return $this->amount;
    }

    /**
     * @return Money
     */
    public function getDiscountedSubTotal(): Money
    {
        return $this->discountedSubTotal;
    }

    /**
     * @return Money
     */
    public function getDiscountedUnitPrice(): Money
    {
        return $this->discountedUnitPrice;
    }

    /**
     * @return ItemCalculation
     */
    public function getItemCalculation(): ItemCalculation
    {
        return $this->itemCalculation;
    }

    /**
     * @return Amount
     */
    public function getReducedAmount(): Amount
    {
        return $this->reducedAmount;
    }

    /**
     * @return Money
     */
    public function getSubTotal(): Money
    {
        return $this->subTotal;
    }

    /**
     * @return Money
     */
    public function getUnitPrice(): Money
    {
        return $this->unitPrice;
    }
}
