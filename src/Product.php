<?php

declare(strict_types=1);

namespace ITB\CalculationPipeline;

use ITB\CalculationPipeline\CalculationRequest\TaxRate;
use ITB\CalculationPipeline\Product\Id;
use Money\Money;

final class Product
{
    /** @var Id */
    private $productId;
    /** @var Money */
    private $netUnitPrice;
    /** @var TaxRate */
    private $taxRate;

    /**
     * @param Id $productId
     * @param Money $netUnitPrice
     * @param TaxRate $taxRate
     */
    public function __construct(Id $productId, Money $netUnitPrice, TaxRate $taxRate)
    {
        $this->productId = $productId;
        $this->netUnitPrice = $netUnitPrice;
        $this->taxRate = $taxRate;
    }

    /**
     * @param Product $product
     * @return bool
     */
    public function equals(Product $product): bool
    {
        return $product->getProductId()->get() === $this->productId->get();
    }

    /**
     * @return Id
     */
    public function getProductId(): Id
    {
        return $this->productId;
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
    public function getUnitPrice(): Money
    {
        return $this->netUnitPrice;
    }
}
