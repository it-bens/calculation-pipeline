<?php

declare(strict_types=1);

namespace ITB\CalculationPipeline\CouponException;

use ITB\CalculationPipeline\CouponInterface;
use Money\Money;
use RuntimeException;

final class OverallValueTooLowForCouponException extends RuntimeException
{
    /** @var CouponInterface */
    private $coupon;
    /** @var Money */
    private $minimalOverallValue;
    /** @var Money */
    private $actualOverallValue;

    /**
     * @param CouponInterface $coupon
     * @param Money $minimalOverallPrice
     * @param Money $actualOverallPrice
     */
    public function __construct(CouponInterface $coupon, Money $minimalOverallPrice, Money $actualOverallPrice)
    {
        parent::__construct(
            sprintf(
                'The coupon with the name "%s" could applied because the passed overall value it too low.',
                $coupon->getName()
            )
        );
        $this->coupon = $coupon;
        $this->minimalOverallValue = $minimalOverallPrice;
        $this->actualOverallValue = $actualOverallPrice;
    }

    /**
     * @return Money
     */
    public function getActualOverallValue(): Money
    {
        return $this->actualOverallValue;
    }

    /**
     * @return CouponInterface
     */
    public function getItemDiscountCoupon(): CouponInterface
    {
        return $this->coupon;
    }

    /**
     * @return Money
     */
    public function getMinimalOverallValue(): Money
    {
        return $this->minimalOverallValue;
    }
}
