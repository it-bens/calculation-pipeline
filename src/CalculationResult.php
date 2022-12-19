<?php

declare(strict_types=1);

namespace ITB\CalculationPipeline;

use ITB\CalculationPipeline\Calculation\CalculationGrandTotal;
use ITB\CalculationPipeline\Calculation\CalculationSubTotal;
use ITB\CalculationPipeline\Calculation\CalculationTotal;

final class CalculationResult
{
    /** @var CalculationRequest */
    private $request;

    /** @var CalculationSubTotal */
    private $subTotal;
    /** @var CalculationTotal */
    private $total;
    /** @var CalculationGrandTotal */
    private $grandTotal;

    /**
     * @param CalculationRequest $request
     * @param CalculationGrandTotal $grandTotal
     */
    public function __construct(
        CalculationRequest $request,
        CalculationGrandTotal $grandTotal
    ) {
        $this->request = $request;
        $this->subTotal = $grandTotal->getCalculationTotal()->getCalculationSubTotal();
        $this->total = $grandTotal->getCalculationTotal();
        $this->grandTotal = $grandTotal;
    }
}
