<?php

declare(strict_types=1);

namespace ITB\CalculationPipeline;

use Iterator;
use ITB\CalculationPipeline\CouponCollection\MixedCouponCollection;

/**
 * @template CouponType
 * @implements Iterator<int, CouponType>
 * @phpstan-consistent-constructor
 */
abstract class CouponCollection implements Iterator
{
    /** @var int */
    private $index = 0;

    /** @var CouponInterface[] */
    private $coupons;

    /**
     * @param CouponInterface[] $coupons
     */
    protected function __construct(array $coupons)
    {
        // No type checks are required because they are done in child classes.
        $this->coupons = array_values($coupons);
    }

    /**
     * @param MixedCouponCollection $couponCollection
     * @param class-string<CouponInterface> $couponType
     * @return CouponCollection<CouponType>
     */
    public static function fromMixedCouponCollectionWithTypeFilter(MixedCouponCollection $couponCollection, string $couponType): CouponCollection
    {
        $filteredCoupons = array_filter($couponCollection->coupons, static function (CouponInterface $coupon) use ($couponType): bool {
            return is_a($coupon, $couponType, true);
        });

        return new static($filteredCoupons);
    }

    /**
     * @return CouponInterface
     */
    public function current(): CouponInterface
    {
        return $this->coupons[$this->index];
    }

    /**
     * @param (callable(CouponInterface): bool) $filterCallback
     * @return CouponCollection<CouponType>
     */
    public function filter(callable $filterCallback): CouponCollection
    {
        $filteredCoupons = array_filter($this->coupons, $filterCallback);

        return new static($filteredCoupons);
    }

    /**
     * @return int
     */
    public function key(): int
    {
        return $this->index;
    }

    /**
     * @param CouponCollection<mixed>[] $collections
     * @return MixedCouponCollection
     */
    public function merge(array $collections): MixedCouponCollection
    {
        $mergedCollections = [];
        foreach ($collections as $collection) {
            $mergedCollections = array_merge($mergedCollections, $collection->coupons);
        }

        return new MixedCouponCollection($mergedCollections);
    }

    /**
     * @return void
     */
    public function next()
    {
        $this->index++;
    }

    /**
     * @param CouponCollection<CouponType> $couponsToRemove
     * @return CouponCollection<CouponType>
     */
    public function removeCoupons(CouponCollection $couponsToRemove): CouponCollection
    {
        $filteredCoupons = array_udiff(
            $this->coupons,
            $couponsToRemove->coupons,
            static function (CouponInterface $coupon, CouponInterface $couponToRemove): int {
                return $coupon->getName() === $couponToRemove->getName() ? 0 : 1;
            }
        );

        return new static($filteredCoupons);
    }

    /**
     * @return void
     */
    public function rewind()
    {
        $this->index = 0;
    }

    /**
     * @return CouponCollection<CouponType>
     */
    public function unique(): CouponCollection
    {
        return new static(array_unique($this->coupons));
    }

    /**
     * @return bool
     */
    public function valid(): bool
    {
        return array_key_exists($this->index, $this->coupons);
    }
}
