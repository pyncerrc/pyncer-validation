<?php
namespace Pyncer\Validation\Rule;

use Pyncer\Exception\InvalidArgumentException;
use Pyncer\Validation\Rule\AbstractRule;
use Stringable;

use function floatval;
use function is_float;
use function is_int;
use function is_scalar;
use function is_string;
use function strval;
use function trim;

class FloatRule extends AbstractRule
{
    public const array EMPTY = [0, 0.0, '0', '0.0'];

    /**
     * @param null|float $minValue The minimum value a float can be.
     * @param null|float $maxValue The maximum value a float can be.
     * @param bool $allowNull When true, null vlaues are valid.
     * @param bool $allowEmpty When true, empty values are valid.
     */
    public function __construct(
        private ?float $minValue = null,
        private ?float $maxValue = null,
        bool $allowNull = false,
        bool $allowEmpty = false,
    ) {
        parent::__construct(
            allowNull: $allowNull,
            allowEmpty: $allowEmpty,
            empty: self::EMPTY,
        );
    }

    /**
     * @inheritdoc
     */
    public function isValidValue(mixed $value): bool
    {
        if (is_float($value) || is_int($value)) {
            return true;
        }

        if (is_bool($value)) {
            return false;
        }

        if ($value instanceof Stringable) {
            $value = strval($value);
        }

        if (!is_scalar($value)) {
            return false;
        }

        if (is_string($value)) {
            $value = trim($value);
        }

        if ($value === '0' || $value === '0.0') {
            return true;
        }

        if (filter_var($value, FILTER_VALIDATE_FLOAT) === false) {
            return false;
        }

        return true;
    }

    /**
     * @inheritdoc
     */
    protected function isValidConstraint(mixed $value): bool
    {
        if ($value instanceof Stringable) {
            $value = strval($value);
        }

        if (is_string($value)) {
            $value = trim($value);
        }

        if (!is_scalar($value)) {
            return false;
        }

        $value = floatval($value);

        if ($this->minValue !== null && $value < $this->minValue) {
            if (in_array($value, $this->empty, true)) {
                return ($this->allowNull || $this->allowEmpty);
            }

            return false;
        }

        if ($this->maxValue !== null && $value > $this->maxValue) {
            if (in_array($value, $this->empty, true)) {
                return ($this->allowNull || $this->allowEmpty);
            }

            return false;
        }

        return true;
    }

    /**
     * @inheritdoc
     */
    public function cleanConstraint(mixed $value): mixed
    {
        $value = parent::cleanConstraint($value);

        if (!is_scalar($value)) {
            throw new InvalidArgumentException('Invalid value specified.');
        }

        $value = floatval($value);

        if ($this->minValue !== null && $value < $this->minValue) {
            return $this->minValue;
        }

        if ($this->maxValue !== null && $value > $this->maxValue) {
            return $this->maxValue;
        }

        return $value;
    }

    /**
     * @inheritdoc
     */
    protected function isEmpty(mixed $value): bool
    {
        if (is_numeric($value) &&
            in_array(floatval($value), $this->empty, true)
        ) {
            $value = $this->empty[0];
        }

        return parent::isEmpty($value);
    }
}
