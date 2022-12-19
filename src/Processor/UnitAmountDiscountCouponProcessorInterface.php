<?php

declare(strict_types=1);

namespace ITB\CalculationPipeline\Processor;

use ITB\CalculationPipeline\CouponCollection\MixedCouponCollection;
use ITB\CalculationPipeline\CouponCollection\UnitAmountDiscountCouponCollection;
use ITB\CalculationPipeline\ItemCalculation;

interface UnitAmountDiscountCouponProcessorInterface
{
    /**
     * @param ItemCalculation $itemCalculation
     * @param MixedCouponCollection $coupons
     * @return UnitAmountDiscountCouponCollection
     */
    public function process(ItemCalculation $itemCalculation, MixedCouponCollection $coupons): UnitAmountDiscountCouponCollection;
}
