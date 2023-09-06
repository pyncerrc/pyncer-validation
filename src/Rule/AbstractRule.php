<?php
namespace Pyncer\Validation\Rule;

use Pyncer\Exception\InvalidArgumentException;
use Pyncer\Validation\Rule\RuleInterface;
use Stringable;

use function is_string;
use function trim;

abstract class AbstractRule implements RuleInterface
{
    /**
     * @param bool $allowNull When true, null vlaues are valid.
     * @param bool $allowEmpty When true, empty values are valid.
     * @param mixed $empty The value to use as an empty value.
     * @param bool $allowWhitespace When true, surrounding whitespace will
     *      be allowed.
     */
    public function __construct(
        protected bool $allowNull = false,
        protected bool $allowEmpty = false,
        protected mixed $empty = '',
        protected bool $allowWhitespace = false,
    ) {}

    /**
     * @inheritdoc
     */
    public function defend(mixed $value): mixed
    {
        if (!$this->isValid($value)) {
            throw new InvalidArgumentException('Invalid value specified.');
        }

        return $this->clean($value);
    }

    /**
     * @inheritdoc
     */
    public function isValid(mixed $value): bool
    {
        if ($this->isNull($value) || $this->isEmpty($value)) {
            return ($this->allowNull || $this->allowEmpty);
        }

        if (!$this->isValidValue($value)) {
            return false;
        }

        if (!$this->isValidConstraint($value)) {
            return false;
        }

        return true;
    }

    /**
     * @inheritdoc
     */
    public function isValidAndClean(mixed $value): bool
    {
        if (!$this->isValid($value)) {
            return false;
        }

        if ($this->clean($value) !== $value) {
            return false;
        }

        return true;
    }

    /**
     * Determines if $value is a valid value.
     *
     * @param mixed $value The value to test.
     * @return bool True if the value meets the requirements, otherwise false.
     */
    protected function isValidValue(mixed $value): bool
    {
        return true;
    }

    /**
     * Determines if $value is valid within its constraints.
     *
     * @param mixed $value The value to test.
     * @return bool True if the value meets the requirements, otherwise false.
     */
    protected function isValidConstraint(mixed $value): bool
    {
        return true;
    }

    /**
     * @inheritdoc
     */
    public function clean(mixed $value): mixed
    {
        if ($this->isNull($value)) {
            if ($this->allowNull) {
                return null;
            }

            if ($this->allowEmpty) {
                return $this->empty;
            }

            return '';
        }

        if ($this->isEmpty($value)) {
            if ($this->allowEmpty) {
                return $this->empty;
            }

            if ($this->allowNull) {
                return null;
            }

            return '';
        }

        if (!$this->isValidValue($value)) {
            if ($this->allowNull) {
                return null;
            }

            if ($this->allowEmpty) {
                return $this->empty;
            }

            return '';
        }

        return $this->cleanConstraint($value);
    }

    /**
     * Cleans a value to be within its constraints.
     *
     * @param mixed $value The value to clean.
     * @return mixed The cleaned value.
     */
    public function cleanConstraint(mixed $value): mixed
    {
        if ($value instanceof Stringable) {
            $value = strval($value);
        }

        if (is_string($value) && !$this->allowWhitespace) {
            $value = trim($value);
        }

        return $value;
    }

    /**
     * @inheritdoc
     */
    public function getError(): ?string
    {
        return 'invalid';
    }

    /**
     * Determines if $value is null or not.
     *
     * @param mixed $value The value to check.
     * @return bool True when $value is null, otherwise false.
     */
    protected function isNull(mixed $value): bool
    {
        if ($value === null) {
            return true;
        }

        if ($this->allowEmpty) {
            return false;
        }

        return $this->isEmpty($value);
    }

    /**
     * Determines if $value is empty or not.
     *
     * @param mixed $value The value to check.
     * @return bool True when $value is empty, otherwise false.
     */
    protected function isEmpty(mixed $value): bool
    {
        if ($value instanceof Stringable) {
            $value = strval($value);
        }

        if (is_string($value) && !$this->allowWhitespace) {
            $value = trim($value);
        }

        if ($this->allowNull && $value === null) {
            return false;
        }

        if ($value === '' || $value === $this->empty || $value === null) {
            return true;
        }

        return false;
    }
}
