<?php
namespace Pyncer\Validation\Rule;

use Pyncer\Validation\Rule\RuleInterface;

class BooleanRule implements RuleInterface
{
    public function defend(mixed $value): mixed
    {
        return $this->clean($value);
    }
    public function isValid(mixed $value): bool
    {
        return true;
    }
    public function clean(mixed $value): mixed
    {
        if ($value === 'true') {
            return true;
        }

        if ($value === 'false') {
            return false;
        }

        return boolval($value);
    }
    public function getError(): ?string
    {
        return 'invalid';
    }
}
