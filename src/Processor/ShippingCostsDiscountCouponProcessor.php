<?php

declare(strict_types=1);

namespace ITB\CalculationPipeline\Processor;

use ITB\CalculationPipeline\Coupon\ShippingCostsDiscountCouponInterface;
use ITB\CalculationPipeline\CouponCollection\MixedCouponCollection;
use ITB\CalculationPipeline\CouponCollection\ShippingCostsDiscountCouponCollection;

final class ShippingCostsDiscountCouponProcessor implements ShippingCostsDiscountCouponProcessorInterface
{
    /**
     * @param MixedCouponCollection $coupons
     * @return ShippingCostsDiscountCouponCollection
     */
    public function process(MixedCouponCollection $coupons): ShippingCostsDiscountCouponCollection
    {
        return ShippingCostsDiscountCouponCollection::fromMixedCouponCollectionWithTypeFilter(
            $coupons,
            ShippingCostsDiscountCouponInterface::class
        );
    }
}
