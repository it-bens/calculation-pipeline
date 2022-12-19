<?php

declare(strict_types=1);

namespace ITB\CalculationPipeline\Processor;

use ITB\CalculationPipeline\CouponCollection\MixedCouponCollection;
use ITB\CalculationPipeline\CouponCollection\UnitPriceDiscountCouponCollection;
use ITB\CalculationPipeline\ItemCalculation;

interface UnitPriceDiscountCouponProcessorInterface
{
    /**
     * @param ItemCalculation $itemCalculation
     * @param MixedCouponCollection $coupons
     * @return UnitPriceDiscountCouponCollection
     */
    public function process(ItemCalculation $itemCalculation, MixedCouponCollection $coupons): UnitPriceDiscountCouponCollection;
}
