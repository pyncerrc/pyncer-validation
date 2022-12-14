<?php
namespace Pyncer\Validation\Rule;

use Pyncer\Exception\InvalidArgumentException;
use Pyncer\Validation\Rule\RuleInterface;

use function floatval;
use function is_int;
use function is_float;
use function Pyncer\nullify as pyncer_nullify;
use function strval;

class FloatRule implements RuleInterface
{
    public function __construct(
        private ?float $minValue = null,
        private ?float $maxValue = null,
        private bool $allowNull = false,
        private bool $allowEmpty = false,
    ) {}

    public function defend(mixed $value): mixed
    {
        if (!$this->isValid($value)) {
            throw new InvalidArgumentException('Invalid float value specified.');
        }

        return $this->clean($value);
    }

    public function isValid(mixed $value): bool
    {
        if ($value === null) {
            return ($this->allowNull || $this->allowEmpty);
        }

        if (!is_int($value) && !is_float($value)) {
            if (strval(floatval($value)) !== strval($value)) {
                return false;
            }
        }

        $value = floatval($value);

        if ($this->minValue !== null && $value < $this->minValue) {
            if ($value === 0) {
                return ($this->allowNull || $this->allowEmpty);
            }

            return false;
        }

        if ($this->maxValue !== null && $value > $this->maxValue) {
            if ($value === 0) {
                return ($this->allowNull || $this->allowEmpty);
            }

            return false;
        }

        return true;
    }

    public function clean(mixed $value): mixed
    {
        if ($value === null) {
            if ($this->allowNull) {
                return null;
            }

            if ($this->allowEmpty) {
                return 0.0;
            }
        } elseif (pyncer_nullify($value) === null) {
            if ($this->allowEmpty) {
                return 0.0;
            }

            if ($this->allowNull) {
                return null;
            }
        }

        if (!is_int($value) && !is_float($value)) {
            if (strval(floatval($value)) !== strval($value)) {
                if ($this->allowNull) {
                    return null;
                }

                return 0.0;
            }
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

    public function getError(): ?string
    {
        return 'invalid';
    }
}
