<?php

declare(strict_types=1);

namespace ITB\CalculationPipeline\Processor;

use ITB\CalculationPipeline\CouponCollection\MixedCouponCollection;
use ITB\CalculationPipeline\CouponCollection\ShippingCostsDiscountCouponCollection;

interface ShippingCostsDiscountCouponProcessorInterface
{
    /**
     * @param MixedCouponCollection $coupons
     * @return ShippingCostsDiscountCouponCollection
     */
    public function process(MixedCouponCollection $coupons): ShippingCostsDiscountCouponCollection;
}
