<?php

declare(strict_types=1);

namespace ITB\CalculationPipeline\CouponCollection;

use ITB\CalculationPipeline\Coupon\OverallPriceDiscountCouponInterface;
use ITB\CalculationPipeline\CouponCollection;
use ITB\CalculationPipeline\CouponCollectionException\NotAnOverallPriceDiscountCouponException;
use ITB\CalculationPipeline\CouponInterface;
use Money\Money;

/**
 * @extends CouponCollection<OverallPriceDiscountCouponInterface>
 *
 * @method static OverallPriceDiscountCouponCollection fromMixedCouponCollectionWithTypeFilter(CouponCollection $couponCollection, string $couponType)
 * @method OverallPriceDiscountCouponInterface current()
 * @method OverallPriceDiscountCouponCollection filter(callable $filterCallback)
 * @method OverallPriceDiscountCouponCollection unique()
 * @method OverallPriceDiscountCouponCollection removeCoupons(CouponCollection $couponsToRemove)
 */
final class OverallPriceDiscountCouponCollection extends CouponCollection
{
    /**
     * @param CouponInterface[] $coupons
     */
    public function __construct(array $coupons)
    {
        array_walk($coupons, static function ($coupon, $index): void {
            if (!$coupon instanceof OverallPriceDiscountCouponInterface) {
                throw NotAnOverallPriceDiscountCouponException::create($index);
            }
        });

        parent::__construct($coupons);
    }

    /**
     * @param Money $total
     * @param Money $nonDiscountedOverallPrice
     * @return Money
     */
    public function applyAllCouponsInCalculation(Money $total, Money $nonDiscountedOverallPrice): Money
    {
        foreach ($this as $overallPriceDiscountCoupon) {
            $total = $overallPriceDiscountCoupon->applyInCalculation($total, $nonDiscountedOverallPrice);
        }

        return $total;
    }
}
