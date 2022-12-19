<?php

declare(strict_types=1);

namespace ITB\CalculationPipeline\ItemCalculation;

use ITB\CalculationPipeline\CalculationRequest\TaxRate;
use Money\Money;

final class ItemCalculationTotal
{
    /** @var ItemCalculationSubTotal */
    private $itemCalculationSubTotal;

    /** @var TaxRate */
    private $taxRate;
    /** @var Money */
    private $taxesOnPrice;
    /** @var Money */
    private $taxesOnDiscountedPrice;

    /** @var Money */
    private $total;
    /** @var Money */
    private $discountedTotal;

    /**
     * @param ItemCalculationSubTotal $itemCalculationSubTotal
     * @param TaxRate $taxRate
     * @param Money $taxesOnPrice
     * @param Money $taxesOnDiscountedPrice
     * @param Money $total
     * @param Money $discountedTotal
     */
    public function __construct(
        ItemCalculationSubTotal $itemCalculationSubTotal,
        TaxRate $taxRate,
        Money $taxesOnPrice,
        Money $taxesOnDiscountedPrice,
        Money $total,
        Money $discountedTotal
    ) {
        $this->itemCalculationSubTotal = $itemCalculationSubTotal;
        $this->taxRate = $taxRate;
        $this->taxesOnPrice = $taxesOnPrice;
        $this->taxesOnDiscountedPrice = $taxesOnDiscountedPrice;
        $this->total = $total;
        $this->discountedTotal = $discountedTotal;
    }

    /**
     * @return Money
     */
    public function getDiscountedTotal(): Money
    {
        return $this->discountedTotal;
    }

    /**
     * @return ItemCalculationSubTotal
     */
    public function getItemCalculationSubTotal(): ItemCalculationSubTotal
    {
        return $this->itemCalculationSubTotal;
    }

    /**
     * @return TaxRate
     */
    public function getTaxRate(): TaxRate
    {
        return $this->taxRate;
    }

    /**
     * @return Money
     */
    public function getTaxesOnDiscountedPrice(): Money
    {
        return $this->taxesOnDiscountedPrice;
    }

    /**
     * @return Money
     */
    public function getTaxesOnPrice(): Money
    {
        return $this->taxesOnPrice;
    }

    /**
     * @return Money
     */
    public function getTotal(): Money
    {
        return $this->total;
    }
}
