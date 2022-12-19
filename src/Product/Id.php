<?php

declare(strict_types=1);

namespace ITB\CalculationPipeline\Product;

use ITB\CalculationPipeline\Product\IdException\IdBlankException;

final class Id
{
    /** @var non-empty-string */
    private $productId;

    public function __construct(string $productId)
    {
        if ('' === $productId) {
            throw IdBlankException::create();
        }

        $this->productId = $productId;
    }

    /**
     * @return non-empty-string
     */
    public function get(): string
    {
        return $this->productId;
    }
}
