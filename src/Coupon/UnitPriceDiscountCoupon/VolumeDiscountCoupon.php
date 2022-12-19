<?php

declare(strict_types=1);

namespace ITB\CalculationPipeline\Coupon\UnitPriceDiscountCoupon;

use ITB\CalculationPipeline\Coupon\Coupon;
use ITB\CalculationPipeline\Coupon\UnitPriceDiscountCouponInterface;
use ITB\CalculationPipeline\ItemCalculation;
use ITB\CalculationPipeline\Product\Amount;
use ITB\CalculationPipeline\ProductCollection;
use Money\Money;

final class VolumeDiscountCoupon extends Coupon implements UnitPriceDiscountCouponInterface
{
    /** @var Money */
    private $absoluteUnitDiscount;
    /** @var ProductCollection */
    private $includedProducts;
    /** @var Amount */
    private $minimalAmount;

    /**
     * A volume discount coupon grants an absolute value discount to every unit of a product if a certain amount of this product is added.
     *
     * The coupon can be used to include products (whitelist) or to exclude products (blacklist).
     * Products can be whitelisted by adding them to the passed ProductCollection.
     * Products can be blacklisted by adding all products of the calculation, expect the blacklisted ones, to the passed ProductCollection.
     *
     * @param string $name
     * @param Money $absoluteUnitDiscount
     * @param ProductCollection $includedProducts
     * @param Amount $minimalAmount
     * @param Money $minimalNonDiscountedOverallPrice
     */
    public function __construct(
        string $name,
        Money $absoluteUnitDiscount,
        ProductCollection $includedProducts,
        Amount $minimalAmount,
        Money $minimalNonDiscountedOverallPrice
    ) {
        parent::__construct($name, $minimalNonDiscountedOverallPrice);

        $this->absoluteUnitDiscount = $absoluteUnitDiscount;
        $this->includedProducts = $includedProducts;
        $this->minimalAmount = $minimalAmount;
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

        $discountedUnitPrice = $unitPrice->subtract($this->absoluteUnitDiscount);

        // Returns the discounted unit price or 0 if it's below 0.
        return Money::max($discountedUnitPrice, new Money(0, $unitPrice->getCurrency()));
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

        if ($this->minimalAmount->get() > $itemCalculation->getAmount()->get()) {
            return false;
        }

        return true;
    }
}
