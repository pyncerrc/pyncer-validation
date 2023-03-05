<?php
namespace Pyncer\Validation\Rule;

use Pyncer\Validation\Rule\AbstractRule;
use Stringable;

use function is_scalar;
use function Pyncer\String\len as pyncer_str_len;
use function Pyncer\String\sub as pyncer_str_sub;
use function strval;
use function trim;

class StringRule extends AbstractRule
{
    /**
     * @param null|int $minLength The minimum length a string can be.
     * @param null|int $maxLength The maximum length a string can be.
     * @param bool $allowNull When true, null vlaues are valid.
     * @param bool $allowEmpty When true, empty values are valid.
     * @param bool $allowWhitespace When true, surrounding whitespace will
     *      be allowed.
     */
    public function __construct(
        private ?int $minLength = null,
        private ?int $maxLength = null,
        bool $allowNull = false,
        bool $allowEmpty = false,
        bool $allowWhitespace = false,
    ) {
        parent::__construct(
            allowNull: $allowNull,
            allowEmpty: $allowEmpty,
            allowWhitespace: $allowWhitespace,
        );
    }

    /**
     * @inheritdoc
     */
    protected function isValidValue(mixed $value): bool
    {
        if (is_scalar($value)) {
            return true;
        }

        if ($value instanceof Stringable) {
            return true;
        }

        return false;
    }

    /**
     * @inheritdoc
     */
    protected function isValidConstraint(mixed $value): bool
    {
        $value = strval($value);

        if (!$this->allowWhitespace) {
            $value = trim($value);
        }

        if ($this->minLength !== null && pyncer_str_len($value) < $this->minLength) {
            return false;
        }

        if ($this->maxLength !== null && pyncer_str_len($value) > $this->maxLength) {
            return false;
        }

        return true;
    }

    /**
     * @inheritdoc
     */
    public function cleanConstraint(mixed $value): mixed
    {
        $value = strval($value);

        if (!$this->allowWhitespace) {
            $value = trim($value);
        }

        if ($this->maxLength !== null && pyncer_str_len($value) > $this->maxLength) {
            return pyncer_str_sub($value, 0, $this->maxLength);
        }

        return $value;
    }

    /**
     * @inheritdoc
     */
    protected function isEmpty(mixed $value): bool
    {
        if (is_scalar($value) || $value instanceof Stringable) {
            $value = strval($value);
        }

        return parent::isEmpty($value);
    }
}
