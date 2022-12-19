<?php

declare(strict_types=1);

namespace ITB\CalculationPipeline\Processor;

use ITB\CalculationPipeline\Coupon\UnitAmountDiscountCouponInterface;
use ITB\CalculationPipeline\CouponCollection\MixedCouponCollection;
use ITB\CalculationPipeline\CouponCollection\UnitAmountDiscountCouponCollection;
use ITB\CalculationPipeline\ItemCalculation;

final class UnitAmountDiscountCouponProcessor implements UnitAmountDiscountCouponProcessorInterface
{
    /**
     * @param ItemCalculation $itemCalculation
     * @param MixedCouponCollection $coupons
     * @return UnitAmountDiscountCouponCollection
     */
    public function process(
        ItemCalculation $itemCalculation,
        MixedCouponCollection $coupons
    ): UnitAmountDiscountCouponCollection {
        $typedCollection = UnitAmountDiscountCouponCollection::fromMixedCouponCollectionWithTypeFilter(
            $coupons,
            UnitAmountDiscountCouponInterface::class
        );

        return $typedCollection->filter(
            static function (UnitAmountDiscountCouponInterface $coupon) use ($itemCalculation): bool {
                return $coupon->checkApplicabilityForItemCalculation($itemCalculation);
            }
        );
    }
}
