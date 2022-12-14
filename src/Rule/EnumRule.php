<?php
namespace Pyncer\Validation\Rule;

use Pyncer\Exception\InvalidArgumentException;
use Pyncer\Validation\Rule\RuleInterface;

use function in_array;
use function strval;

class EnumRule implements RuleInterface
{
    public function __construct(
        private array $values,
        private bool $allowNull = false
    ) {}

    public function defend(mixed $value): mixed
    {
        if (!$this->isValid($value)) {
            throw new InvalidArgumentException('Invalid enum value specified.');
        }

        return $this->clean($value);
    }

    public function isValid(mixed $value): bool
    {
        if ($this->allowNull && $value === null) {
            return true;
        }

        if (!in_array(strval($value), $this->values, true)) {
            return false;
        }

        return true;
    }

    public function clean(mixed $value): mixed
    {
        if ($this->allowNull && $value === null) {
            return null;
        }

        return strval($value);
    }

    public function getError(): ?string
    {
        return 'invalid';
    }
}
