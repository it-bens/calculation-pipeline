<?php

declare(strict_types=1);

namespace ITB\CalculationPipeline;

use ITB\CalculationPipeline\CalculationRequest\Item;
use ITB\CalculationPipeline\CalculationRequest\TaxRate;
use ITB\CalculationPipeline\CouponCollection\MixedCouponCollection;
use Money\Currency;
use Money\Money;

final class CalculationRequest
{
    /** @var array<string, Item> */
    private $items;
    /** @var MixedCouponCollection */
    private $coupons;

    /** @var Currency */
    private $currency;
    /** @var TaxRate */
    private $taxRate;
    /** @var Money */
    private $shippingCosts;

    /**
     * @param Item[] $items
     * @param MixedCouponCollection $coupons
     * @param Currency $currency
     * @param TaxRate $taxRate
     * @param Money $shippingCosts
     */
    public function __construct(
        array $items,
        MixedCouponCollection $coupons,
        Currency $currency,
        TaxRate $taxRate,
        Money $shippingCosts
    ) {
        $this->items = [];
        foreach ($items as $item) {
            if (!$item instanceof Item) {
                // TODO: throw exception
            }

            $this->$items[$item->getProduct()->getProductId()->get()] = $items;
        }

        $this->coupons = $coupons;

        $this->currency = $currency;
        $this->taxRate = $taxRate;
        $this->shippingCosts = $shippingCosts;
    }

    /**
     * This is a copy and not a clone method! The items, the coupons in the collection, the tax rate and the shipping costs are passed by reference.
     *
     * @param MixedCouponCollection $couponsToRemove
     * @return CalculationRequest
     */
    public function copyWithCouponRemoval(MixedCouponCollection $couponsToRemove): CalculationRequest
    {
        return new self(
            $this->items,
            $this->coupons->removeCoupons($couponsToRemove),
            $this->currency,
            $this->taxRate,
            $this->shippingCosts
        );
    }

    /**
     * @return MixedCouponCollection
     */
    public function getCoupons(): MixedCouponCollection
    {
        return $this->coupons;
    }

    /**
     * @return Currency
     */
    public function getCurrency(): Currency
    {
        return $this->currency;
    }

    /**
     * @return Item[]
     */
    public function getItems(): array
    {
        return array_values($this->items);
    }

    /**
     * @return Money
     */
    public function getShippingCosts(): Money
    {
        return $this->shippingCosts;
    }
}
