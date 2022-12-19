<?php

declare(strict_types=1);

namespace ITB\CalculationPipeline\Product\IdException;

use ITB\CalculationPipeline\Exception\RequestExceptionInterface;
use RuntimeException;

final class IdBlankException extends RuntimeException implements RequestExceptionInterface
{
    /**
     * @return IdBlankException
     */
    public static function create(): IdBlankException
    {
        return new self('The product id must not be blank.');
    }
}
