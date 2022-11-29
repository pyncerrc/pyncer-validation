<?php
namespace Pyncer\Validation\Rule;

use Pyncer\Validation\Rule\RuleInterface;

use function Pyncer\nullify as pyncer_nullify;

class NullableRule implements RuleInterface
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
        return pyncer_nullify($value);
    }
    public function getError(): ?string
    {
        return null;
    }
}
