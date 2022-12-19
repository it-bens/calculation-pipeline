<?php

declare(strict_types=1);

namespace ITB\CalculationPipeline\Coupon;

use ITB\CalculationPipeline\CouponException\OverallValueTooLowForCouponException;
use ITB\CalculationPipeline\CouponInterface;
use Money\Money;

abstract class Coupon implements CouponInterface
{
    /** @var string */
    protected $name;
    /** @var Money */
    protected $minimalNonDiscountedOverallPrice;

    /**
     * @param string $name
     * @param Money $minimalNonDiscountedOverallPrice
     */
    public function __construct(string $name, Money $minimalNonDiscountedOverallPrice)
    {
        $this->name = $name;
        $this->minimalNonDiscountedOverallPrice = $minimalNonDiscountedOverallPrice;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->name;
    }

    /**
     * @param Money $overallPrice
     * @return void
     *
     * @throws OverallValueTooLowForCouponException
     */
    public function checkApplicabilityInCalculation(Money $overallPrice): void
    {
        if ($this->minimalNonDiscountedOverallPrice > $overallPrice) {
            throw new OverallValueTooLowForCouponException($this, $this->minimalNonDiscountedOverallPrice, $overallPrice);
        }
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }
}
