<?php
namespace Pyncer\Validation\Rule;

use Pyncer\Exception\InvalidArgumentException;
use Pyncer\Validation\Rule\RuleInterface;

class IdRule implements RuleInterface
{
    public function defend(mixed $value): mixed
    {
        if (!$this->isValid($value)) {
            throw new InvalidArgumentException('Invalid id value specified.');
        }

        return $this->clean($value);
    }
    public function isValid(mixed $value): bool
    {
        if (!is_int($value)) {
            if ($value === null) {
                return true;
            }

            if (strval(intval($value)) !== strval($value)) {
                return false;
            }

            $value = intval($value);
        }

        if ($value < 0) {
            return false;
        }

        return true;
    }
    public function clean(mixed $value): mixed
    {
        return max(0, intval($value));
    }
    public function getError(): ?string
    {
        return 'invalid';
    }
}
