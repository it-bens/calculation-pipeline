<?php

declare(strict_types=1);

namespace ITB\CalculationPipeline\Processor;

use ITB\CalculationPipeline\Coupon\UnitPriceDiscountCouponInterface;
use ITB\CalculationPipeline\CouponCollection\MixedCouponCollection;
use ITB\CalculationPipeline\CouponCollection\UnitPriceDiscountCouponCollection;
use ITB\CalculationPipeline\ItemCalculation;

final class UnitPriceDiscountCouponProcessor implements UnitPriceDiscountCouponProcessorInterface
{
    /**
     * @param ItemCalculation $itemCalculation
     * @param MixedCouponCollection $coupons
     * @return UnitPriceDiscountCouponCollection
     */
    public function process(
        ItemCalculation $itemCalculation,
        MixedCouponCollection $coupons
    ): UnitPriceDiscountCouponCollection {
        $typedCollection = UnitPriceDiscountCouponCollection::fromMixedCouponCollectionWithTypeFilter(
            $coupons,
            UnitPriceDiscountCouponInterface::class
        );

        return $typedCollection->filter(
            static function (UnitPriceDiscountCouponInterface $coupon) use ($itemCalculation): bool {
                return $coupon->checkApplicabilityForItemCalculation($itemCalculation);
            }
        );
    }
}
