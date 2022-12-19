<?php

declare(strict_types=1);

namespace ITB\CalculationPipeline\CouponCollection;

use ITB\CalculationPipeline\Coupon\ShippingCostsDiscountCouponInterface;
use ITB\CalculationPipeline\CouponCollection;
use ITB\CalculationPipeline\CouponCollectionException\NotAShippingCostsDiscountCouponException;
use ITB\CalculationPipeline\CouponInterface;
use Money\Money;

/**
 * @extends CouponCollection<ShippingCostsDiscountCouponInterface>
 *
 * @method static ShippingCostsDiscountCouponCollection fromMixedCouponCollectionWithTypeFilter(CouponCollection $couponCollection, string $couponType)
 * @method ShippingCostsDiscountCouponInterface current()
 * @method ShippingCostsDiscountCouponCollection filter(callable $filterCallback)
 * @method ShippingCostsDiscountCouponCollection unique()
 * @method ShippingCostsDiscountCouponCollection removeCoupons(CouponCollection $couponsToRemove)
 */
final class ShippingCostsDiscountCouponCollection extends CouponCollection
{
    /**
     * @param CouponInterface[] $coupons
     */
    public function __construct(array $coupons)
    {
        array_walk($coupons, static function ($coupon, $index): void {
            if (!$coupon instanceof ShippingCostsDiscountCouponInterface) {
                throw NotAShippingCostsDiscountCouponException::create($index);
            }
        });

        parent::__construct($coupons);
    }

    /**
     * @param Money $shippingCosts
     * @param Money $nonDiscountedOverallPrice
     * @return Money
     */
    public function applyAllCouponsInCalculation(Money $shippingCosts, Money $nonDiscountedOverallPrice): Money
    {
        foreach ($this as $shippingCostsDiscountCoupon) {
            $shippingCosts = $shippingCostsDiscountCoupon->applyInShippingCostsCalculation(
                $shippingCosts,
                $nonDiscountedOverallPrice
            );
        }

        return $shippingCosts;
    }
}
