<?php

declare(strict_types=1);

namespace ITB\CalculationPipeline\Processor;

use ITB\CalculationPipeline\CouponCollection\MixedCouponCollection;
use ITB\CalculationPipeline\CouponCollection\OtherPaymentCouponCollection;

interface OtherPaymentCouponProcessorInterface
{
    /**
     * @param MixedCouponCollection $coupons
     * @return OtherPaymentCouponCollection
     */
    public function process(MixedCouponCollection $coupons): OtherPaymentCouponCollection;
}
