<?php

declare(strict_types=1);

namespace ITB\CalculationPipeline\CalculationRequest;

final class TaxRate
{
    /** @var float */
    private $taxRate;
    /** @var float */
    private $taxFactor;

    /**
     * @param float $taxRate
     */
    public function __construct(float $taxRate)
    {
        // Tax rates can be above 100%.
        if ($taxRate < 0) {
            // TODO: throw exception
        }
        $this->taxRate = $taxRate;

        $this->taxFactor = 1 + (float)bcdiv((string)$this->taxRate, '100');
    }

    /**
     * @return float
     */
    public function get(): float
    {
        return $this->taxRate;
    }

    /**
     * @return float
     */
    public function getTaxFactor(): float
    {
        return $this->taxFactor;
    }
}
