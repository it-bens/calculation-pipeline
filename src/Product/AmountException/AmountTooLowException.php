<?php

declare(strict_types=1);

namespace ITB\CalculationPipeline\Product\AmountException;

use ITB\CalculationPipeline\Exception\RequestExceptionInterface;
use RuntimeException;

final class AmountTooLowException extends RuntimeException implements RequestExceptionInterface
{
    /**
     * @param int $amount
     * @return AmountTooLowException
     */
    public static function create(int $amount): AmountTooLowException
    {
        return new self(sprintf('The product amount must be at least 1. %d was passed.', $amount));
    }
}
