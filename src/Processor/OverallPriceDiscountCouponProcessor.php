<?php

declare(strict_types=1);

namespace ITB\CalculationPipeline\Processor;

use ITB\CalculationPipeline\Coupon\OverallPriceDiscountCouponInterface;
use ITB\CalculationPipeline\CouponCollection\MixedCouponCollection;
use ITB\CalculationPipeline\CouponCollection\OverallPriceDiscountCouponCollection;

final class OverallPriceDiscountCouponProcessor implements OverallPriceDiscountCouponProcessorInterface
{
    /**
     * @param MixedCouponCollection $coupons
     * @return OverallPriceDiscountCouponCollection
     */
    public function process(MixedCouponCollection $coupons): OverallPriceDiscountCouponCollection
    {
        return OverallPriceDiscountCouponCollection::fromMixedCouponCollectionWithTypeFilter(
            $coupons,
            OverallPriceDiscountCouponInterface::class
        );
    }
}
