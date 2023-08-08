<?php

declare(strict_types=1);

namespace Abivia\Money;

use InvalidArgumentException;
use UnexpectedValueException;

/**
 * @property-read int $scale
 * @property-read string $value
 */
class Money
{
    protected const IS_GREATER = 1;
    protected const IS_LESS = -1;

    private ?int $scale = null;

    private string $value;

    /**
     * Create a new instance and validate the input.
     *
     * @param string $value
     * @param int|null $scale
     * @param bool $round
     */
    public function __construct(string $value, ?int $scale = null, bool $round = true)
    {
        // This will throw an error if the value is not valid
        if ($round) {
            $roundScale = $scale ?? bcscale();
            $this->value = bcadd($value, '0', $roundScale + 1);
            $this->value = $this->roundResult($this->value, $roundScale);
        } else {
            $this->value = bcadd($value, '0', $scale);
        }

        $this->scale = $scale;
    }

    /**
     * Provide read access to scale and value.
     *
     * @param $name
     * @return mixed
     */
    public function __get($name): mixed
    {
        return $this->$name;
    }

    /**
     * Convert to string.
     * @return string
     */
    public function __toString(): string
    {
        return $this->value;
    }

    /**
     * Get an instance containing the absolute value of this instance.
     * @return $this
     */
    public function abs(): static
    {
        if (static::isNegative($this->value)) {
            return static::make(substr($this->value, 1), $this->scale);
        }

        return clone $this;
    }

    /**
     * Get an instance containing the sum of this and the passed operand value. If no scale is
     * provided, the larger scale of this or the operand is used.
     * @param Money|string $operand
     * @param int|null $scale
     * @param bool $round
     * @return static A new instance containing the sum and scale.
     */
    public function add(Money|string $operand, ?int $scale = null, bool $round = true): static
    {
        if (!($operand instanceof static)) {
            $operand = static::make($operand, $scale, $round);
            return $this->add($operand);
        }
        $scale = $this->maxScale($operand, $scale);
        $value = bcadd($this->value, $operand->value, $scale + ($round ? 1 : 0));
        if ($round) {
            $value = $this->roundResult($value, $scale);
        }

        return static::make($value, $scale);
    }

    /**
     * Get an instance containing the next integer more distant from zero.
     * @return $this
     */
    public function ceil(): static
    {
        $trimmed = $this->trim();
        if (strpos($trimmed, '.') !== false) {
            $delta = $this->value[0] === '-' ? '-1' : '1';
        } else {
            $delta = '0';
        }
        return static::make(bcadd($this->value, $delta, 0), 0);
    }

    /**
     * Compare this value to the operand and return 1 if the operand is greater, -1 if less, and 0
     * if equal.
     * @param Money|string $operand Defaults to zero.
     * @param int|null $scale
     * @param bool $round
     * @return int
     */
    public function comp(Money|string $operand = '0', ?int $scale = null, bool $round = true): int
    {
        if (!$operand instanceof static) {
            $operand = static::make($operand, $scale, $round);
            return $this->comp($operand);
        }
        $scale = $this->maxScale($operand, $scale);

        return bccomp($this->value, $operand->value, $scale);
    }

    private static function compScan($for, ...$values): ?static
    {
        $result = null;
        foreach ($values as $value) {
            if (is_array($value)) {
                $subMax = static::compScan($for, ...$value);
            } elseif (($value instanceof static)) {
                $subMax = $value;
            } else {
                $subMax = static::make($value);
            }
            if ($result === null || $result->comp($subMax) === $for) {
                $result = $subMax;
            }
        }

        return $result;
    }

    /**
     * Return the quotient resulting from the division of this instance by the divisor.
     * @param Money|string $divisor
     * @param int|null $scale
     * @param bool $round
     * @return $this
     */
    public function div(Money|string $divisor, ?int $scale = null, bool $round = true): static
    {
        if (!$divisor instanceof static) {
            $divisor = static::make($divisor, $scale, $round);
            return $this->div($divisor, null, $round);
        }
        if ($divisor->trim() === '0') {
            throw new InvalidArgumentException('Division by zero');
        }

        $scale = $this->maxScale($divisor, $scale);
        $roundScale = $scale + ($round ? 1 : 0);
        $result = bcdiv($this->value, $divisor->value, $roundScale);

        if ($result === null) {
            throw new UnexpectedValueException('bcdiv() returned null!');
        }
        if ($round) {
            $result = $this->roundResult($result, $scale);
        }

        return static::make($result, $scale);
    }

    /**
     * Get an instance containing the next integer closer to zero.
     * @return $this
     */
    public function floor(): static
    {
        return static::make(bcadd($this->value, '0', 0), 0);
    }

    /**
     * Get the scale of this instance. If none is set, return the current bcmath scale.
     * @return int
     */
    public function getScale(): int
    {
        return $this->scale ?? bcscale();
    }

    /**
     * Return true if the value has a fractional part, even if it is empty.
     * @return bool
     */
    protected function hasFractionalPart(): bool
    {
        return false !== strpos($this->value, '.');
    }

    /**
     * Return true if the passed string represents a negative number.
     * @param string $number
     * @return bool
     */
    protected static function isNegative(string $number): bool
    {
        return 0 === strncmp('-', $number, 1);
    }

    /**
     * Create a new instance and return it.
     *
     * @param string $value
     * @param int|null $scale
     * @param bool $round
     * @return static
     */
    public static function make(string $value, ?int $scale = null, bool $round = true): static
    {
        return new static($value, $scale, $round);
    }

    public static function max(...$values): ?static
    {
        return static::compScan(static::IS_LESS, $values);
    }

    /**
     * Get the scale for the result of an operation.
     * @param Money $operand
     * @param int|null $scale
     * @return int
     */
    private function maxScale(Money $operand, ?int $scale = null): int
    {
        if ($scale !== null) {
            return $scale;
        }
        return max($operand->scale ?? bcscale(), $this->scale ?? bcscale());
    }

    public static function min(...$values): ?static
    {
        return static::compScan(static::IS_GREATER, $values);
    }

    /**
     * Return an instance containing the modulus of this value divided by the divisor.
     * @param Money|string $divisor
     * @param int|null $scale
     * @return $this|null
     */
    public function mod(Money|string $divisor, ?int $scale = null): ?static
    {
        if (!$divisor instanceof static) {
            $divisor = static::make($divisor, $scale);
            return $this->mod($divisor, null);
        }
        if ($divisor->trim() === '0') {
            return null;
        }
        $scale = $this->maxScale($divisor);

        return static::make(bcmod($this->value, $divisor->value, $scale), $scale);
    }

    /**
     * Create an instance that is the product of this instance and the operand.
     * @param Money|string $operand
     * @param int|null $scale
     * @param bool $round
     * @return $this
     */
    public function mul(Money|string $operand, ?int $scale = null, bool $round = true): static
    {
        if (!$operand instanceof static) {
            $operand = static::make($operand, $scale);
            return $this->mul($operand, null, $round);
        }
        $scale = $this->maxScale($operand, $scale);
        $roundScale = $scale + ($round ? 1 : 0);
        $result = bcmul($this->value, $operand->value, $roundScale);

        if ($round) {
            $result = $this->roundResult($result, $scale);
        }

        return static::make($result, $scale);
    }

    /**
     * Create an instance that is the product of this instance and the operand.
     * @param Money|string $operand
     * @param int|null $scale
     * @param bool $round
     * @return $this
     */
    public function pow(Money|string $operand, ?int $scale = null, bool $round = true): static
    {
        if (!$operand instanceof static) {
            $operand = static::make($operand, $scale);
            return $this->pow($operand, null, $round);
        }
        $scale = $this->maxScale($operand, $scale);
        $roundScale = $scale + ($round ? 1 : 0);
        $exponent = $operand->trim();
        $result = bcpow($this->value, $exponent, $roundScale);

        if ($round) {
            $result = $this->roundResult($result, $scale);
        }

        return static::make($result, $scale);
    }

    public function round(int $scale = 0): static
    {
        return self::make($this->roundResult($this->value, $scale), $scale);
    }

    private function roundResult(string $value, int $scale): string
    {
        $dot = strpos($value, '.');
        if ($dot === false) {
            return $value;
        }

        $last = substr($value, $dot + $scale + 1, 1);
        $lastDig = $last === '' ? 0 : (int)$last;
        if ($lastDig >= 5) {
            if ($scale) {
                $increment = (self::isNegative($value) ? '-0.' : '0.')
                    . str_repeat('0', $scale - 1) . '1';
            } else {
                $increment = self::isNegative($value) ? '-1.' : '1.';
            }
            $value = bcadd($value, $increment, $scale);
        } else {
            $length = $dot + $scale + ($scale ? 1 : 0);
            $value = substr($value . str_repeat('0', $scale), 0, $length);
        }
        return $value;
    }

    /**
     * Get the minimum significant value for a scale.
     * @param int|null $scale
     * @return static
     */
    public static function quantum(?int $scale = null): static
    {
        $value = $scale ? ('0.' . str_repeat('0', $scale - 1) . '1') : '1';
        return static::make($value, $scale);
    }

    /**
     * Get a new instance with the desired scale.
     * @param int $scale
     * @return $this
     */
    public function scale(int $scale, bool $round = true): static
    {
        if ($round) {
            $result = static::make($this->roundResult($this->value, $scale), $scale);
        } else {
            $result = static::make(bcadd($this->value, '0', $scale), $scale);
        }
        return $result;
    }

    public static function setScale(int $scale): void
    {
        bcscale($scale);
    }

    /**
     * Get an instance containing this value less the passed operand value. If no scale is
     * provided, the larger scale of this or the operand is used.
     * @param Money|string $operand
     * @param int|null $scale
     * @param bool $round
     * @return static A new instance containing the sum and scale.
     */
    public function sub(Money|string $operand, ?int $scale = null, bool $round = true): static
    {
        if (!($operand instanceof static)) {
            $operand = static::make($operand, $scale, $round);
            return $this->sub($operand);
        }
        $scale = $this->maxScale($operand, $scale);
        $value = bcsub($this->value, $operand->value, $scale + ($round ? 1 : 0));
        if ($round) {
            $value = $this->roundResult($value, $scale);
        }

        return static::make($value, $scale);
    }

    protected function trim(): string
    {
        $number = $this->hasFractionalPart() ? rtrim($this->value, '0') : $this->value;

        return rtrim($number, '.') ?: '0';
    }
}
