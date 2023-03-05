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
            empty: 0.0,
        );
    }

    /**
     * @inheritdoc
     */
    public function isValidValue(mixed $value): bool
    {
        if (!is_scalar($value) && !$value instanceof Stringable) {
            return false;
        }

        if (is_string($value)) {
            $value = trim($value);
        }

        if (!is_int($value) && !is_float($value)) {
            if (strval(floatval($value)) !== strval($value)) {
                return false;
            }
        }

        return true;
    }

    /**
     * @inheritdoc
     */
    protected function isValidConstraint(mixed $value): bool
    {
        $value = floatval($value);

        if ($this->minValue !== null && $value < $this->minValue) {
            if ($value === $this->empty) {
                return ($this->allowNull || $this->allowEmpty);
            }

            return false;
        }

        if ($this->maxValue !== null && $value > $this->maxValue) {
            if ($value === $this->empty) {
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
    protected function isNull(mixed $value): bool
    {
        if (is_numeric($value) && floatval($value) === $this->empty) {
            $value = $this->empty;
        }

        return parent::isNull($value);
    }

    /**
     * @inheritdoc
     */
    protected function isEmpty(mixed $value): bool
    {
        if (is_numeric($value) && floatval($value) === $this->empty) {
            $value = $this->empty;
        }

        return parent::isEmpty($value);
    }
}
