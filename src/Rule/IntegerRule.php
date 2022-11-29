<?php
namespace Pyncer\Validation\Rule;

use Pyncer\Exception\InvalidArgumentException;
use Pyncer\Validation\Rule\RuleInterface;

class IntegerRule implements RuleInterface
{
    public function defend(mixed $value): mixed
    {
        if (!$this->isValid($value)) {
            throw new InvalidArgumentException('Invalid integer value specified.');
        }

        return intval($value);
    }
    public function isValid(mixed $value): bool
    {
        if ($value === null) {
            return true;
        }

        return (strval(intval($value)) === strval($value));
    }
    public function clean(mixed $value): mixed
    {
        if (!$this->isValid($value)) {
            return 0;
        }

        return intval($value);
    }
    public function getError(): ?string
    {
        return 'invalid';
    }
}
