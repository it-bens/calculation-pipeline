<?php

declare(strict_types=1);

namespace ITB\CalculationPipeline\CouponCollection;

use ITB\CalculationPipeline\CouponCollection;
use ITB\CalculationPipeline\CouponCollectionException\NotACouponException;
use ITB\CalculationPipeline\CouponInterface;

/**
 * @extends CouponCollection<CouponInterface>
 *
 * @method static MixedCouponCollection fromMixedCouponCollectionWithTypeFilter(CouponCollection $couponCollection, string $couponType)
 * @method MixedCouponCollection current()
 * @method MixedCouponCollection filter(callable $filterCallback)
 * @method MixedCouponCollection unique()
 * @method MixedCouponCollection removeCoupons(CouponCollection $couponsToRemove)
 */
final class MixedCouponCollection extends CouponCollection
{
    /**
     * @param CouponInterface[] $coupons
     */
    public function __construct(array $coupons)
    {
        array_walk($coupons, static function ($coupon, $index) {
            if (!$coupon instanceof CouponInterface) {
                throw NotACouponException::create($index);
            }
        });

        parent::__construct($coupons);
    }
}
