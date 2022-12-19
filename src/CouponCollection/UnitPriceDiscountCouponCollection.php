<?php

declare(strict_types=1);

namespace ITB\CalculationPipeline\CouponCollection;

use ITB\CalculationPipeline\Coupon\UnitPriceDiscountCouponInterface;
use ITB\CalculationPipeline\CouponCollection;
use ITB\CalculationPipeline\CouponCollectionException\NotAUnitPriceDiscountCouponException;
use ITB\CalculationPipeline\CouponInterface;
use Money\Money;

/**
 * @extends CouponCollection<UnitPriceDiscountCouponInterface>
 *
 * @method static UnitPriceDiscountCouponCollection fromMixedCouponCollectionWithTypeFilter(CouponCollection $couponCollection, string $couponType)
 * @method UnitPriceDiscountCouponInterface current()
 * @method UnitPriceDiscountCouponCollection filter(callable $filterCallback)
 * @method UnitPriceDiscountCouponCollection unique()
 * @method UnitPriceDiscountCouponCollection removeCoupons(CouponCollection $couponsToRemove)
 */
final class UnitPriceDiscountCouponCollection extends CouponCollection
{
    /**
     * @param CouponInterface[] $coupons
     */
    public function __construct(array $coupons)
    {
        array_walk($coupons, static function ($coupon, $index): void {
            if (!$coupon instanceof UnitPriceDiscountCouponInterface) {
                throw NotAUnitPriceDiscountCouponException::create($index);
            }
        });

        parent::__construct($coupons);
    }

    /**
     * @param Money $unitPrice
     * @param Money $nonDiscountedOverallPrice
     * @return Money
     */
    public function applyAllCouponsInItemCalculation(Money $unitPrice, Money $nonDiscountedOverallPrice): Money
    {
        foreach ($this as $unitPriceDiscountCoupon) {
            $unitPrice = $unitPriceDiscountCoupon->applyInItemCalculation($unitPrice, $nonDiscountedOverallPrice);
        }

        return $unitPrice;
    }
}
