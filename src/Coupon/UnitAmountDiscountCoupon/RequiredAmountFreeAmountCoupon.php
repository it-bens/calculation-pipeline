<?php

declare(strict_types=1);

namespace ITB\CalculationPipeline\Coupon\UnitAmountDiscountCoupon;

use ITB\CalculationPipeline\Coupon\Coupon;
use ITB\CalculationPipeline\Coupon\UnitAmountDiscountCoupon\RequiredAmountFreeAmountCoupon\FreeAmountGraterThanRequiredAmountException;
use ITB\CalculationPipeline\Coupon\UnitAmountDiscountCoupon\RequiredAmountFreeAmountCoupon\MaximumApplicationNotPositiveException;
use ITB\CalculationPipeline\Coupon\UnitAmountDiscountCouponInterface;
use ITB\CalculationPipeline\ItemCalculation;
use ITB\CalculationPipeline\Product\Amount;
use ITB\CalculationPipeline\ProductCollection;
use Money\Money;

final class RequiredAmountFreeAmountCoupon extends Coupon implements UnitAmountDiscountCouponInterface
{
    /** @var Amount */
    private $requiredAmount;
    /** @var Amount */
    private $freeAmount;
    /** @var int */
    private $maximumApplicationCount;
    /** @var ProductCollection */
    private $includedProducts;

    /**
     * A required amount - free amount discount coupon is applied to an item.
     * If the product amount if above the required amount, one or more units are free.
     *
     * The coupon can be used to include products (whitelist) or to exclude products (blacklist).
     * Products can be whitelisted by adding them to the passed ProductCollection.
     * Products can be blacklisted by adding all products of the calculation, expect the blacklisted ones, to the passed ProductCollection.
     *
     * @param string $name
     * @param Amount $requiredAmount
     * @param Amount $freeAmount
     * @param int $maximumApplicationCount
     * @param ProductCollection $includedProducts
     * @param Money $minimalNonDiscountedOverallPrice
     */
    public function __construct(
        string $name,
        Amount $requiredAmount,
        Amount $freeAmount,
        int $maximumApplicationCount,
        ProductCollection $includedProducts,
        Money $minimalNonDiscountedOverallPrice
    ) {
        parent::__construct($name, $minimalNonDiscountedOverallPrice);

        $this->includedProducts = $includedProducts;

        if ($freeAmount > $requiredAmount) {
            throw FreeAmountGraterThanRequiredAmountException::create($freeAmount->get(), $requiredAmount->get());
        }
        $this->requiredAmount = $requiredAmount;
        $this->freeAmount = $freeAmount;

        if ($maximumApplicationCount < 0) {
            throw MaximumApplicationNotPositiveException::create($maximumApplicationCount);
        }
        $this->maximumApplicationCount = $maximumApplicationCount;
    }

    /**
     * @param Amount $unitAmount
     * @param Money $nonDiscountedOverallPrice
     * @return Amount
     */
    public function applyInItemCalculation(Amount $unitAmount, Money $nonDiscountedOverallPrice): Amount
    {
        // The checks are repeated because the calling class should clear any problems before starting the calculation with coupons.
        $this->checkApplicabilityInCalculation($nonDiscountedOverallPrice);

        $discountApplicationTimes = (int)floor(
            (float)bcdiv((string)$unitAmount->get(), (string)$this->requiredAmount->get())
        );
        // The count how many times this discount can be applied is restricted to the passed max value.
        $discountApplicationTimes = min($discountApplicationTimes, $this->maximumApplicationCount);

        return new Amount($unitAmount->get() - (int)($discountApplicationTimes) * $this->freeAmount->get());
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
