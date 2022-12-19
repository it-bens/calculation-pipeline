<?php

declare(strict_types=1);

namespace ITB\CalculationPipeline\Processor;

use ITB\CalculationPipeline\CouponCollection\MixedCouponCollection;
use ITB\CalculationPipeline\CouponCollection\OverallPriceDiscountCouponCollection;

interface OverallPriceDiscountCouponProcessorInterface
{
    /**
     * @param MixedCouponCollection $coupons
     * @return OverallPriceDiscountCouponCollection
     */
    public function process(MixedCouponCollection $coupons): OverallPriceDiscountCouponCollection;
}
