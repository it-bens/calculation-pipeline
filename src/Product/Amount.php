<?php

declare(strict_types=1);

namespace ITB\CalculationPipeline\Product;

use ITB\CalculationPipeline\Product\AmountException\AmountTooLowException;

final class Amount
{
    /** @var int */
    private $amount;

    /**
     * @param int $amount
     */
    public function __construct(int $amount)
    {
        if ($amount <= 0) {
            throw AmountTooLowException::create($amount);
        }

        $this->amount = $amount;
    }

    /**
     * @return int
     */
    public function get(): int
    {
        return $this->amount;
    }
}
