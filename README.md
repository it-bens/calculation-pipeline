# Calculation Pipeline for prices in carts, checkouts and invoices

Often, the calculation of prices in e-commerce seems to be easy: add all items/products, maybe add shipping costs. That's it. But everyone who ever worked on price calculations in such systems knows that the calculation can get very tedious.

There are different taxes for different items and non-items (like shipping costs). There are coupons that should be applied in a specific order. And there are floating point errors to prevent. This package provides a general (but not perfect) way to do all the required calculations in a standardized way.

## About this package

### Money :moneybag:

This packages uses the `moneyphp/money` package, which is an implementation of the money pattern by Martin Fowler (https://martinfowler.com/eaaCatalog/money.html).

All money related values in this package are represented by a `Money` object and all related calculations are done with them. The `moneyphp/money` package uses bcmath internally for all calculations to prevent floating point issues. However, the `getAmount()` method returns a well-usable float.

### bcmath

bcmath is used for all float calculations in this package.

### Exceptions

This packages defines three exception interfaces which can be used to catch. The `ModelCarGroup\BlueBrixx\CalculationPipeline\Exception\ExceptionInterface` can be used in code outside this package to react to exceptions coming from here. 

The `RequestExceptionInterface` is used for exceptions that occur during the request construction. This includes creation of product objects, coupon objects and the creation of the initial calculation (without any calculations). This covers all data inconsistencies.

The `CalculationExceptionInterface` is used for exceptions that occur during the calculations. These should all be caught and handled by this package itself.

## The Pipe

The price calculation goes through various steps which involve commercial terms and technical terms. This is the general process:

1. A `CalculationRequest` is created. It contains information about the products, the shipping costs, the taxes and so on.
2. The subtotal is calculated for every item in the request. 
3. The subtotals of the items are used to calculate the overall subtotal.
4. The total is calculated for every item using the subtotal and tax calculator. 
5. The totals of the items are used to calculate the overall total together with the shipping costs.
6. The grand overall grant total is calculated with application of payment coupons (like gif cards).
7. A `CalculationResult` is returned which contains every required information for further system-specific processing.

## The Coupons

The general calculation is easy. 4th grade math at the most. But it gets tricky if coupons should be applied.

There are a lot of different coupons out there. Some are more common like a percentage coupon for the whole order and some are rarer in e-commerce systems like 'buy 4, get 1 free'. This packages provides multiple interfaces for different coupon types that should cover most of all.

All coupons have a minimal overall price requirement. A coupon will only be applied if the overall price is above or equal this price. If this is set to `0` the coupon will always be applied.

### Coupon Types

#### Unit Price Discount Coupon

A unit price coupon applies directly to the unit price of a product. It is often whitelisted or blacklisted for certain products. It is applied during **subtotal** calculation.

The coupon is defined by the `UnitPriceDiscountCouponInterface`. Two implementations are provided: the `PercentageDiscountCoupon` and the `VolumeDiscountCoupon`. The `VolumeDiscountCoupon` additionally has a minimal volume requirement after which it will be applied. If this is set to `0`, it acts like a absolute amount discount.

#### Unit Amount Discount Coupon

A unit amount coupon applies to the amount of a product. It can be whitelisted or blacklisted as will. The coupon can reduce the amount of the product for the calculation.

The coupon is defined by the `UnitAmountDiscountCouponInterface`. One implementation is provided: the `RequiredAmountFreeAmountCoupon`. It is created with a required amount and a free amount which results in something like "buy 4, get 1 free". The required amount must be higher than the free amount.

#### Overall Price Discount Coupon

An overall price coupon applies to the whole calculation. It is not applied to the items and will not influence their subtotal and total.

The coupon is defined by the `OverallPriceDiscountCouponInterface`. One implementation is provided: the `OverallPercentagePriceDiscountCoupon`. It is a typical percentage discount.

#### Shipping Costs Discount Coupon

A shipping costs coupon reduced the shipping costs within the calculation.

The coupons are defined by the `ShippingCostsDiscountCouponInterface`. Two implementations are provided: the `ShippingCostsFreeDiscountCoupon` and the `ShippingCostsPercentageDiscountCoupon`. The first will always reduce the shipping costs to `0`. The latter reduces the shipping costs by a provided percentage value.

The coupon for free shipping can be used to provide free shipping if the overall price is above the required price of the coupon. Together with the passed shipping costs in the `CalculationRequest`, this can be used to set different shipping costs and free-limits per country.

#### Other Payment Coupon

An other-payment coupon reduces the overall price by a certain value. It is applied at the end and only used in the grand-total. Because it is handled like a payment it will not be taxed. So this is the only coupon with an absolute value discount used in a gross instead of a net price. Typical use cases are newsletter coupons and gift cards.

The coupons are defined by the `OtherPaymentCouponInterface`. TODO: add implementation.


### Limitations

A major limitation is the missing coupon-compatibility system. It is common that some coupons cannot be combined. However, a reliable implementation of such a system is difficult, because it requires a coupon-usage-optimization.

Let's say a coupon has a list of other coupons it cannot be combined with. If a conflict is detected there are two options: removing one coupon or the other. Both can result in a lower overall price if the removal of one coupon results in the resolution of multiple conflicts. 

Without an analytical way to find the best combination, the only way is to try every combination and compare the results. A naive solution would have a complexity of `O(n!)`!

> :warning: Coupon conflicts have to be resolved before the coupons are passed to the `CalculationRequest`. They cannot be resolved by the pipeline.