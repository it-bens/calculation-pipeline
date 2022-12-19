<?php

declare(strict_types=1);

namespace ITB\CalculationPipeline\Discount;

final class PercentageDiscount
{
    /** @var float */
    private $percentage;

    /** @var float */
    private $discountFactor;

    /**
     * @param float $percentage
     */
    public function __construct(float $percentage)
    {
        $this->percentage = $percentage;
        $this->discountFactor = (float)bcdiv((string)(100 - $this->percentage), '100');
    }

    /**
     * @return float
     */
    public function get(): float
    {
        return $this->percentage;
    }

    /**
     * @return float
     */
    public function getDiscountFactor(): float
    {
        return $this->discountFactor;
    }
}
