<?php

declare(strict_types=1);

namespace ITB\CalculationPipeline\Coupon\UnitPriceDiscountCoupon;

use ITB\CalculationPipeline\Coupon\Coupon;
use ITB\CalculationPipeline\Coupon\UnitPriceDiscountCouponInterface;
use ITB\CalculationPipeline\Discount\PercentageDiscount;
use ITB\CalculationPipeline\ItemCalculation;
use ITB\CalculationPipeline\ProductCollection;
use Money\Money;

final class PercentageDiscountCoupon extends Coupon implements UnitPriceDiscountCouponInterface
{
    /** @var PercentageDiscount */
    private $percentageUnitDiscount;
    /** @var ProductCollection */
    private $includedProducts;

    /**
     * A percentage discount coupon is applied to every unit of a product.
     *
     * The coupon can be used to include products (whitelist) or to exclude products (blacklist).
     * Products can be whitelisted by adding them to the passed ProductCollection.
     * Products can be blacklisted by adding all products of the calculation, expect the blacklisted ones, to the passed ProductCollection.
     *
     * @param string $name
     * @param PercentageDiscount $percentageUnitDiscount
     * @param ProductCollection $includedProducts
     * @param Money $minimalNonDiscountedOverallPrice
     */
    public function __construct(
        string $name,
        PercentageDiscount $percentageUnitDiscount,
        ProductCollection $includedProducts,
        Money $minimalNonDiscountedOverallPrice
    ) {
        parent::__construct($name, $minimalNonDiscountedOverallPrice);

        $this->percentageUnitDiscount = $percentageUnitDiscount;
        $this->includedProducts = $includedProducts;
    }

    /**
     * @param Money $unitPrice
     * @param Money $nonDiscountedOverallPrice
     * @return Money
     */
    public function applyInItemCalculation(Money $unitPrice, Money $nonDiscountedOverallPrice): Money
    {
        // The checks are repeated because the calling class should clear any problems before starting the calculation with coupons.
        $this->checkApplicabilityInCalculation($nonDiscountedOverallPrice);

        return $unitPrice->multiply($this->percentageUnitDiscount->getDiscountFactor());
    }

    /**
     * @param ItemCalculation $itemCalculation
     * @return bool
     */
    public function checkApplicabilityForItemCalculation(ItemCalculation $itemCalculation): bool
    {
        if (!$this->includedProducts->isProductInCollection($itemCalculation->getProduct())) {
            return false;
        }

        return true;
    }
}
