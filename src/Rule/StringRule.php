<?php
namespace Pyncer\Validation\Rule;

use Pyncer\Validation\Rule\RuleInterface;

use function Pyncer\nullify as pyncer_nullify;
use function Pyncer\String\len as pyncer_str_len;
use function Pyncer\String\sub as pyncer_str_sub;
use function strval;

class StringRule implements RuleInterface
{
    public function __construct(
        private ?int $minLength = null,
        private ?int $maxLength = null,
        private bool $allowNull = false,
        private bool $allowEmpty = false,
    ) {}

    public function defend(mixed $value): mixed
    {
        return $this->clean($value);
    }

    public function isValid(mixed $value): bool
    {
        if ($value === null || $value === '') {
            return ($this->allowNull || $this->allowEmpty);
        }

        $value = strval($value);

        if ($this->minLength !== null && pyncer_str_len($value) < $this->minLength) {
            return false;
        }

        if ($this->maxLength !== null && pyncer_str_len($value) > $this->maxLength) {
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
                return '';
            }
        } elseif (pyncer_nullify($value) === null) {
            if ($this->allowEmpty) {
                return '';
            }

            if ($this->allowNull) {
                return null;
            }
        }

        $value = strval($value);

        if ($this->maxLength !== null && pyncer_str_len($value) > $this->maxLength) {
            return pyncer_string_sub($value, 0, $this->maxLength);
        }

        return $value;
    }

    public function getError(): ?string
    {
        return 'invalid';
    }
}
