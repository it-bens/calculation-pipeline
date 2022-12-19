<?php

declare(strict_types=1);

namespace ITB\CalculationPipeline;

use ITB\CalculationPipeline\Calculator\ItemTotalCalculatorInterface;
use ITB\CalculationPipeline\Calculator\TotalCalculatorInterface;
use ITB\CalculationPipeline\Processor\OtherPaymentCouponProcessorInterface;
use ITB\CalculationPipeline\Processor\OverallPriceDiscountCouponProcessorInterface;
use ITB\CalculationPipeline\Processor\ShippingCostsDiscountCouponProcessorInterface;
use ITB\CalculationPipeline\Processor\UnitAmountDiscountCouponProcessorInterface;
use ITB\CalculationPipeline\Processor\UnitPriceDiscountCouponProcessorInterface;

final class CalculationPipeline
{
    /** @var UnitPriceDiscountCouponProcessorInterface */
    private $unitPriceDiscountCouponProcessor;
    /** @var UnitAmountDiscountCouponProcessorInterface */
    private $unitAmountDiscountCouponProcessor;
    /** @var OverallPriceDiscountCouponProcessorInterface */
    private $overallPriceDiscountCouponProcessor;
    /** @var ShippingCostsDiscountCouponProcessorInterface */
    private $shippingCostsDiscountCouponProcessor;
    /** @var OtherPaymentCouponProcessorInterface */
    private $otherPaymentCouponProcessor;

    /** @var TotalCalculatorInterface */
    private $totalCalculator;
    /** @var ItemTotalCalculatorInterface */
    private $itemTotalCalculator;

    /**
     * @param UnitPriceDiscountCouponProcessorInterface $unitPriceDiscountCouponProcessor
     * @param UnitAmountDiscountCouponProcessorInterface $unitAmountDiscountCouponProcessor
     * @param OverallPriceDiscountCouponProcessorInterface $overallPriceDiscountCouponProcessor
     * @param ShippingCostsDiscountCouponProcessorInterface $shippingCostsDiscountCouponProcessor
     * @param OtherPaymentCouponProcessorInterface $otherPaymentCouponProcessor
     * @param TotalCalculatorInterface $totalCalculator
     * @param ItemTotalCalculatorInterface $itemTotalCalculator
     */
    public function __construct(
        UnitPriceDiscountCouponProcessorInterface $unitPriceDiscountCouponProcessor,
        UnitAmountDiscountCouponProcessorInterface $unitAmountDiscountCouponProcessor,
        OverallPriceDiscountCouponProcessorInterface $overallPriceDiscountCouponProcessor,
        ShippingCostsDiscountCouponProcessorInterface $shippingCostsDiscountCouponProcessor,
        OtherPaymentCouponProcessorInterface $otherPaymentCouponProcessor,
        TotalCalculatorInterface $totalCalculator,
        ItemTotalCalculatorInterface $itemTotalCalculator
    ) {
        $this->unitPriceDiscountCouponProcessor = $unitPriceDiscountCouponProcessor;
        $this->unitAmountDiscountCouponProcessor = $unitAmountDiscountCouponProcessor;
        $this->overallPriceDiscountCouponProcessor = $overallPriceDiscountCouponProcessor;
        $this->shippingCostsDiscountCouponProcessor = $shippingCostsDiscountCouponProcessor;
        $this->otherPaymentCouponProcessor = $otherPaymentCouponProcessor;
        $this->totalCalculator = $totalCalculator;
        $this->itemTotalCalculator = $itemTotalCalculator;
    }

    /**
     * @param CalculationRequest $request
     * @return CalculationResult
     */
    public function process(CalculationRequest $request): CalculationResult
    {
        $initialCalculation = new Calculation(
            $request,
            $this->unitPriceDiscountCouponProcessor,
            $this->unitAmountDiscountCouponProcessor,
            $this->overallPriceDiscountCouponProcessor,
            $this->shippingCostsDiscountCouponProcessor,
            $this->otherPaymentCouponProcessor
        );

        $minimalOverallPriceNotReachedCoupons = $initialCalculation->getMinimalOverallPriceNotReachedCoupons();
        $requestWithoutMinimalOverallPriceNotReachedCoupons = $request->copyWithCouponRemoval(
            $minimalOverallPriceNotReachedCoupons
        );

        $calculationWithoutMinimalOverallPriceNotReachedCoupons = new Calculation(
            $requestWithoutMinimalOverallPriceNotReachedCoupons,
            $this->unitPriceDiscountCouponProcessor,
            $this->unitAmountDiscountCouponProcessor,
            $this->overallPriceDiscountCouponProcessor,
            $this->shippingCostsDiscountCouponProcessor,
            $this->otherPaymentCouponProcessor
        );

        $grandTotal = $calculationWithoutMinimalOverallPriceNotReachedCoupons->calculate(
            $this->totalCalculator,
            $this->itemTotalCalculator
        );

        return new CalculationResult($request, $grandTotal);
    }
}
