# Abivia Money: BCMath with rounding for currency calculations

Abivia Money is a compact, fluent library that implements correct rounding logic, with the option 
to fall back to BCMath's truncation mode. Money instances are immutable.

Money implements the BCMath functions essential for financial calculations, along with other useful
functions `ceil`, `floor`, `max`, `min`, and `round`.

Money objects can retain individual scale values. If an operation is performed on two objects with
different scale values, the result will retain the higher precision. 

## The Rounding Problem

Example: $35.04 / 1.15 = $30.469565217

When working with currencies there are no fractional cents. This should be rounded to 30.47. But

```php
echo bcdiv('35.04', '1.15', 2);

30.46
```

That's a rounding error of .95 cents instead of 0.05 cents. Not a big deal for a
dozen calculations but made a million times, that's a difference of $900,000.

```php
use Abivia\Money;

Money::setScale(2);
echo Money::make('35.04')->div('1.15');

30.47
```



