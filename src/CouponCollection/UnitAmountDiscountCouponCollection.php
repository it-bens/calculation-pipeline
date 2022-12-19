<?php

declare(strict_types=1);

namespace ITB\CalculationPipeline\CouponCollection;

use ITB\CalculationPipeline\Coupon\UnitAmountDiscountCouponInterface;
use ITB\CalculationPipeline\CouponCollection;
use ITB\CalculationPipeline\CouponCollectionException\NotAUnitAmountDiscountCouponException;
use ITB\CalculationPipeline\CouponInterface;
use ITB\CalculationPipeline\Product\Amount;
use Money\Money;

/**
 * @extends CouponCollection<UnitAmountDiscountCouponInterface>
 *
 * @method static UnitAmountDiscountCouponCollection fromMixedCouponCollectionWithTypeFilter(CouponCollection $couponCollection, string $couponType)
 * @method UnitAmountDiscountCouponInterface current()
 * @method UnitAmountDiscountCouponCollection filter(callable $filterCallback)
 * @method UnitAmountDiscountCouponCollection unique()
 * @method UnitAmountDiscountCouponCollection removeCoupons(CouponCollection $couponsToRemove)
 */
final class UnitAmountDiscountCouponCollection extends CouponCollection
{
    /**
     * @param CouponInterface[] $coupons
     */
    public function __construct(array $coupons)
    {
        array_walk($coupons, static function ($coupon, $index): void {
            if (!$coupon instanceof UnitAmountDiscountCouponInterface) {
                throw NotAUnitAmountDiscountCouponException::create($index);
            }
        });

        parent::__construct($coupons);
    }

    /**
     * @param Amount $amount
     * @param Money $nonDiscountedOverallPrice
     * @return Amount
     */
    public function applyAllCouponsInItemCalculation(Amount $amount, Money $nonDiscountedOverallPrice): Amount
    {
        foreach ($this as $unitAmountDiscountCoupon) {
            $amount = $unitAmountDiscountCoupon->applyInItemCalculation($amount, $nonDiscountedOverallPrice);
        }

        return $amount;
    }
}
