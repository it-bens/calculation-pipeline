<?php

declare(strict_types=1);

namespace ITB\CalculationPipeline;

use ITB\CalculationPipeline\CalculationRequest\Item as CalculationRequestItem;
use ITB\CalculationPipeline\Calculator\ItemTotalCalculatorInterface;
use ITB\CalculationPipeline\CouponCollection\MixedCouponCollection;
use ITB\CalculationPipeline\CouponCollection\UnitAmountDiscountCouponCollection;
use ITB\CalculationPipeline\CouponCollection\UnitPriceDiscountCouponCollection;
use ITB\CalculationPipeline\ItemCalculation\ItemCalculationSubTotal;
use ITB\CalculationPipeline\ItemCalculation\ItemCalculationTotal;
use ITB\CalculationPipeline\Processor\UnitAmountDiscountCouponProcessorInterface;
use ITB\CalculationPipeline\Processor\UnitPriceDiscountCouponProcessorInterface;
use ITB\CalculationPipeline\Product\Amount as ProductAmount;
use Money\Money;

final class ItemCalculation
{
    /** @var Product */
    private $product;

    /** @var ProductAmount */
    private $amount;

    /** @var UnitPriceDiscountCouponCollection */
    private $unitPriceDiscountCoupons;
    /** @var UnitAmountDiscountCouponCollection */
    private $unitAmountDiscountCoupons;

    /**
     * @param CalculationRequestItem $calculationRequestItem
     * @param MixedCouponCollection $coupons
     * @param UnitPriceDiscountCouponProcessorInterface $unitPriceDiscountCouponProcessor
     * @param UnitAmountDiscountCouponProcessorInterface $unitAmountDiscountCouponProcessor
     */
    public function __construct(
        CalculationRequestItem $calculationRequestItem,
        MixedCouponCollection $coupons,
        UnitPriceDiscountCouponProcessorInterface $unitPriceDiscountCouponProcessor,
        UnitAmountDiscountCouponProcessorInterface $unitAmountDiscountCouponProcessor
    ) {
        $this->product = $calculationRequestItem->getProduct();
        $this->amount = $calculationRequestItem->getAmount();

        $this->unitPriceDiscountCoupons = $unitPriceDiscountCouponProcessor->process($this, $coupons);
        $this->unitAmountDiscountCoupons = $unitAmountDiscountCouponProcessor->process($this, $coupons);
    }

    /**
     * @return Money
     */
    public function calculateNonDiscountedPrice(): Money
    {
        return $this->product->getUnitPrice()->multiply($this->amount->get());
    }

    /**
     * @param ItemTotalCalculatorInterface $itemTotalCalculator
     * @param Money $nonDiscountedOverallPrice
     * @return ItemCalculationSubTotal
     */
    public function calculateSubTotal(
        ItemTotalCalculatorInterface $itemTotalCalculator,
        Money $nonDiscountedOverallPrice
    ): ItemCalculationSubTotal {
        return $itemTotalCalculator->calculateItemSubTotal(
            $this,
            $this->unitPriceDiscountCoupons,
            $this->unitAmountDiscountCoupons,
            $nonDiscountedOverallPrice
        );
    }

    /**
     * @param ItemCalculationSubTotal $itemCalculationSubTotal
     * @param ItemTotalCalculatorInterface $itemTotalCalculator
     * @return ItemCalculationTotal
     */
    public function calculateTotal(
        ItemCalculationSubTotal $itemCalculationSubTotal,
        ItemTotalCalculatorInterface $itemTotalCalculator
    ): ItemCalculationTotal {
        return $itemTotalCalculator->calculateItemTotal($itemCalculationSubTotal);
    }

    /**
     * @return MixedCouponCollection
     */
    public function getAddedCoupons(): MixedCouponCollection
    {
        return $this->unitPriceDiscountCoupons->merge([$this->unitAmountDiscountCoupons]);
    }

    /**
     * @return ProductAmount
     */
    public function getAmount(): ProductAmount
    {
        return $this->amount;
    }

    /**
     * @return Product
     */
    public function getProduct(): Product
    {
        return $this->product;
    }
}
