<?php

declare(strict_types=1);

namespace ITB\CalculationPipeline\Processor;

use ITB\CalculationPipeline\Coupon\OtherPaymentCouponInterface;
use ITB\CalculationPipeline\CouponCollection\MixedCouponCollection;
use ITB\CalculationPipeline\CouponCollection\OtherPaymentCouponCollection;

final class OtherPaymentCouponProcessor implements OtherPaymentCouponProcessorInterface
{
    /**
     * @param MixedCouponCollection $coupons
     * @return OtherPaymentCouponCollection
     */
    public function process(MixedCouponCollection $coupons): OtherPaymentCouponCollection
    {
        return OtherPaymentCouponCollection::fromMixedCouponCollectionWithTypeFilter(
            $coupons,
            OtherPaymentCouponInterface::class
        );
    }
}
