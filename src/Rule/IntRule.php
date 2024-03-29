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

class IntRule extends AbstractRule
{
    /**
     * @param null|int $minValue The minimum value an integer can be.
     * @param null|int $maxValue The maximum value an integer can be.
     * @param bool $allowNull When true, null vlaues are valid.
     * @param bool $allowEmpty When true, empty values are valid.
     */
    public function __construct(
        private ?int $minValue = null,
        private ?int $maxValue = null,
        bool $allowNull = false,
        bool $allowEmpty = false,
    ) {
        parent::__construct(
            allowNull: $allowNull,
            allowEmpty: $allowEmpty,
            empty: 0,
        );
    }

    /**
     * @inheritdoc
     */
    public function isValidValue(mixed $value): bool
    {
        if (is_int($value)) {
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

        if ($value === '0' || $value === '0.0' || $value === 0.0) {
            return true;
        }

        if (filter_var($value, FILTER_VALIDATE_FLOAT) === false) {
            return false;
        }

        // Floats with decimals are invalid
        if (strval(intval($value)) !== strval(floatval($value))) {
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

        $value = intval($value);

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
        $value = intval($value);

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
        if (is_numeric($value) && intval($value) === $this->empty) {
            $value = $this->empty;
        }

        return parent::isNull($value);
    }

    /**
     * @inheritdoc
     */
    protected function isEmpty(mixed $value): bool
    {
        if (is_numeric($value) && intval($value) === $this->empty) {
            $value = $this->empty;
        }

        return parent::isEmpty($value);
    }
}
