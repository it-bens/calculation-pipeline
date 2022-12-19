<?php

declare(strict_types=1);

namespace ITB\CalculationPipeline\CouponCollection;

use ITB\CalculationPipeline\Coupon\OtherPaymentCouponInterface;
use ITB\CalculationPipeline\CouponCollection;
use ITB\CalculationPipeline\CouponCollectionException\NotAnOtherPaymentCouponException;
use ITB\CalculationPipeline\CouponInterface;
use Money\Money;

/**
 * @extends CouponCollection<OtherPaymentCouponInterface>
 *
 * @method static OtherPaymentCouponCollection fromMixedCouponCollectionWithTypeFilter(CouponCollection $couponCollection, string $couponType)
 * @method OtherPaymentCouponInterface current()
 * @method OtherPaymentCouponCollection filter(callable $filterCallback)
 * @method OtherPaymentCouponCollection unique()
 * @method OtherPaymentCouponCollection removeCoupons(CouponCollection $couponsToRemove)
 */
final class OtherPaymentCouponCollection extends CouponCollection
{
    /**
     * @param CouponInterface[] $coupons
     */
    public function __construct(array $coupons)
    {
        array_walk($coupons, static function ($coupon, $index): void {
            if (!$coupon instanceof OtherPaymentCouponInterface) {
                throw NotAnOtherPaymentCouponException::create($index);
            }
        });

        parent::__construct($coupons);
    }

    /**
     * @param Money $grandTotal
     * @param Money $nonDiscountedOverallPrice
     * @return Money
     */
    public function applyAllCouponsInCalculation(Money $grandTotal, Money $nonDiscountedOverallPrice): Money
    {
        foreach ($this as $otherPaymentCoupon) {
            $grandTotal = $otherPaymentCoupon->applyInCalculation($grandTotal, $nonDiscountedOverallPrice);
        }

        return $grandTotal;
    }
}
