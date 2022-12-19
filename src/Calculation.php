<?php

declare(strict_types=1);

namespace ITB\CalculationPipeline;

use ITB\CalculationPipeline\Calculation\CalculationGrandTotal;
use ITB\CalculationPipeline\Calculation\CalculationSubTotal;
use ITB\CalculationPipeline\Calculation\CalculationTotal;
use ITB\CalculationPipeline\Calculator\ItemTotalCalculatorInterface;
use ITB\CalculationPipeline\Calculator\TotalCalculatorInterface;
use ITB\CalculationPipeline\CouponCollection\MixedCouponCollection;
use ITB\CalculationPipeline\CouponCollection\OtherPaymentCouponCollection;
use ITB\CalculationPipeline\CouponCollection\OverallPriceDiscountCouponCollection;
use ITB\CalculationPipeline\CouponCollection\ShippingCostsDiscountCouponCollection;
use ITB\CalculationPipeline\CouponException\OverallValueTooLowForCouponException;
use ITB\CalculationPipeline\ItemCalculation\ItemCalculationSubTotalCollection;
use ITB\CalculationPipeline\ItemCalculation\ItemCalculationTotalCollection;
use ITB\CalculationPipeline\Processor\OtherPaymentCouponProcessorInterface;
use ITB\CalculationPipeline\Processor\OverallPriceDiscountCouponProcessorInterface;
use ITB\CalculationPipeline\Processor\ShippingCostsDiscountCouponProcessorInterface;
use ITB\CalculationPipeline\Processor\UnitAmountDiscountCouponProcessorInterface;
use ITB\CalculationPipeline\Processor\UnitPriceDiscountCouponProcessorInterface;
use Money\Money;

final class Calculation
{
    /** @var CalculationRequest */
    private $calculationRequest;

    /** @var ItemCalculation[] */
    private $itemCalculations = [];

    /** @var OverallPriceDiscountCouponCollection */
    private $overallPriceDiscountCoupons;
    /** @var ShippingCostsDiscountCouponCollection */
    private $shippingCostsDiscountCoupons;
    /** @var OtherPaymentCouponCollection */
    private $otherPaymentCoupons;
    /** @var MixedCouponCollection */
    private $addedCoupons;

    /** @var Money */
    private $nonDiscountedPrice;

    /** @var MixedCouponCollection */
    private $minimalOverallPriceNotReachedCoupons;

    /**
     * @param CalculationRequest $calculationRequest
     * @param UnitPriceDiscountCouponProcessorInterface $unitPriceDiscountCouponProcessor
     * @param UnitAmountDiscountCouponProcessorInterface $unitAmountDiscountCouponProcessor
     * @param OverallPriceDiscountCouponProcessorInterface $overallPriceDiscountCouponProcessor
     * @param ShippingCostsDiscountCouponProcessorInterface $shippingCostsDiscountCouponProcessor
     * @param OtherPaymentCouponProcessorInterface $otherPaymentCouponProcessor
     */
    public function __construct(
        CalculationRequest $calculationRequest,
        UnitPriceDiscountCouponProcessorInterface $unitPriceDiscountCouponProcessor,
        UnitAmountDiscountCouponProcessorInterface $unitAmountDiscountCouponProcessor,
        OverallPriceDiscountCouponProcessorInterface $overallPriceDiscountCouponProcessor,
        ShippingCostsDiscountCouponProcessorInterface $shippingCostsDiscountCouponProcessor,
        OtherPaymentCouponProcessorInterface $otherPaymentCouponProcessor
    ) {
        $this->calculationRequest = $calculationRequest;

        $this->overallPriceDiscountCoupons = $overallPriceDiscountCouponProcessor->process(
            $this->calculationRequest->getCoupons()
        );
        $this->shippingCostsDiscountCoupons = $shippingCostsDiscountCouponProcessor->process(
            $this->calculationRequest->getCoupons()
        );
        $this->otherPaymentCoupons = $otherPaymentCouponProcessor->process($this->calculationRequest->getCoupons());

        $this->addedCoupons = $this->overallPriceDiscountCoupons->merge(
            [$this->shippingCostsDiscountCoupons, $this->otherPaymentCoupons]
        );
        foreach ($this->calculationRequest->getItems() as $calculationRequestItem) {
            $itemCalculation = new ItemCalculation(
                $calculationRequestItem,
                $this->calculationRequest->getCoupons(),
                $unitPriceDiscountCouponProcessor,
                $unitAmountDiscountCouponProcessor
            );

            $this->itemCalculations[] = $itemCalculation;
            $this->addedCoupons = $this->addedCoupons->merge([$itemCalculation->getAddedCoupons()]);
        }

        $nonDiscountedPrice = new Money(0, $calculationRequest->getCurrency());
        foreach ($this->itemCalculations as $itemCalculation) {
            $nonDiscountedPrice = $nonDiscountedPrice->add($itemCalculation->calculateNonDiscountedPrice());
        }
        $this->nonDiscountedPrice = $nonDiscountedPrice;

        $minimalOverallPriceNotReachedCoupons = [];
        foreach ($this->addedCoupons as $coupon) {
            /** @var CouponInterface $coupon */
            try {
                $coupon->checkApplicabilityInCalculation($this->nonDiscountedPrice);
            } catch (OverallValueTooLowForCouponException $exception) {
                $minimalOverallPriceNotReachedCoupons[] = $coupon;
            }
            // Other exceptions are not caught because they are not expected.
        }

        // array_unique can be used on the coupons because the CouponInterface required a __toString() method.
        $this->minimalOverallPriceNotReachedCoupons = (new MixedCouponCollection(
            $minimalOverallPriceNotReachedCoupons
        ))->unique();
    }

    /**
     * @param TotalCalculatorInterface $totalCalculator
     * @param ItemTotalCalculatorInterface $itemTotalCalculator
     * @return CalculationGrandTotal
     */
    public function calculate(
        TotalCalculatorInterface $totalCalculator,
        ItemTotalCalculatorInterface $itemTotalCalculator
    ): CalculationGrandTotal {
        $subTotal = $this->calculateSubTotal($totalCalculator, $itemTotalCalculator);
        $total = $this->calculateTotal($subTotal, $totalCalculator, $itemTotalCalculator);

        return $this->calculateGrantTotal($total, $totalCalculator);
    }

    /**
     * @return MixedCouponCollection
     */
    public function getMinimalOverallPriceNotReachedCoupons(): MixedCouponCollection
    {
        return $this->minimalOverallPriceNotReachedCoupons;
    }

    /**
     * @param CalculationTotal $calculationTotal
     * @param TotalCalculatorInterface $totalCalculator
     * @return CalculationGrandTotal
     */
    private function calculateGrantTotal(
        CalculationTotal $calculationTotal,
        TotalCalculatorInterface $totalCalculator
    ): CalculationGrandTotal {
        return $totalCalculator->calculateGrandTotal(
            $calculationTotal,
            $this->otherPaymentCoupons,
            $this->nonDiscountedPrice
        );
    }

    /**
     * @param TotalCalculatorInterface $totalCalculator
     * @param ItemTotalCalculatorInterface $itemTotalCalculator
     * @return CalculationSubTotal
     */
    private function calculateSubTotal(
        TotalCalculatorInterface $totalCalculator,
        ItemTotalCalculatorInterface $itemTotalCalculator
    ): CalculationSubTotal {
        $itemCalculationSubTotals = ItemCalculationSubTotalCollection::fromItemCalculation(
            $this->itemCalculations,
            $this->nonDiscountedPrice,
            $itemTotalCalculator
        );

        return $totalCalculator->calculateSubTotal($itemCalculationSubTotals);
    }

    /**
     * @param CalculationSubTotal $calculationSubTotal
     * @param TotalCalculatorInterface $totalCalculator
     * @param ItemTotalCalculatorInterface $itemTotalCalculator
     * @return CalculationTotal
     */
    private function calculateTotal(
        CalculationSubTotal $calculationSubTotal,
        TotalCalculatorInterface $totalCalculator,
        ItemTotalCalculatorInterface $itemTotalCalculator
    ): CalculationTotal {
        $itemCalculationTotals = ItemCalculationTotalCollection::fromItemCalculationSubtotals(
            $calculationSubTotal->getItemCalculationSubTotals(),
            $itemTotalCalculator
        );

        return $totalCalculator->calculateTotal(
            $calculationSubTotal,
            $itemCalculationTotals,
            $this->overallPriceDiscountCoupons,
            $this->shippingCostsDiscountCoupons,
            $this->calculationRequest->getShippingCosts(),
            $this->nonDiscountedPrice
        );
    }
}
