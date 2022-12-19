<?php

declare(strict_types=1);

namespace ITB\CalculationPipeline\CalculationRequest;

use ITB\CalculationPipeline\Product;
use ITB\CalculationPipeline\Product\Amount;

final class Item
{
    /** @var Product */
    private $product;
    /** @var Amount */
    private $amount;

    /**
     * @param Product $product
     * @param Amount $amount
     */
    public function __construct(Product $product, Amount $amount)
    {
        $this->product = $product;
        $this->amount = $amount;
    }

    /**
     * @return Amount
     */
    public function getAmount(): Amount
    {
        return $this->amount;
    }

    /**
     * @return Product
     */
    public function getProduct(): Product
    {
        return $this->product;
    }
}
