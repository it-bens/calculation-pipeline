<?php

declare(strict_types=1);

namespace ITB\CalculationPipeline\Tests\CouponCollection;

use Generator;
use ITB\CalculationPipeline\Coupon\OtherPaymentCoupon\OtherPaymentCoupon;
use ITB\CalculationPipeline\Coupon\OverallDiscountCoupon\OverallPercentagePriceDiscountCoupon;
use ITB\CalculationPipeline\CouponCollection\OtherPaymentCouponCollection;
use ITB\CalculationPipeline\CouponCollectionException\NotAnOtherPaymentCouponException;
use ITB\CalculationPipeline\Discount\PercentageDiscount;
use Money\Money;
use PHPUnit\Framework\TestCase;

final class OtherPaymentCouponCollectionTest extends TestCase
{
    /**
     * @return Generator
     */
    public function provideForTestApplyAllCouponsInCalculation(): Generator
    {
        $coupon1 = new OtherPaymentCoupon('Newsletter5', Money::EUR(5), Money::EUR(0));
        $coupon2 = new OtherPaymentCoupon('Gift10', Money::EUR(10), Money::EUR(0));

        yield 'single 5€ coupon' => [
            new OtherPaymentCouponCollection([$coupon1]),
            Money::EUR(100),
            Money::EUR(150),
            Money::EUR(95)
        ];
        yield 'single 10€ coupon' => [
            new OtherPaymentCouponCollection([$coupon2]),
            Money::EUR(100),
            Money::EUR(150),
            Money::EUR(90)
        ];
        yield '5€ coupon + 10€ coupon' => [
            new OtherPaymentCouponCollection([$coupon1, $coupon2]),
            Money::EUR(100),
            Money::EUR(150),
            Money::EUR(85)
        ];
    }

    /**
     * @return Generator
     */
    public function provideForTestWithInvalidCouponList(): Generator
    {
        $coupons = [
            new OverallPercentagePriceDiscountCoupon('10OnAll', new PercentageDiscount(10), Money::EUR(10)),
            new OtherPaymentCoupon('Gift20', Money::EUR(20), Money::EUR(0))
        ];

        yield [$coupons, NotAnOtherPaymentCouponException::class];
    }

    /**
     * @return Generator
     */
    public function provideTestWithValidCouponList(): Generator
    {
        $coupons = [
            new OtherPaymentCoupon('Newsletter5', Money::EUR(5), Money::EUR(10)),
            new OtherPaymentCoupon('Gift20', Money::EUR(20), Money::EUR(0))
        ];

        yield 'empty list' => [[]];
        yield 'newsletter and gift card' => [$coupons];
    }

    /**
     * @dataProvider provideForTestApplyAllCouponsInCalculation
     *
     * @param OtherPaymentCouponCollection $coupons
     * @param Money $grandTotal
     * @param Money $nonDiscountedOverallPrice
     * @param Money $expectedResult
     * @return void
     */
    public function testApplyAllCouponsInCalculation(
        OtherPaymentCouponCollection $coupons,
        Money $grandTotal,
        Money $nonDiscountedOverallPrice,
        Money $expectedResult
    ): void {
        $result = $coupons->applyAllCouponsInCalculation($grandTotal, $nonDiscountedOverallPrice);
        $this->assertTrue($expectedResult->equals($result));
    }

    /**
     * @dataProvider provideForTestWithInvalidCouponList
     *
     * @param array $coupons
     * @param class-string $expectedException
     * @return void
     */
    public function testWithInvalidCouponList(array $coupons, string $expectedException): void
    {
        $this->expectException($expectedException);
        new OtherPaymentCouponCollection($coupons);
    }

    /**
     * @dataProvider provideTestWithValidCouponList
     *
     * @param array $coupons
     * @return void
     */
    public function testWithValidCouponList(array $coupons): void
    {
        $collection = new OtherPaymentCouponCollection($coupons);
        $this->assertInstanceOf(OtherPaymentCouponCollection::class, $collection);
    }
}