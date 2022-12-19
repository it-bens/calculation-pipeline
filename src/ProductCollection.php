<?php

declare(strict_types=1);

namespace ITB\CalculationPipeline;

use ITB\CalculationPipeline\ProductCollectionException\NotAProductException;
use Iterator;

/** @implements Iterator<int, Product> */
final class ProductCollection implements Iterator
{
    /** @var int */
    private $index = 0;

    /** @var Product[] */
    private $products;

    /**
     * @param Product[] $products
     */
    public function __construct(array $products)
    {
        $this->products = [];
        foreach ($products as $index => $product) {
            if (!$product instanceof Product) {
                throw NotAProductException::create($index);
            }
        }
    }

    /**
     * @return Product
     */
    public function current(): Product
    {
        return $this->products[$this->index];
    }

    /**
     * @param Product $product
     * @return bool
     */
    public function isProductInCollection(Product $product): bool
    {
        foreach ($this->products as $productToCompare) {
            if ($productToCompare->equals($product)) {
                return true;
            }
        }

        return false;
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
        return array_key_exists($this->index, $this->products);
    }
}
